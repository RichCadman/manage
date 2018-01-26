<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller{
	public function _initialize(){
		if(!isset($_SESSION['userinfo'])){
			echo "<script type='text/javascript'>alert('非法路径！！！');window.location='/index.php/Login/login';</script>";
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
