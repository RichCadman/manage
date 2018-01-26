<?php
namespace Admin\Controller;
class UsersController extends BaseController {
	//用户总览
    public function users_index(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('users a,manage_position b')
                ->field('a.id,a.username,a.phone,a.sex,a.birthday,a.entry_time,b.name')
                ->where("a.position_id = b.id and is_del = 0")
                ->select();
            $this->assign(array(
                'info'      => $info,
                'display'   => 'Users',
                'current'   => 'users_index',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //添加用户页面
    public function users_add(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            //查询职位
            $position = M('position')->select();
            $this->assign(array(
                'position'      => $position,
                'display'   => 'Users',
                'current'   => 'users_add',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //添加用户
    public function users_add_do(){
        if($_POST['username'] && $_POST['sex']!='' && $_POST['birthday'] && $_POST['phone'] && $_POST['password'] && $_POST['position_id']!=0 && $_POST['entry_time']){
            $_POST['password'] = md5($_POST['password']);
            $res = M('users')->add($_POST);
            if($res){
                //添加日志
                $log['title'] = '添加用户';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
               echo "<script>alert('添加成功！');window.location='/admin.php/Users/users_index';</script>"; 
           }else{
                echo "<script>alert('添加失败！');window.history.go(-1);</script>";
           }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }

    //修改用户页面
    public function users_editor($id){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('users')->where("id = $id")->find();
            $position = M('position')->select();
            $this->assign(array(
                'info'      => $info,
                'position'      => $position,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //修改用户
    public function users_editor_do($id){
        if($_POST['username'] && $_POST['phone'] && $_POST['position_id'] && $_POST['entry_time']){
            $res = M('users')->where("id = $id")->save($_POST);
            if($res){
                //添加日志
                $log['title'] = '修改用户';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('修改成功！');window.location='/admin.php/Users/users_index';</script>"; 
            }else{
                echo "<script>alert('修改失败！');window.history.go(-1);</script>";
            }
        }else{
           echo "<script>alert('请填写完整！');window.history.go(-1);</script>";          $res = M('users')->where("id = $id")->save(); 
        }
    }
    //删除用户
    public function users_del(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $id = $_POST['id'];
            $del['is_del'] = 1;
            $res = M('users')->where("id = $id")->save($del);
            if($res){
                //添加日志
                $log['title'] = '删除用户';
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
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //回收站页面
    public function users_recycle(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('users a,manage_position b')
                ->field('a.id,a.username,a.phone,a.sex,a.birthday,a.entry_time,b.name')
                ->where("a.position_id = b.id and is_del = 1")
                ->select();
            $this->assign(array(
                'info'      => $info,
                'display'   => 'Users',
                'current'   => 'users_recycle',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //回收
    public function users_recycle_do($id){
        if($_GET['id']){
            $data['is_del'] = 0;
            $res = M('users')->where("id = $id")->save($data);
            if($res){
                //添加日志
                $log['title'] = '回收用户';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('操作成功！');window.location='/admin.php/Users/users_index';</script>"; 
            }else{
                echo "<script>alert('操作失败！');window.history.go(-1);</script>";
            }
        }
    }
}