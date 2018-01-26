<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller{
	public function _initialize(){
		if(!isset($_SESSION['userinfo'])){
			echo "<script type='text/javascript'>alert('非法路径！！！');window.location='/admin.php/Login/login';</script>";
		}

        //查询菜单列表
        $menu = M('rule')->where("is_visible = 1")->select();
        $this->assign('menu',$menu);
        //通过权限控制左侧菜单的显示与隐藏
        //用户id
        $uid = $_SESSION['userinfo']['id'];
        //通过用户id查询所属用户组
        $group_access_info = M('group_access')->where("uid = $uid")->find();
        //获取用户组id
        $group_id = $group_access_info['group_id'];
        //根据用户组id查询权限菜单menu_id组
        $group_info = M('group')->where("id = $group_id")->find();
        //权限菜单 id 组
        $menu_info = $group_info['menu_id'];
        //var_dump($rule_info);exit;//字符串格式
        //转为数组
        $menu_array=explode(",",$menu_info);
        $this->assign('menu_array',$menu_array);

        //二级操作菜单  id  组
        $rule_info = $group_info['rules'];
        //转为数组
        $rule_array = explode(",",$rule_info);
        $this->assign('rule_array',$rule_array);

        
        //echo date("Y-m-d H:i:s",'1514460377');exit;
        //检查项目是否有三天未确认的情况
        $now_time = time();
        $info = M('affirm')->where("result = 0")->select();
        foreach ($info as $k=>$v){
            $time = $v['add_time'];
            $cz = ($now_time - $time) / 86400;
            //项目id
            $item_id = $v['item_id'];
            $item = M("item")->where("id = $item_id and is_push = 0")->find();
            if($cz > 3 && $item){
                //超过三天项目冻结
                //客户openID
                $openid = $item['link_openid'];
                //发送推送消息
                Vendor('WXAPI.JSSDK');
                $jssdk = new \JSSDK("","");
                $AccessToken = $jssdk->getAccessToken();
                //拼接链接(固定页面)
                $link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_xq';
                //模板消息（申请模板）
                $template=array(
                        'touser'=>$openid,//用户openID
                        'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                        'url'=>$link,//点击卡片链接地址 
                        'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                        'data'=>array(//模板字段列表
                                'first'=>array('value'=>urlencode("项目冻结通知！"),'color'=>"#FF0000"),
                                'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                'keyword2'=>array('value'=>urlencode('冻结中'),'color'=>'#FF0000'),
                                'keyword3'=>array('value'=>urlencode('项目已冻结'),'color'=>'#FF0000'),
                                'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                'remark'=>array('value'=>urlencode('由于您三天没有确认设计图，项目已经冻结，点击查看详情...'),'color'=>'#FF0000'), )
                            );
                $json_template=json_encode($template);
                //echo $json_template;
                //echo $this->access_token;
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                $res=$jssdk->httpGet($url,urldecode($json_template));
                $res=json_decode($res,true);
                if($res['errcode']==0){
                    //添加日志
                    $log['title'] = '推送冻结消息：'.$item['name'];
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    //标记为已推送
                    $push['is_push'] = 1;
                    //冻结项目
                    $push['is_block'] = 1;
                    $push['block_time'] = time();
                    M("item")->where("id = $item_id")->save($push);
                    //添加冻结信息
                    $data1['message'] = '设计图超三天未确认';
                    $data1['item_id'] = $item_id;
                    M('block')->add($data1);
                    echo "<script>alert('已推送项目冻结信息！项目：".$item['name']."');</script>"; 
                }/*else{
                   echo "<script>alert('项目冻结消息推送失败！项目：".$item['name']."')</script>";  
                }*/
            }
        }

        //检查冻结项目是否有超过十天的情况
        $block_item = M("item")->where("is_block = 1")->select();
        foreach ($block_item as $k => $v) {
            $block_item = $v['block_item'];
            $cz1 = ($now_time - $block_item) / 86400;
            if($cz1 > 10){
                //推送项目终止信息
                //用户openID
                $openid = $item['link_openid'];
                //发送推送消息
                Vendor('WXAPI.JSSDK');
                $jssdk = new \JSSDK("","");
                $AccessToken = $jssdk->getAccessToken();
                //拼接链接(写一个固定页面)
                $link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_zj';
                //模板消息（申请模板）
                $template = array(
                                'touser'=>$openid,//用户openID
                                'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                                'url'=>$link,//点击卡片链接地址 
                                'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                                'data'=>array(//模板字段列表
                                        'first'=>array('value'=>urlencode("项目终止通知！"),'color'=>"#FF0000"),
                                        'keyword1'=>array('value'=>urlencode($v['name']),'color'=>'#FF0000'),
                                        'keyword2'=>array('value'=>urlencode('已终止'),'color'=>'#FF0000'),
                                        'keyword3'=>array('value'=>urlencode('项目已终止'),'color'=>'#FF0000'),
                                        'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                        'remark'=>array('value'=>urlencode('由于您超过10天没有解冻项目，项目已经终止，点击查看详情...'),'color'=>'#FF0000'), 
                                        )
                            );
                $json_template=json_encode($template);
                //echo $json_template;
                //echo $this->access_token;
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                $res=$jssdk->httpGet($url,urldecode($json_template));
                $res=json_decode($res,true);
                if($res['errcode']==0){
                    //添加日志
                    $log['title'] = '推送项目终止消息：'.$item['name'];
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('已推送项目终止信息！项目：".$item['name']."');</script>"; 
                }/*else{
                   echo "<script>alert('项目终止消息推送失败！项目：".$item['name']."')</script>";  
                }*/
            }
        }
		/*$user_id = $_SESSION['userinfo']['id'];
        //查询数据
        $info = M("allot a,manage_item b")
                ->field('a.id,a.duration,a.start_time,b.name')
                ->where("a.is_ok = 0 and a.start_time !='' and a.is_remind =0 and a.item_id = b.id and a.user_id = $user_id")
                ->order("start_time asc")
                ->select();
        //遍历
        foreach ($info as $k => $v) {
    		//计算已经做了多少天
            $day_do = (time() - $v['start_time']) / 86400;
            //实际工期多少天
            $duration = $v['duration'];
            //计算比例
            $percent = $day_do / $duration;
            if($percent >= 0.80){
                //项目提醒
                //$data['result'] = 200;
                $msg = '你有项目：'.$v['name'].' 即将到期,请及时查看进度！';
                //$this->ajaxReturn($data);
                //echo "<script type='text/javascript'>alert('".$msg."')</script>";
                //改变提醒状态
                $id = $v['id'];
                $data_statu['is_remind'] = 0;
                M('allot')->where("id = $id")->save($data_statu);
            }
        }*/
	}
	//空方法
	public function _empty(){
		$this->redirect("Index/index");
	}
}
