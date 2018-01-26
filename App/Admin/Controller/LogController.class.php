<?php
namespace Admin\Controller;
class LogController extends BaseController {
	//日志
    public function log(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('log')->order("time desc")->select();
            $this->assign(array(
                'info'      => $info,
                'display'   => 'Log',
                'current'   => 'log',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
}