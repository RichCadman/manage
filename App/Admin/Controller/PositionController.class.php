<?php
namespace Admin\Controller;
class PositionController extends BaseController {
	 //职位总览
    public function position_index(){
        $info = M('position')->field('id,name,add_time')->select();
        $this->assign('info',$info);
    	$this->assign('current','position_index');
    	$this->display();
    }
    //添加职位页面
    public function position_add(){

    	$this->assign('current','position_add');
    	$this->display();
    }
    //添加职位
    public function position_add_do(){
        if($_POST['name']){
            $data['name'] = $_POST['name'];
            $res = M('position')->add($data);
            if($res){
                //添加日志
                $log['title'] = '添加职位';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
               echo "<script>alert('添加成功！');window.location='/admin.php/Index/position_index';</script>"; 
            }else{
               echo "<script>alert('添加失败！');window.history.go(-1);</script>";
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
        
    }
    //修改职位页面
    public function position_editor($id){
        $info = M('position')->where("id = $id")->find();
        $this->assign('info',$info);
        $this->assign('current','position_index');
        $this->display();
    }
    //编辑职位
    public function position_editor_do($id){
        $data['name'] = $_POST['name'];
        $res = M('position')->where("id = $id")->save($data);
        if($res){
            //添加日志
            $log['title'] = '修改职位';
            $log['username'] = $_SESSION['userinfo']['username'];
            $log['ip'] = $_SERVER['REMOTE_ADDR'];
            M('log')->add($log);
            echo "<script>alert('修改成功！');window.location='/admin.php/Index/position_index';</script>"; 
        }else{
            echo "<script>alert('修改失败！');window.history.go(-1);</script>"; 
        }
    }
    //删除职位
    public function position_del(){
        $id = $_POST['id'];
        $res = M('position')->where("id = $id")->delete();
        if($res){
            //添加日志
            $log['title'] = '删除职位';
            $log['username'] = $_SESSION['userinfo']['username'];
            $log['ip'] = $_SERVER['REMOTE_ADDR'];
            M('log')->add($log);
            $data['statu'] = 200;
            $data['info'] = '删除成功';
            $this->ajaxReturn($data);
        }else{
            $data['statu'] = 400;
            $data['info'] = '删除失败';
            $this->ajaxReturn($data);
        }
    }
}