<?php
namespace Admin\Controller;
class AuthController extends BaseController
{
    //规则总览
    public function rule_index(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('rule')->select();
            $this->assign(array(
                'info'  => $info,
                'display'   => 'Auth',
                'current'   => 'rule_index',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }

    }
    //修改规则页面
    public function rule_editor($id){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            if($_GET['id']){
                //查询数据
                $info = M('rule')->where("id = $id")->find();
                //查询规则组
                $rule = M('rule')->select();
                $this->assign(array(
                    'info'  => $info,
                    'rule'  => $rule,
                    'ling'  => '0',
                    'yi'    => '1',
                    'er'    => '2',
                ));
                $this->display();
            }else{
                echo "<script>alert('缺少参数！');window.history.back();</script>";
            }
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //修改规则
    public function rule_editor_do(){
        //var_dump($_POST);exit;
        if ($_POST['name'] && $_POST['title'] && $_POST['p_id'] != '' && $_POST['nav'] && $_POST['controller']) {
            //父级规则id
            $p_id = $_POST['p_id'];
            //判断添加规则是否正确
            $info1 = M('rule')->where("id = $p_id")->find();
            $p_id1 = $info1['p_id'];
            //当传过来的p_id 或者 根据传过来的id查询出的p_id 等于 0 时，才可进行添加操作
            if ($p_id == 0 || $p_id1 == 0) {
                $id = $_POST['id'];
                $res = M('rule')->where("id = $id")->save($_POST);
                if ($res) {
                    echo "<script>alert('修改成功！');window.location='/admin.php/Auth/rule_index'</script>";
                }
            } else {
                echo "<script>alert('父级规则请选择一级或者二级规则！');window.history.back();</script>";
            }
        } else {
            echo "<script>alert('请填写完整！');window.history.back();</script>";
        }
    }

    //添加规则页面
    public function add_rule()
    {
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('rule')->select();
            $this->assign(array(
                'display' => 'Auth',
                'current' => 'add_rule',
                'info' => $info,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }

    //添加规则
    public function add_rule_do()
    {
        //var_dump($_POST);exit;
        if ($_POST['name'] && $_POST['title'] && $_POST['p_id'] != '' && $_POST['nav'] && $_POST['controller']) {
            //父级规则id
            $p_id = $_POST['p_id'];
            //判断添加规则是否正确
            $info1 = M('rule')->where("id = $p_id")->find();
            $p_id1 = $info1['p_id'];
            //当传过来的p_id 或者 根据传过来的id查询出的p_id 等于 0 时，才可进行添加操作
            if ($p_id == 0 || $p_id1 == 0) {
                $res = M('rule')->add($_POST);
                if ($res) {
                    echo "<script>alert('添加成功！');window.location='/admin.php/Auth/add_rule'</script>";
                }
            } else {
                echo "<script>alert('父级规则请选择一级或者二级规则！');window.history.back();</script>";
            }
        } else {
            echo "<script>alert('填写完整！');window.history.back();</script>";
        }
    }

    //添加用户组页面
    public function add_group()
    {
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('rule')->select();
            $this->assign(array(
                'display' => 'Auth',
                'current' => 'add_group',
                'info' => $info,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }

    }

    //添加用户组
    public function add_group_do()
    {
        if ($_POST['title'] && $_POST['rules']) {
            //处理操作权限组
            $arr = $_POST['rules'];
            //数组转为字符串
            $arr1 = implode(",", $arr);
            $_POST['rules'] = $arr1;

            //处理显示权限组
            $ids = implode(",", $arr);
            //根据提交过来的规则id组查询他们的p_id
            $info = M('rule')->where("id in ($ids)")->select();
            //循环得出p_id
            foreach ($info as $k => $v) {
                $p_id[] = $v['p_id'];
            }
            //数组去重
            $arr2 = array_unique($p_id);
            //数组转为字符串
            $arr2 = implode(",", $arr2);
            $_POST['menu_id'] = $arr2;
            //var_dump($_POST);exit;
            //添加到用户组
            $res = M('group')->add($_POST);
            if ($res) {
                echo "<script>alert('添加成功！');window.location='/admin.php/Auth/add_group'</script>";
            }
        } else {
            echo "<script>alert('填写完整！');window.history.back();</script>";
        }
    }

    //管理用户组
    public function group_index()
    {
       $Auth = new \Think\Auth;
       $uid = $_SESSION['userinfo']['id'];
       $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
       if($res){
            $info = M('group')->select();

            $this->assign(array(
                'display' => 'Auth',
                'current' => 'group_index',
                'info' => $info,
            ));
            $this->display();
       }else{
           echo "<script>alert('权限不足！');window.history.back();</script>";
       }
    }

    //修改用户组页面
    public function group_editor($id)
    {
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('rule')->select();
            $group = M('group')->where("id = $id")->find();
            //获取权限值
            $str = $group['rules'];
            $str = explode(",", $str);
            $this->assign(array(
                'info' => $info,
                'str' => $str,
                'group' => $group,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }

    //修改用户组
    public function group_editor_do()
    {
        if ($_POST['title'] && $_POST['rules']) {
            $title = $_POST['title'];
            $id = $_POST['group_id'];
            //查看修改的标题是否与其他冲突
            $check = M('group')->where("id != $id and title = '$title'")->select();
            if ($check) {
                echo "<script>alert('该用户组名与表中有冲突！');window.history.back();</script>";
            } else {
                $arr = $_POST['rules'];
                //数组转为字符串
                $arr1 = implode(",", $arr);
                $_POST['rules'] = $arr1;

                //处理显示权限组
                $ids = implode(",", $arr);
                //var_dump($ids);exit;
                //根据提交过来的规则id组查询他们的p_id
                $info = M('rule')->where("id in ($ids)")->select();
                //循环得出p_id
                foreach ($info as $k => $v) {
                    $p_id[] = $v['p_id'];
                }
                //数组去重
                $arr2 = array_unique($p_id);
                //数组转为字符串
                $arr2 = implode(",", $arr2);
                $_POST['menu_id'] = $arr2;

                $res = M('group')->where("id = $id")->save($_POST);
                if ($res) {
                    echo "<script>alert('修改成功！');window.location='/admin.php/Auth/group_index'</script>";
                }
            }
        } else {
            echo "<script>alert('填写完整！');window.history.back();</script>";
        }
    }

    //修改用户权限页面
    public function editor_user_auth($id)
    {
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            //接收到用户ID $id
            //根据id查询用户信息
            $user = M('users')->where("id = $id")->find();
            //查询用户组信息
            $group = M('group')->select();
            //查询用户所属用户组id
            $group_id = M('group_access')->where("uid = $id")->find();
            $this->assign(array(
                'user' => $user,
                'group' => $group,
                'group_id' => $group_id,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }

    //修改用户权限
    public function editor_user_auth_do()
    {
        if ($_POST['group_id'] && $_POST['uid']) {
            $uid = $_POST['uid'];
            //检查此用户是否已经分配过用户组
            $res = M('group_access')->where("uid = $uid")->find();
            if ($res) {
                //已经分配过
                //修改用户组
                $res1 = M("group_access")->where("uid = $uid")->save($_POST);
                if ($res1) {
                    echo "<script>alert('操作成功！');window.location='/admin.php/Users/users_index'</script>";
                } else {
                    echo "<script>alert('操作失败！');window.history.back();</script>";
                }
            } else {
                //没有分配过
                $res2 = M('group_access')->add($_POST);
                if ($res2) {
                    echo "<script>alert('操作成功！');window.location='/admin.php/Users/users_index'</script>";
                } else {
                    echo "<script>alert('操作失败！');window.history.back();</script>";
                }
            }
        } else {
            echo "<script>alert('填写完整！');window.history.back();</script>";
        }
    }
}