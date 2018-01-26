<?php
namespace Admin\Controller;
use Think\Controller;
class LoveController extends Controller {
	//查看设计图
    public function affirm(){
    	//图片表id
        if($_GET['id']){
        	$id = $_GET['id'];
        	$info = M("img")->where("id = $id")->find();

        	$this->assign('info',$info);
        	$this->display();
        }else{
        	echo "<script>window.history.go(-1);</script>";
        }
    }
    //确认设计图
    public function ok($item_id){
    	//接收项目id
        //判断是否已经确认过
        $res2 = M('affirm')->where("item_id = $item_id and result = 0")->find();
        $res = array();
        if($res2){
            //确认OK
            $data['result'] = 1;
            $res = M("affirm")->where("item_id = $item_id")->save($data); 
        }
    	if($res){
            //推送消息至设计人员
            //查询openID
            $info = M("affirm")->where("item_id = $item_id")->find();
            Vendor('WXAPI.JSSDK');
            $jssdk = new \JSSDK("","");
            $AccessToken = $jssdk->getAccessToken();
            //echo $this->access_token;exit;
            //拼接链接(通知类消息无需链接)
            //$link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_affirm/id/'.$res;
            //模板消息
            $template=array(
                    'touser'=>$info['openid'],//用户openID
                    'template_id'=>"XQGZqmk1rdHw4XPQsy4HQXM6xPQbUwHYeGzF2GCl3LM",//模板ID--OK
                    //'url'=>$link,//点击卡片链接地址 
                    'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                    'data'=>array(//模板字段列表
                        'first'=>array('value'=>urlencode("设计图满意通知！"),'color'=>"#FF0000"),
                        'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                        'remark'=>array('value'=>urlencode('设计图客户满意！'),'color'=>'#FF0000'), )
                    );
            $json_template=json_encode($template);
            //echo $json_template;
            //echo $this->access_token;
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
            $res=$jssdk->httpGet($url,urldecode($json_template));
            $res=json_decode($res,true);
            if($res){
                //添加日志
                $log['title'] = '设计图满意！';
                $log['username'] = $item['name'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('操作成功！');window.history.go(-1);</script>"; 
            }else{
               echo "<script>alert('操作失败！');window.history.go(-1);</script>"; 
            }
    		              
    	}else{
    		echo "<script>alert('重复操作！');window.history.go(-1);</script>";
    	}
    }
    //不满意
    public function danger($item_id){
        //判断是否已经确认过
        $res2 = M('affirm')->where("item_id = $item_id and result = 0")->find();
        //不满意
        $res1 = array();
        if($res2){
           $data['result'] = 2;
            $res1 = M("affirm")->where("item_id = $item_id")->save($data);
        }
        if($res1 && $res2){
            //查询项目名称
            $item = M('item')->where("id = $item_id")->find();
            //推送
            $info = M("affirm")->where("item_id = $item_id")->find();
            Vendor('WXAPI.JSSDK');
            $jssdk = new \JSSDK("","");
            $AccessToken = $jssdk->getAccessToken();
            //echo $this->access_token;exit;
            //拼接链接(通知类消息无需链接)
            //$link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_affirm/id/'.$res;
            //模板消息
            $template=array(
                    'touser'=>$info['openid'],//用户openID
                    'template_id'=>"XQGZqmk1rdHw4XPQsy4HQXM6xPQbUwHYeGzF2GCl3LM",//模板ID--OK
                    //'url'=>$link,//点击卡片链接地址 
                    'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                    'data'=>array(//模板字段列表
                        'first'=>array('value'=>urlencode("设计图不满意通知！"),'color'=>"#FF0000"),
                        'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                        'remark'=>array('value'=>urlencode('客户是上帝，改吧！'),'color'=>'#FF0000'), )
                    );
            $json_template=json_encode($template);
            //echo $json_template;
            //echo $this->access_token;
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
            $res=$jssdk->httpGet($url,urldecode($json_template));
            $res=json_decode($res,true);
            //var_dump($res);exit;
            if ($res['errcode']==0){
                //echo '发送成功';
                //添加日志
                $log['title'] = '设计图不满意！';
                $log['username'] = $item['name'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('操作成功，联系技术处理！');window.history.go(-1);</script>";
            }else{
               echo "<script>alert('操作失败！');window.history.go(-1);</script>"; 
            }
        }else{
            echo "<script>alert('重复操作！');window.history.go(-1);</script>";
        }
    }
    //查看修改需求内容
    public function mod_demand($id){
        $info = M('record')->where("id = $id")->find();
        $this->assign('info',$info);
        $this->display();
    }
    
    //项目完结确认页面
    public function item_affirm($id){
        $info = M('details')->where("id = $id")->find();
        $this->assign('info',$info);
        $this->display();
    }
    //客户点击项目完结确认
    public function money_ok($id){
        $data['result'] = 1;
        $res = M('details')->where("id = $id")->save($data);
        if($res){
            //查询项目id
            $info = M('details')->where("id = $id")->find();
            $item_id = $info['item_id'];
            //根据id查询项目名称
            $item = M('item')->where("id = $item_id")->find();
            //推送消息
            Vendor('WXAPI.JSSDK');
            $jssdk = new \JSSDK("","");
            $AccessToken = $jssdk->getAccessToken();
            //echo $this->access_token;exit;
            //拼接链接(通知类消息无需链接)
            //$link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_affirm/id/'.$res;
            //模板消息
            $template=array(
                    'touser'=>"oDpc20b1p0IM3THNQ1_ACPiYMUu4",//用户openID  固定的管理员openID
                    'template_id'=>"XQGZqmk1rdHw4XPQsy4HQXM6xPQbUwHYeGzF2GCl3LM",//模板ID--OK
                    //'url'=>$link,//点击卡片链接地址 
                    'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                    'data'=>array(//模板字段列表
                        'first'=>array('value'=>urlencode("客户确认项目完结通知！"),'color'=>"#FF0000"),
                        'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                        'remark'=>array('value'=>urlencode('客户已经确认项目没有问题，并且点击了确认！'),'color'=>'#FF0000'), )
                    );
            $json_template=json_encode($template);
            //echo $json_template;
            //echo $this->access_token;
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
            $res=$jssdk->httpGet($url,urldecode($json_template));
            $res=json_decode($res,true);
            if($res['errcode']==0){
                //添加日志
                $log['title'] = '项目确认完结';
                $log['username'] = $item['name'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('操作成功！');window.history.go(-1);</script>";
            }else{
                echo "<script>alert('操作失败！');window.history.go(-1);</script>";
            }
        }else{
            echo "<script>alert('重复操作！');window.history.go(-1);</script>";
        }
    }
    //项目冻结详情页面
    public function item_xq(){

        $this->display();
    }

    //登陆
    public function login(){
        $appid = "wxec9f9ed5706a50a6";
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri=http://www.dingzankj.com/manage/admin.php/Love/login_do&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
       header("Location:".$url);
    }
    //获取用户信息
    public function login_do(){
        header("Content-type: text/html; charset=utf-8");
        Vendor('Card.class_weixin_adv');    
        //appid   wxec9f9ed5706a50a6
        $weixin=new \class_weixin_adv("","");
        if (isset($_GET['code'])){  
            $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=&secret=&code=".$_GET['code']."&grant_type=authorization_code";
            $res = $weixin->https_request($url);
            $res=(json_decode($res, true));
            $access_token = $res['access_token'];//这个access_token是最新的一个
            $openid = $res['openid'];
            $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN'; 
            $res = $weixin->https_request($get_user_info_url);//调用SDK方法获取到res 从中可以得到openid
            //解析json  
            $row = json_decode($res,true);
            //dump($row);
            //exit;
            if ($row['openid']) {
                $openid = $row['openid'];
                $user = M("customer")->where("openid='$openid'")->find();
                if($user){
                    $_SESSION['customer'] = $user;
                    header('Location:'.__APP__.'/manage/Love/add_customer');
                }else{//未查询到用户openID
                    $data['openid']=$row['openid'];
                    $add_user=M("customer")->add($data);
                    if(!$add_user){
                        echo "<meta charset='utf-8' /><script>alert('no_add_user!');</script>";
                    }else{
                        $userinfo=M("customer")->where("id = $add_user")->find();
                        $_SESSION['customer'] = $userinfo;
                        header('Location:'.__APP__.'/manage/Love/add_customer');
                    }
                }
                
            }else{
                echo "<meta charset='utf-8' /><script>alert('授权出错,请重新授权!');</script>";
                echo "<script>history.back();</script>";
            }
        }else{
            echo "<script>alert('NO CODE!');</script>";
            echo "<script>history.back();</script>";
        }
    }
    //添加客户信息
    public function add_customer(){
        $openid = $_SESSION['customer']['openid'];
        
        $this->assign('info',$openid);   
        $this->display();
    }

    //添加用户
    public function add_customer_do($openid){
        if($_POST['title']){
            $res = M('customer')->where("openid = '$openid'")->save($_POST);
            if($res){
                echo "<script>alert('提交成功！');window.location='/manage/admin.php/Love/add_customer';</script>"; 
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }
}
	