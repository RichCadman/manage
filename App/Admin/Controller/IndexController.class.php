<?php
namespace Admin\Controller;
class IndexController extends BaseController {
	//首页
    public function index(){
        $info = array(
                '操作系统'=>PHP_OS,
                '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
                'ThinkPHP版本'=>THINK_VERSION,
                'PHP运行方式'=>php_sapi_name(),
                '主机名'=>$_SERVER['SERVER_NAME'],
                'WEB服务端口'=>$_SERVER['SERVER_PORT'],
                '网站文档目录'=>$_SERVER["DOCUMENT_ROOT"],
                '浏览器信息'=>substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
                '通信协议'=>$_SERVER['SERVER_PROTOCOL'],
                '请求方法'=>$_SERVER['REQUEST_METHOD'],
                '上传附件限制'=>ini_get('upload_max_filesize'),
                '执行时间限制'=>ini_get('max_execution_time').'秒',
                '服务器时间'=>date("Y年n月j日 H:i:s"),
                '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
                '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
                '用户的IP地址'=>$_SERVER['REMOTE_ADDR'],
                '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
                'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
                'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
                'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign(array(
                'info'      => $info,
                "current"   => 'index',
            ));
		$this->display();    	
    }
    //上传设计效果图
    public function upload_img(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $user_id = $_SESSION['userinfo']['id'];
            $info = M('allot a,manage_item b')
                ->field('b.id,b.name')
                ->where("a.item_id = b.id and a.user_id = $user_id")
                ->select();
            $customer = M('customer')->select();
            $this->assign(array(
                'customer'  => $customer,
                "info"      => $info,
                'display'   => 'Index',
                'current'   => 'upload_img',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //上传设计图
    public function upload_img_do(){
        if($_POST['item_id'] && $_POST['content'] && $_POST['openid']){
            //上传
            if($_POST['submit'] == 'upload'){
                $_POST['user_id'] = $_SESSION['userinfo']['id'];
                $res = M("img")->add($_POST);
                if($res){
                   //添加日志
                    $log['title'] = '上传设计图';
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('操作成功！');window.location='/admin.php/Index/upload_img';</script>";  
                }else{
                    echo "<script>alert('操作失败！');window.history.go(-1);</script>";
                }
            //上传并推送
            }else{
                $_POST['user_id'] = $_SESSION['userinfo']['id'];
                $res = M("img")->add($_POST);
                if($res){
                    //查询项目名称
                    $item_id = $_POST['item_id'];
                    $item = M('item')->where("id = $item_id")->find();
                    Vendor('WXAPI.JSSDK');
                    $jssdk = new \JSSDK("","");
                    $AccessToken = $jssdk->getAccessToken();
                    //echo $this->access_token;exit;
                    //拼接查看设计图链接
                    $link = $_SERVER['SERVER_NAME'].'/admin.php/Love/affirm/id/'.$res;
                    //模板消息
                    $template=array(
                            'touser'=>$_POST['openid'],//用户openID
                            'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                            'url'=>$link,//点击卡片链接地址 
                            'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                            'data'=>array(//模板字段列表
                                    'first'=>array('value'=>urlencode("确认项目设计图通知！"),'color'=>"#FF0000"),
                                    'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                    'keyword2'=>array('value'=>urlencode('进行中'),'color'=>'#FF0000'),
                                    'keyword3'=>array('value'=>urlencode('确认项目设计图'),'color'=>'#FF0000'),
                                    'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                    'remark'=>array('value'=>urlencode('点击查看设计图...'),'color'=>'#FF0000'), )
                                );
                    $json_template=json_encode($template);
                    //echo $json_template;
                    //echo $this->access_token;
                    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                    $res=$jssdk->httpGet($url,urldecode($json_template));
                    $res=json_decode($res,true);
                    if($res['errcode']==0){
                        //添加日志
                        $log['title'] = '上传设计图';
                        $log['username'] = $_SESSION['userinfo']['username'];
                        $log['ip'] = $_SERVER['REMOTE_ADDR'];
                        M('log')->add($log);
                         //链接存表
                        $affirm['link'] = $link;
                        $affirm['item_id'] = $_POST['item_id'];
                        $affirm['add_time'] = time();
                        $affirm['stage'] = '设计';
                        $affirm['openid'] = $_SESSION['userinfo']['openid'];
                        M('affirm')->add($affirm);
                        echo "<script>alert('操作成功！');window.location='/admin.php/Index/upload_img';</script>"; 
                    }else{
                       echo "<script>alert('推送失败！');window.history.go(-1);</script>";  
                    }
                }else{
                    echo "<script>alert('上传失败！');window.history.go(-1);</script>";
                }
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
        }
    }
    //清除缓存
    public function clear_cache(){
        deldir(RUNTIME_PATH);
        //添加日志
        $log['title'] = '清除缓存';
        $log['username'] = $_SESSION['userinfo']['username'];
        $log['ip'] = $_SERVER['REMOTE_ADDR'];
        M('log')->add($log);
        echo "<script>alert('清除成功！');window.history.go(-1);</script>";
    }
    
    
}