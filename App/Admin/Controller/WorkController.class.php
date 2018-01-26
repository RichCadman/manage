<?php

/**
 * @version  Sublime Text 2.0
 * @author  阁下贵姓
 * @email   417626953@qq.com
 * @date    2017-12-4
 */
namespace Admin\Controller;
class WorkController extends BaseController{
	//假条总览
	public function work_index(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('leave a,manage_users b')
                ->field('a.start_time,a.end_time,a.cause,b.username')
                ->where("a.user_id = b.id")
                ->select();
            $this->assign(array(
                'current' => 'work_index',
                'display' => 'Work',
                'info'	  => $info,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
	}
	//上传考勤表
	public function upload(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $this->assign(array(
                'current' => 'upload',
                'display' => 'Work',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
	}

	//上传考勤
	public function upload_do(){
		if($_POST['month'] && $_FILES['content']['error'] == 0){
    		$upload = new \Think\Upload();
			$upload->maxSize = 524288000;//设置附件上传大小
			$upload->exts = array();//设置附件上传类型
			$upload->rootPath="./Pub/upload/";//设置附件上传目录 文件上传保存的根路径
			$upload->savePath = "kaoqin/";//设置附件上传目录  文件上传的保存路径（相对于根路径）
			$info = $upload->uploadOne($_FILES['content']);
			if($info){
				$_POST['content'] = $info['savepath'].$info['savename'];//遍历得到路径
			}
			$res = M('check_work')->add($_POST);
			if($res){
				//添加日志
                $log['title'] = '上传考勤表';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('上传成功！');window.location='/admin.php/Work/upload';</script>";
			}
		}else{
			echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
		}
	}

	//下载考勤
	public function download(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('check_work')->select();
            $this->assign(array(
                'display' => 'Work',
                'current' => 'download',
                'info' 	  => $info,
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
	}
}
 