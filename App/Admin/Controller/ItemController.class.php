<?php
namespace Admin\Controller;
class ItemController extends BaseController {
	//项目总览
    public function item_index(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('item')->field('id,durations,start_time')->select();
            $time = time();
            foreach ($info as $k => $v) {
                if($v['start_time']){
                    //计算差值
                    $cz = round($time - $v['start_time']) / 86400;
                    if($cz > $v['durations']){
                        //更改项目状态为已逾期
                        $data['is_exceed'] = 1;
                        $id = $v['id'];
                        M('item')->where("id = $id")->save($data);
                    }
                }
            }
            $items = M('item')
                ->field('id,name,durations,describe,contract,flow,is_allot,is_finish,is_block,is_push,start_time,is_exceed')
                ->select();
            $this->assign(array(
                "info"      => $items,
                'display'   => 'Item',
                'current'   => 'item_index',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //添加项目页面
    public function item_add(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $this->assign(array(
                'display'   => 'Item',
                'current'   => 'item_add',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
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
	    		echo "<script>alert('添加成功！');window.location='/admin.php/Item/item_index';</script>";
	    	}
    	}else{
    		echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
    	}
    }

    //修改项目
    public function item_editor($id){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('item')->where("id = $id")->find();
            $this->assign(array(
                'info'      => $info,
                'current'   => 'item_index',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
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
	    		echo "<script>alert('修改成功！');window.location='/admin.php/Item/item_index';</script>";
	    	}
    	}else{
    		echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
    	}
    }

    //删除项目
    public function item_del(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
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
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
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

    //项目启动
    public function item_start($item_id){
        $data['start_time'] = time();
        $res = M('item')->where("id = $item_id")->save($data);
        if($res){
           echo "<script>alert('项目开工！');window.location='/admin.php/Item/item_index';</script>"; 
        }else{
            echo "<script>alert('操作失败，稍后再试！');window.history.go(-1);</script>";
        }
    }

    //我的项目
    public function my_item(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $id = $_SESSION['userinfo']['id'];
            $my_item = M('allot a,manage_item b,manage_users c')
                ->field('a.id,a.item_id,a.duration,a.is_ok,a.start_time,a.end_time,a.ratio,b.name,c.username')
                ->where("a.item_id = b.id and a.user_id = c.id and a.user_id = $id")
                ->order('a.is_ok asc')
                ->select();
            $this->assign(array(
                'my_item'      => $my_item,
                'display'   => 'Item',
                'current'   => 'my_item',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }   


    //开始项目
    public function my_item_start($id,$item_id){
        //查询项目是否启动
        $info = M('item')->where("id = $item_id")->find();
        if($info['start_time']){
            $user_id = $_SESSION['userinfo']['id'];
            $data['start_time'] = time();
            $res = M('allot')->where("id = $id")->save($data);
            if($res){
                //添加日志
                $log['title'] = '开始项目';
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('开工成功！');window.location='/admin.php/Item/my_item';</script>";
            }else{
                echo "<script>alert('开工失败！');window.history.go(-1);</script>";
            } 
        }else{
           echo "<script>alert('项目未启动，无法开工！');window.history.go(-1);</script>"; 
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
            echo "<script>alert('完工成功！');window.location='/admin.php/Item/my_item';</script>";
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
            if($durations == $count){
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
                    //添加推送
                    //设计
                    $ui_id = $_POST['user_id_ui'];
                    $ui_info = M('users')->where("id = $ui_id")->find();
                    //实例化Send类
                    Vendor('WXAPI.JSSDK');
                    $jssdk = new \JSSDK("","");
                    $AccessToken = $jssdk->getAccessToken();
                    //拼接链接(系统登录链接)
                    $link = $_SERVER['SERVER_NAME'].'/manage/admin.php';
                    //模板消息（申请模板）
                    $template = array(
                                'touser'=>$ui_info['openid'],//用户openID
                                'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                                'url'=>$link,//点击卡片链接地址 
                                'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                                'data'=>array(//模板字段列表
                                        'first'=>array('value'=>urlencode("项目分配通知！"),'color'=>"#FF0000"),
                                        'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                        'keyword2'=>array('value'=>urlencode('已分配'),'color'=>'#FF0000'),
                                        'keyword3'=>array('value'=>urlencode('分配项目'),'color'=>'#FF0000'),
                                        'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                        'remark'=>array('value'=>urlencode('项目已分配，点击登录查看'),'color'=>'#FF0000'), 
                                        )
                                );
                    $json_template=json_encode($template);
                    //echo $json_template;
                    //echo $this->access_token;
                    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                    $res_ui=$jssdk->httpGet($url,urldecode($json_template));
                    $res_ui=json_decode($res_ui,true);
                    //-------------------------------
                    //前端
                    $web_id = $_POST['user_id_web'];
                    $web_info = M('users')->where("id = $web_id")->find();
                    //模板消息（申请模板）
                    $template = array(
                                'touser'=>$web_info['openid'],//用户openID
                                'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                                'url'=>$link,//点击卡片链接地址 
                                'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                                'data'=>array(//模板字段列表
                                        'first'=>array('value'=>urlencode("项目分配通知！"),'color'=>"#FF0000"),
                                        'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                        'keyword2'=>array('value'=>urlencode('已分配'),'color'=>'#FF0000'),
                                        'keyword3'=>array('value'=>urlencode('分配项目'),'color'=>'#FF0000'),
                                        'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                        'remark'=>array('value'=>urlencode('项目已分配，点击登录查看'),'color'=>'#FF0000'), 
                                        )
                                );
                    $json_template=json_encode($template);
                    //echo $json_template;
                    //echo $this->access_token;
                    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                    $res_web=$jssdk->httpGet($url,urldecode($json_template));
                    $res_web=json_decode($res_web,true);
                    //-------------------------------
                    //程序
                    $code_id = $_POST['user_id_code'];
                    $code_info = M('users')->where("id = $code_id")->find();
                    //模板消息（申请模板）
                    $template = array(
                                'touser'=>$code_info['openid'],//用户openID
                                'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                                'url'=>$link,//点击卡片链接地址 
                                'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                                'data'=>array(//模板字段列表
                                        'first'=>array('value'=>urlencode("项目分配通知！"),'color'=>"#FF0000"),
                                        'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                        'keyword2'=>array('value'=>urlencode('已分配'),'color'=>'#FF0000'),
                                        'keyword3'=>array('value'=>urlencode('分配项目'),'color'=>'#FF0000'),
                                        'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                        'remark'=>array('value'=>urlencode('项目已分配，点击登录查看'),'color'=>'#FF0000'), 
                                        )
                                );
                    $json_template=json_encode($template);
                    //echo $json_template;
                    //echo $this->access_token;
                    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                    $res_code=$jssdk->httpGet($url,urldecode($json_template));
                    $res_code=json_decode($res_code,true);
                    //判断
                    if($res_ui['errcode'] == 0 && $res_web['errcode'] == 0 && $res_code['errcode'] == 0){
                        $item_data['is_allot'] = 1;
                        M('item')->where("id = $item_id")->save($item_data);
                        //添加日志
                        $log['title'] = '分配项目';
                        $log['username'] = $_SESSION['userinfo']['username'];
                        $log['ip'] = $_SERVER['REMOTE_ADDR'];
                        M('log')->add($log);
                        echo "<script>alert('分配成功！');window.location='/admin.php/Item/item_index';</script>";
                    }else{
                        echo "<script>alert('分配成功，推送失败！');window.history.go(-1);</script>";
                    }  
                }

            }else{
               echo "<script>alert('工期分配不合理！');window.history.go(-1);</script>"; 
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }
    //提醒项目进度
    public function remind(){
        //$user_id = $_SESSION['userinfo']['id'];
        //查询数据
        $info = M("allot a,manage_item b")
                ->field('a.id,a.user_id,a.duration,a.start_time,b.name')
                ->where("a.is_ok = 0 and a.start_time !='' and a.is_remind =0 and a.item_id = b.id")
                ->order("start_time asc")
                ->select();
        //var_dump($info);exit;
        $time = time();
        //遍历
        foreach ($info as $k => $v) {
            //计算已经做了多少天
            $day_do = ($time - $v['start_time']) / 86400;
            //实际工期多少天
            $duration = $v['duration'];
            //计算比例
            $percent = $day_do / $duration;
            if($percent >= 0.80){
                //用户id
                $user_id = $v['user_id'];
                //查询用户信息
                $userinfo = M('users')->where("id = $user_id")->find();
                //项目提醒(推送预留接口，通过openID推送)
                //实例化Send类
                Vendor('WXAPI.JSSDK');
                $jssdk = new \JSSDK("","");
                $AccessToken = $jssdk->getAccessToken();
                //echo $this->access_token;exit;
                //模板消息
                $template=array(
                        'touser'=>$userinfo['openid'],//用户openID
                        'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                        'url'=>$_SERVER['SERVER_NAME'].'/manage/admin.php',//卡片链接地址
                        'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                        'data'=>array(//模板字段列表
                                'first'=>array('value'=>urlencode("项目到期提醒！"),'color'=>"#FF0000"),
                                'keyword1'=>array('value'=>urlencode($v['name']),'color'=>'#FF0000'),
                                'keyword2'=>array('value'=>urlencode('进行中'),'color'=>'#FF0000'),
                                'keyword3'=>array('value'=>urlencode('项目工期'),'color'=>'#FF0000'),
                                'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                'remark'=>array('value'=>urlencode('您做的这个项目快要到期，点击登录系统查看'),'color'=>'#FF0000'), )
                            );
                $json_template=json_encode($template);
                //echo $json_template;
                //echo $this->access_token;
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                $res=$jssdk->httpGet($url,urldecode($json_template));
                $res=json_decode($res,true);
                if ($res['errcode']==0){
                    //改变提醒状态
                    $id = $v['id'];
                    $data_statu['is_remind'] = 1;
                    M('allot')->where("id = $id")->save($data_statu);
                    /*$data['statu'] = '200';
                    $data['msg'] = '你有项目：'.$v['name'].' 即将到期,请及时查看进度！';
                    $this->ajaxReturn($data);*/
                }
            }
        }
    }
    //项目关联客户
    public function item_link(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $item = M('item')->select();
            $customer = M('customer')->select();
            $this->assign(array(
                'item'      => $item,
                'customer'  => $customer,
                'display'   => 'Item',
                'current'   => 'item_link',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //项目关联客户
    public function item_link_do(){
        if($_POST['openid'] && $_POST['item_id']){
            $item_id = $_POST['item_id'];
            $data['link_openid'] = $_POST['openid'];
            $res = M('item')->where("id = $item_id")->save($data);
            if($res){
               echo "<script>alert('关联成功！');window.location='/admin.php/Item/item_index';</script>";  
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.go(-1);</script>";
        }
    }
    //增加项目时间页面
    public function item_add_time(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $users = M('users')->where("position_id = 2")->select();
            $this->assign('users',$users);
            $item = M('item')->select();
            $this->assign(array(
                'item'      => $item,
                'customer'  => $customer,
                'display'   => 'Item',
                'current'   => 'item_add_time',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //增加项目时间
    public function item_add_time_do(){
        if($_POST['item_id'] && $_POST['user_id'] && $_POST['day'] >0){
            //增加设计时间
            $item_id = $_POST['item_id'];
            $user_id = $_POST['user_id'];
            $res1 = M('allot')->where("item_id = $item_id and user_id = $user_id")->setInc('duration',$_POST['day']);
            $res2 = M('item')->where("id = $item_id")->setInc('durations',$_POST['day']);
            if($res1 && $res2){
                //添加日志
                $log['title'] = '增加项目时间：'.$_POST['day'];
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('增加成功！');window.location='/admin.php/Item/item_add_time';</script>";
            }else{
               echo "<script>alert('增加失败！');window.history.go(-1);</script>";  
            }
        }else{
           echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
        }
    }
    //项目解冻
    public function item_thaw($id){
        $data['is_block'] = 0;
        $res = M("item")->where("id = $id")->save($data);
        //更改affirm表确认状态
        $data1['result'] = 1;
        $res1 = M('affirm')->where("item_id = $id")->save($data1);
        if($res && $res1){
            $info = M('item')->where("id = $id")->find();
            //添加日志
            $log['title'] = '项目解冻：'.$info['name'];
            $log['username'] = $_SESSION['userinfo']['username'];
            $log['ip'] = $_SERVER['REMOTE_ADDR'];
            M('log')->add($log);
            echo "<script>alert('解冻成功！');window.location='/admin.php/Item/item_index';</script>";
        }else{
           echo "<script>alert('解冻失败！');window.history.go(-1);</script>"; 
        }
    }
    //待确认项目页面
    public function item_affirm(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M("affirm a,manage_item b")
                ->field("a.id,a.result,a.stage,b.name")
                ->where("a.item_id = b.id and a.result = 0")
                ->select();
            $this->assign(array(
                'info'      => $info,
                'display'   => 'Item',
                'current'   => 'item_affirm',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //待确认项目点击确认
    public function item_affirm_do(){
        $id = $_POST['id'];
        $data['result'] = 1;
        $res = M("affirm")->where("id = $id")->save($data);
        if($res){
            $info = M("affirm a,manage_item b")->where("a.item_id = b.id and a.id = $id")->find();
            //添加日志
            $log['title'] = '帮客户点击确认：'.$info['name'];
            $log['username'] = $_SESSION['userinfo']['username'];
            $log['ip'] = $_SERVER['REMOTE_ADDR'];
            M('log')->add($log);
            $data['statu'] = 200;
            $data['info'] = '操作成功';
            $this->ajaxReturn($data); 
        }else{
            $data['statu'] = 400;
            $data['info'] = '操作失败';
            $this->ajaxReturn($data); 
        }
    }
    //项目占比
    public function item_ratio(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('allot a,manage_item b,manage_users c')
                ->field("a.duration,b.name,b.durations,c.username,c.position_id")
                ->where("a.item_id = b.id and a.user_id = c.id")
                ->select();
            $this->assign(array(
                'info'      => $info,
                'display'   => 'Item',
                'current'   => 'item_ratio',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //查看逾期时间
    public function look($item_id){
        $time = time();
        $info = M("item")->where("id = $item_id")->find();
        $cz = ($time - $info['start_time']) / 86400;
        if($cz > 0 && $cz < 1){
            echo "<script>alert('已逾期 1 天');window.history.go(-1);</script>";
        }else{
            echo "<script>alert('已逾期 ".$cz." 天');window.history.go(-1);</script>";
        }
    }
    //查看冻结信息
    public function see($item_id){
        $info = M("block")->where("item_id = $item_id")->order('time desc')->find();
        if($info){
            echo "<script>alert('".$info['message']."');window.history.go(-1);</script>";
        }
    }
}