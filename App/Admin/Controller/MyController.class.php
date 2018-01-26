<?php
namespace Admin\Controller;
class MyController extends BaseController {
	//个人中心
    public function my(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $id = $_SESSION['userinfo']['id'];
            $info = M('users a,manage_position b')
                ->field('a.id,a.username,a.phone,a.sex,a.birthday,a.entry_time,b.name')
                ->where("a.position_id = b.id and a.id = $id")
                ->find();
            $this->assign(array(
                'info'      => $info,
                'display'   => 'My',
                'current'   => 'my',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //修改个人信息页面
    public function my_editor($id){
        $info = M('users')->where("id = $id")->find();
        $this->assign('info',$info);
        $this->assign('current','my');
        $this->assign(array(
                'info'      => $info,
                'current'   => 'my',
            ));
        $this->display();
    }

    //修改个人信息
    public function my_editor_do($id){
        if($_POST['phone'] && $_POST['birthday']){
            $res = M('users')->where("id = $id")->save($_POST);
            if($res){
                //添加日志
                $log['title'] = '修改个人信息';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('修改成功！');window.location='/admin.php/My/my';</script>"; 
            }else{
                echo "<script>alert('修改失败！');window.history.go(-1);</script>";
            }
        }else{
           echo "<script>alert('请填写手机号！');window.history.go(-1);</script>";  
        }
    }

    //修改密码页面
    public function password(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $this->assign(array(
                'display'   => 'My',
                'current'   => 'password',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
     //修改密码
    public function password_editor_do(){
        if($_POST['oldpassword'] && $_POST['pwd']){
            $id = $_SESSION['userinfo']['id'];
            $password = $_SESSION['userinfo']['password'];
            $oldpassword = md5($_POST['oldpassword']);
            if($password == $oldpassword){
                $data['password'] = md5($_POST['pwd']);
                $res = M('users')->where("id = $id")->save($data);
                if($res){
                    //清空原session
                    unset($_SESSION['userinfo']);
                    $_SESSION['userinfo'] = M('users')->where("id = $id")->find();
                    //添加日志
                    $log['title'] = '修改密码';
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('修改成功！');window.location='/admin.php/My/my';</script>";
                }else{
                    echo "<script>alert('修改失败！');window.history.go(-1);</script>";
                }
            }else{
                echo "<script>alert('原密码错误！');window.history.go(-1);</script>";
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
        }
    }

    //填写请假
    public function leave(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $this->assign(array(
                'display' => 'My',
                'current' => 'leave',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }

    //请假
    public function leave_do(){
        if($_POST['cause'] && $_POST['start_time'] && $_POST['end_time']){
            $_POST['user_id'] = $_SESSION['userinfo']['id'];
            $res = M('leave')->add($_POST);
            if($res){
                //推送至经理
                Vendor('WXAPI.JSSDK');
                $jssdk = new \JSSDK("","");
                $AccessToken = $jssdk->getAccessToken();
                //echo $this->access_token;exit;
                //模板消息
                $template=array(
                        'touser'=>"oDpc20en1-o09dtEYhk0RBgHHkZo",//管理员openID
                        'template_id'=>"Lfta2sZMEXGUT30c4kNCGyy3CLtNaRJ-tJ8eumDOsYU",//模板ID
                        'url'=>$_SERVER['SERVER_NAME'].'/manage/admin.php',//点击卡片链接地址 点击之后显示修改的内容
                        'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                        'data'=>array(//模板字段列表
                                'first'=>array('value'=>urlencode($username." 向您递交了假条！"),'color'=>"#FF0000"),
                                'keyword1'=>array('value'=>urlencode($_POST['cause']),'color'=>'#FF0000'),
                                'keyword2'=>array('value'=>urlencode("事假"),'color'=>'#FF0000'),
                                'keyword3'=>array('value'=>urlencode($_POST['start_time']."--".$_POST['end_time']),'color'=>'#FF0000'),
                                'keyword4'=>array('value'=>urlencode($_POST['days'].' 天'),'color'=>'#FF0000'),
                                'remark'=>array('value'=>urlencode('点击登录系统查看详情'),'color'=>'#FF0000'), )
                            );
                $json_template=json_encode($template);
                //echo $json_template;
                //echo $this->access_token;
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                $res=$jssdk->httpGet($url,urldecode($json_template));
                $res=json_decode($res,true);
                //var_dump($res);exit;
                if ($res['errcode']==0){
                    //添加日志
                    $log['title'] = '请假';
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('提交成功！');window.location='/admin.php/My/leave';</script>";
                }else{
                    //添加日志
                    $log['title'] = '请假';
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('提交成功，通知经理失败，请自行通知经理！');window.location='/admin.php/My/leave';</script>"; 
                }
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }
}