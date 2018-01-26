<?php
namespace Admin\Controller;
class PerformanceController extends BaseController {
	//绩效
    public function performance(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('performance a,manage_users b,manage_item c')
                ->field("a.result,a.add_time,b.username,c.name")
                ->where("a.user_id = b.id and a.item_id = c.id")
                ->order("add_time desc")
                ->select();
            $this->assign(array(
                'info'    => $info,
                'display' => 'Performance',
                'current' => 'performance',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }

}