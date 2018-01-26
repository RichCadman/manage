<?php
namespace Home\Controller;
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
    	//确认OK
    	$data['result1'] = 1;
    	$res = M("affirm")->where("item_id = $item_id")->save($data);
    	if($res){
    		echo "<script>alert('操作成功！');window.history.go(-1);</script>"; 
    	}else{
    		echo "<script>alert('重复操作！');window.history.go(-1);</script>";
    	}
    }
}
	