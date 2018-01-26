<?php
namespace Home\Controller;
class IndexController extends BaseController {
	//首页
    public function index(){
        $info = array(
                '操作系统'=>PHP_OS,
                '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
                'ThinkPHP版本'=>THINK_VERSION,
                'PHP运行方式'=>php_sapi_name(),
                '主机名'=>$_SERVER['SERVER_NAME'],
                'WEB服务端口'=>$_SERVER['SERVER_PORT'],
                '网站文档目录'=>$_SERVER["DOCUMENT_ROOT"],
                '浏览器信息'=>substr($_SERVER['HTTP_USER_AGENT'], 0, 40),
                '通信协议'=>$_SERVER['SERVER_PROTOCOL'],
                '请求方法'=>$_SERVER['REQUEST_METHOD'],
                '上传附件限制'=>ini_get('upload_max_filesize'),
                '执行时间限制'=>ini_get('max_execution_time').'秒',
                '服务器时间'=>date("Y年n月j日 H:i:s"),
                '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
                '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
                '用户的IP地址'=>$_SERVER['REMOTE_ADDR'],
                '剩余空间'=>round((disk_free_space(".")/(1024*1024)),2).'M',
                'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
                'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
                'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
		$this->assign("current",'index');
		$this->display();    	
    }
    //项目总览
    public function item_index(){
    	$info = M('item')->field('id,name,durations,describe,contract,flow,is_allot,is_finish')->select();
    	$this->assign('info',$info);
    	$this->assign("current",'item_index');
        $this->assign("display",'item');
    	$this->display();
    }
    //添加项目页面
    public function item_add(){

    	$this->assign("current",'item_add');
        $this->assign("display",'item');
    	$this->display();
    }
    //添加项目
    public function item_add_do(){
    	if($_POST['name'] && $_POST['durations'] && $_POST['describe']){
    		//上传合同
	    	if($_FILES['contract']['error']==0 && $_FILES['flow']['error']==0){
	    		$upload = new \Think\Upload();
				$upload->maxSize = 524288000;//设置附件上传大小
				$upload->exts = array();//设置附件上传类型
				$upload->rootPath="./Pub/upload/";//设置附件上传目录 文件上传保存的根路径
				$upload->savePath = "hetong/";//设置附件上传目录  文件上传的保存路径（相对于根路径）
				$info = $upload->uploadOne($_FILES['contract']);
				if($info){
					$data['contract'] = $info['savepath'].$info['savename'];//遍历得到路径
				}
	    	}else{
	    		echo "<script>alert('请上传合同和流程图！');window.history.go(-1);</script>";
	    		exit;
	    	}
	    	//上传流程图
	    	if($_FILES['contract']['error']==0 && $_FILES['flow']['error']==0){
	    		//echo 2;exit;
	    		$upload = new \Think\Upload();
				$upload->maxSize = 524288000;//设置附件上传大小
				$upload->exts = array();//设置附件上传类型
				$upload->rootPath="./Pub/upload/";//设置附件上传目录 文件上传保存的根路径
				$upload->savePath = "liuchengtu/";//设置附件上传目录  文件上传的保存路径（相对于根路径）
				$info = $upload->uploadOne($_FILES['flow']);
				if($info){
					$data['flow'] = $info['savepath'].$info['savename'];//遍历得到路径
				}
	    	}else{
	    		echo "<script>alert('请上传合同和流程图！');window.history.go(-1);</script>";
	    		exit;
	    	}
	    	$data['name'] = $_POST['name'];//项目名称
	    	$data['durations'] = $_POST['durations'];//工期
	    	$data['describe'] = $_POST['describe'];//描述
	    	$res = M('item')->add($data);
	    	if($res){
                //添加日志
                $log['title'] = '添加项目';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
	    		echo "<script>alert('添加成功！');window.location='/index.php/Index/item_index';</script>";
	    	}
    	}else{
    		echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
    	}
    }

    //修改项目
    public function item_editor($id){
    	$info = M('item')->where("id = $id")->find();
    	$this->assign('info',$info);
        $this->assign("current",'item_index');
    	$this->display();
    }
    //修改项目
    public function item_editor_do($id){
    	if($_POST['name'] && $_POST['durations'] && $_POST['describe']){
    		//上传合同
	    	if($_FILES['contract']['error']==0){
	    		$upload = new \Think\Upload();
				$upload->maxSize = 524288000;//设置附件上传大小
				$upload->exts = array();//设置附件上传类型
				$upload->rootPath="./Pub/upload/";//设置附件上传目录 文件上传保存的根路径
				$upload->savePath = "hetong/";//设置附件上传目录  文件上传的保存路径（相对于根路径）
				$info = $upload->uploadOne($_FILES['contract']);
				if($info){
					$data['contract'] = $info['savepath'].$info['savename'];//遍历得到路径
				}
	    	}
	    	//上传流程图
	    	if($_FILES['flow']['error']==0){
	    		//echo 2;exit;
	    		$upload = new \Think\Upload();
				$upload->maxSize = 524288000;//设置附件上传大小
				$upload->exts = array();//设置附件上传类型
				$upload->rootPath="./Pub/upload/";//设置附件上传目录 文件上传保存的根路径
				$upload->savePath = "liuchengtu/";//设置附件上传目录  文件上传的保存路径（相对于根路径）
				$info = $upload->uploadOne($_FILES['flow']);
				if($info){
					$data['flow'] = $info['savepath'].$info['savename'];//遍历得到路径
				}
	    	}
	    	$data['name'] = $_POST['name'];//项目名称
	    	$data['durations'] = $_POST['durations'];//工期
	    	$data['describe'] = $_POST['describe'];//描述
	    	$res = M('item')->where("id = $id")->save($data);
	    	if($res){
                //添加日志
                $log['title'] = '修改项目';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
	    		echo "<script>alert('修改成功！');window.location='/index.php/Index/item_index';</script>";
	    	}
    	}else{
    		echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
    	}
    }

    //删除项目
    public function item_del(){
        $id = $_POST['id'];
        $res = M('item')->where("id = $id")->delete();
        if($res){
            //添加日志
            $log['title'] = '删除项目';
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

    //项目进度查询
    public function item_query($item_id){
        //UI进度
        $ui = M('allot a,manage_item b,manage_users c')
            ->field('a.is_ok,a.duration,a.start_time,a.end_time,a.ratio,b.name,c.username')
            ->where("a.item_id = b.id and a.user_id = c.id and a.item_id = $item_id and c.position_id = 2")
            ->find();
        //计算开工天数
        $do_day_ui = (time() - $ui['start_time']) / 86400;
        if($do_day_ui >= $ui['duration']){
            $do_day_ui = $ui['duration'];
        }
        //计算百分比
        $ui['percent_ui'] = round(($do_day_ui / $ui['duration'])*100);
        $this->assign('ui',$ui);
        //--------------我是分界线----------------//
        //前端进度
        $web = M('allot a,manage_item b,manage_users c')
            ->field('a.is_ok,a.duration,a.start_time,a.end_time,a.ratio,b.name,c.username')
            ->where("a.item_id = b.id and a.user_id = c.id and a.item_id = $item_id and c.position_id = 3")
            ->find();
        //计算开工天数
        $do_day_web = (time() - $web['start_time']) / 86400;
        if($do_day_web >= $web['duration']){
            $do_day_web = $web['duration'];
        }
        //计算百分比
        $web['percent_ui'] = round(($do_day_web / $web['duration'])*100);
        $this->assign('web',$web);
        //--------------我是分界线----------------//
        //程序进度
        //前端进度
        $code = M('allot a,manage_item b,manage_users c')
            ->field('a.is_ok,a.duration,a.start_time,a.end_time,a.ratio,b.name,c.username')
            ->where("a.item_id = b.id and a.user_id = c.id and a.item_id = $item_id and c.position_id = 4")
            ->find();
        //计算开工天数
        $do_day_code = (time() - $code['start_time']) / 86400;
        if($do_day_code >= $code['duration']){
            $do_day_code = $code['duration'];
        }
        //计算百分比
        $code['percent_ui'] = round(($do_day_code / $code['duration'])*100);
        $this->assign('code',$code);

        //查看项目是否已经完工
        if($ui['is_ok'] ==1 && $web['is_ok'] ==1 && $code['is_ok'] ==1){
            $data_item['is_finish'] = 1;
            M('item')->where("id = $item_id")->save($data_item);
        }
        //--------------我是分界线----------------//
        //总计
        $item_info = M("item")->where("id = $item_id")->find();
        //总工期
        $durations = $item_info['durations'];
        //已做总工期
        if($ui['start_time']  && $web['start_time']  && $code['start_time'] ){
            $total_duration = $do_day_ui + $do_day_web + $do_day_code;
        }else if($ui['start_time']  && $web['start_time']){
            $total_duration = $do_day_ui + $do_day_web;
        }else if($ui['start_time']){
            $total_duration = $do_day_ui;
        }else{
            $total_duration = 0;
        }
        //计算百分比
        $item_info['percent_ui'] = round(($total_duration / $durations)*100); 
        $this->assign('item_info',$item_info);
        $this->display();
    }

    //我的项目
    public function my_item(){
        $id = $_SESSION['userinfo']['id'];
        $my_item = M('allot a,manage_item b,manage_users c')
                ->field('a.id,a.item_id,a.duration,a.is_ok,a.start_time,a.end_time,a.ratio,b.name,c.username')
                ->where("a.item_id = b.id and a.user_id = c.id and a.user_id = $id")
                ->order('a.is_ok asc')
                ->select();
        $this->assign('my_item',$my_item); 
        $this->display();       
    }   

    //开始项目
    public function my_item_start($id){
        $user_id = $_SESSION['userinfo']['id'];
        $data['start_time'] = time();
        $res = M('allot')->where("id = $id")->save($data);
        if($res){
            //添加日志
            $log['title'] = '开始项目';
            $log['username'] = $_SESSION['userinfo']['username'];
            $log['ip'] = $_SERVER['REMOTE_ADDR'];
            M('log')->add($log);
            echo "<script>alert('开工成功！');window.location='/index.php/Index/my_item';</script>";
        }else{
            echo "<script>alert('开工失败！');window.history.go(-1);</script>";
        }

    }

    //完成项目
    public function my_item_ok($id){
        $user_id = $_SESSION['userinfo']['id'];
        $data['end_time'] = time();
        $res = M('allot')->where("id = $id")->save($data);
        if($res){
            //查询数据
            $info = M('allot a,manage_item b')
                    ->field('a.duration,a.start_time,a.end_time,b.id')
                    ->where("a.item_id = b.id and a.id = $id")
                    ->find();
            //项目id
            $item_id = $info['id'];
            //总工期
            $duration = $info['duration'];
            //开始时间
            $start_time = $info['start_time'];
            //结束时间
            $end_time = $info['end_time'];
            //转为天数
            $day = ($end_time - $start_time) / 86400;
            //计算工期比
            $ratio = $day / $duration;
            if($ratio <= 0.80){
                $performance['result'] = '优';
            }else if($ratio <= 0.90){
                $performance['result'] = '甲'; 
            }else if($ratio < 1.0){
                $performance['result'] = '良';
            }else{
                $performance['result'] = '努力吧，骚年！！！';
            }
            $performance['user_id'] = $user_id;
            $performance['item_id'] = $item_id;
            //添加绩效
            M('performance')->add($performance);
            //添加工期比
            $data_ratio['ratio'] = $ratio;
            //改变项目状态
            $data_ratio['is_ok'] = 1;
            M('allot')->where("id = $id")->save($data_ratio);

            //添加日志
            $log['title'] = '完成项目';
            $log['username'] = $_SESSION['userinfo']['username'];
            $log['ip'] = $_SERVER['REMOTE_ADDR'];
            M('log')->add($log);
            echo "<script>alert('完工成功！');window.location='/index.php/Index/my_item';</script>";
        }else{
            echo "<script>alert('完工失败！');window.history.go(-1);</script>";
        }

    }
    //项目分配页面
    public function item_allot($id){
        //查询项目
        $this->assign('id',$id);
        $item = M('item')->where("id = $id")->find();
        $this->assign('item',$item);
        //查询UI设计
        $UI = M('users a,manage_position b')
            ->field("a.id,a.username")
            ->where("a.position_id = b.id and b.name = '设计'")
            ->select();
        $this->assign('UI',$UI);
        //查询web前端
        $web = M('users a,manage_position b')
            ->field("a.id,a.username")
            ->where("a.position_id = b.id and b.name = '前端'")
            ->select();
        $this->assign('web',$web);
        //查询程序
        $code = M('users a,manage_position b')
            ->field("a.id,a.username")
            ->where("a.position_id = b.id and b.name = '程序'")
            ->select();
        $this->assign('code',$code);      
        $this->display();
    }

    //添加项目分配信息
    public function allot_add_do(){
        //项目id
        $item_id = $_POST['item_id'];
        if($_POST['user_id_ui']!=0 && $_POST['duration_ui'] && $_POST['user_id_web']!=0 && $_POST['duration_web'] && $_POST['user_id_code']!=0 && $_POST['duration_code']){
            //查询项目
            $item = M('item')->where("id = $item_id")->find();
            //总工期
            $durations = $item['durations'];
            //提交工期
            $count = $_POST['duration_ui'] + $_POST['duration_web'] + $_POST['duration_code'];
            if($durations >= $count){
                //设计
                $ui_data['user_id'] = $_POST['user_id_ui'];
                $ui_data['item_id'] = $item_id;
                $ui_data['duration'] = $_POST['duration_ui'];
                $ui = M('allot')->add($ui_data);
                //前端
                $web_data['user_id'] = $_POST['user_id_web'];
                $web_data['item_id'] = $item_id;
                $web_data['duration'] = $_POST['duration_web'];
                $web = M('allot')->add($web_data);
                //程序
                $code_data['user_id'] = $_POST['user_id_code'];
                $code_data['item_id'] = $item_id;
                $code_data['duration'] = $_POST['duration_code'];
                $code = M('allot')->add($code_data);
                if($ui && $web && $code){
                    $item_data['is_allot'] = 1;
                    M('item')->where("id = $item_id")->save($item_data);
                    //添加日志
                    $log['title'] = '分配项目';
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                   echo "<script>alert('分配成功！');window.location='/index.php/Index/item_index';</script>"; 
                }

            }else{
               echo "<script>alert('分配工期大于项目总工期！');window.history.go(-1);</script>"; 
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }
    //用户总览
    public function users_index(){
        $info = M('users a,manage_position b')
                ->field('a.id,a.username,a.phone,a.sex,a.birthday,a.entry_time,b.name')
                ->where("a.position_id = b.id and is_del = 0")
                ->select();
        $this->assign('info',$info);
    	$this->assign('current','users_index');
        $this->assign('display','users');
    	$this->display();
    }
    //添加用户页面
    public function users_add(){
        //查询职位
        $position = M('position')->select();
        $this->assign('position',$position);
    	$this->assign('current','users_add');
        $this->assign('display','users');
    	$this->display();
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
               echo "<script>alert('添加成功！');window.location='/index.php/Index/users_index';</script>"; 
           }else{
                echo "<script>alert('添加失败！');window.history.go(-1);</script>";
           }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }

    //修改用户页面
    public function users_editor($id){
        $info = M('users')->where("id = $id")->find();
        $position = M('position')->select();
        $this->assign('info',$info);
        $this->assign('position',$position);
        $this->assign('current','users_add');
        $this->display();
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
                echo "<script>alert('修改成功！');window.location='/index.php/Index/users_index';</script>"; 
            }else{
                echo "<script>alert('修改失败！');window.history.go(-1);</script>";
            }
        }else{
           echo "<script>alert('请填写完整！');window.history.go(-1);</script>";          $res = M('users')->where("id = $id")->save(); 
        }
    }
    //删除用户
    public function users_del(){
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
    }
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
               echo "<script>alert('添加成功！');window.location='/index.php/Index/position_index';</script>"; 
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
            echo "<script>alert('修改成功！');window.location='/index.php/Index/position_index';</script>"; 
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
    //权限总览
    public function authority_index(){

    	$this->assgin('current','authority_index');
    	$this->display();
    }
    //添加权限页面
    public function authority_add(){

    	$this->assign('current','authority_add');
    	$this->display();
    }
    //日志
    public function log(){
        $info = M('log')->order("time desc")->select();
        $this->assign('info',$info);
    	$this->assign('current','log');
    	$this->display();
    }
    //绩效
    public function performance(){
        $info = M('performance a,manage_users b,manage_item c')
                ->field("a.result,a.add_time,b.username,c.name")
                ->where("a.user_id = b.id and a.item_id = c.id")
                ->order("add_time desc")
                ->select();
        $this->assign('info',$info);
    	$this->assign('current','performance');
    	$this->display();
    }

    //个人中心
    public function my(){
        $id = $_SESSION['userinfo']['id'];
        $info = M('users a,manage_position b')
                ->field('a.id,a.username,a.phone,a.sex,a.birthday,a.entry_time,b.name')
                ->where("a.position_id = b.id and a.id = $id")
                ->find();
        $this->assign('info',$info);
        $this->assign('current','my');
        $this->display();
    }
    //修改个人信息页面
    public function my_editor($id){
        $info = M('users')->where("id = $id")->find();
        $this->assign('info',$info);
        $this->assign('current','my');
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
                echo "<script>alert('修改成功！');window.location='/index.php/Index/my';</script>"; 
            }else{
                echo "<script>alert('修改失败！');window.history.go(-1);</script>";
            }
        }else{
           echo "<script>alert('请填写手机号！');window.history.go(-1);</script>";  
        }
    }

    //修改密码页面
    public function password(){

        $this->assign('current','password');
        $this->display();
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
                    echo "<script>alert('修改成功！');window.location='/index.php/Index/my';</script>";
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

    //设计上传效果图
    public function upload_img(){
        $user_id = $_SESSION['userinfo']['id'];
        $info = M('allot a,manage_item b')
                ->field('b.id,b.name')
                ->where("a.item_id = b.id and a.user_id = $user_id")
                ->select();
        $this->assign('info',$info);
        $this->assign('current','upload_img');
        $this->display();
    }
    //上传设计图
    public function upload_img_do(){
        if($_POST['item_id'] && $_POST['content']){
            $_POST['user_id'] = $_SESSION['userinfo']['id'];
            $res = M("img")->add($_POST);
            if($res){
                //添加日志
                $log['title'] = '上传设计图';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                $link = $_SERVER['SERVER_NAME'].'/index.php/Love/affirm/id/'.$res;
                //链接存表
                $affirm['link1'] = $link;
                $affirm['item_id'] = $_POST['item_id'];
                $affirm['add_time1'] = time();
                M('affirm')->add($affirm);
               echo "<script>alert('上传成功！请复制此链接发送给客户确认：".$link."');window.location='/index.php/Index/upload_img';</script>"; 
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
        }
    }
    //清除缓存
    public function clear_cache(){
        deldir(RUNTIME_PATH);
        //添加日志
        $log['title'] = '清除缓存';
        $log['username'] = $_SESSION['userinfo']['username'];
        $log['ip'] = $_SERVER['REMOTE_ADDR'];
        M('log')->add($log);
        echo "<script>alert('清除成功！');window.history.go(-1);</script>";
    }
    //提醒项目进度
    public function remind(){
        $user_id = $_SESSION['userinfo']['id'];
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
                //改变提醒状态
                $id = $v['id'];
                $data_statu['is_remind'] = 1;
                M('allot')->where("id = $id")->save($data_statu);
                //项目提醒
                $data['statu'] = '200';
                $data['msg'] = '你有项目：'.$v['name'].' 即将到期,请及时查看进度！';
                $this->ajaxReturn($data);
                //echo "<script type='text/javascript'>alert('".$msg."')</script>";
                
            }
        }
    }
}