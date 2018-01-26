<?php
namespace Admin\Controller;
class RemarkController extends BaseController {
	//添加修改记录页面
    public function add_remark(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $user_id = $_SESSION['userinfo']['id'];
            $info = M('allot a,manage_item b')
                ->field('b.id,b.name')
                ->where("a.item_id = b.id and a.user_id = $user_id")
                ->select();
            $customer = M('customer')->select();
            $this->assign(array(
                'info'    => $info,
                'customer'=> $customer,
                'display' => 'Remark',
                'current' => 'add_remark',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    
    //添加修改记录并且推送给客户
    public function add_remark_do(){
        //上传
        if($_POST['submit'] == 'upload'){
            if($_POST['item_id'] && $_POST['remark']){
                $_POST['user_id'] = $_SESSION['userinfo']['id'];
                $res = M("record")->add($_POST);
                if($res){
                  //添加日志
                    $log['title'] = '添加修改记录';
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('操作成功！');window.location='/admin.php/Remark/add_remark';</script>";  
                }else{
                    echo "<script>alert('操作失败！');window.history.go(-1);</script>";  
                }
            }else{
                echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
            }   
        //上传并推送
        }else{
            if($_POST['item_id'] && $_POST['remark'] && $_POST['openid']){
                $_POST['user_id'] = $_SESSION['userinfo']['id'];
                $res = M("record")->add($_POST);
                if($res){
                    //查询项目名称
                    $item = M("item")->where("id = $item_id")->find();
                    Vendor('WXAPI.JSSDK');
                    $jssdk = new \JSSDK("","");
                    $AccessToken = $jssdk->getAccessToken();
                    //echo $this->access_token;exit;
                    //模板消息
                    $template=array(
                            'touser'=>$_POST['openid'],//用户openID
                            'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                            'url'=>$_SERVER['SERVER_NAME'].'/manage/admin.php/Love/mod_demand/id/'.$res,//点击卡片链接地址 点击之后显示修改的内容
                            'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                            'data'=>array(//模板字段列表
                                    'first'=>array('value'=>urlencode("项目修改记录提醒！"),'color'=>"#FF0000"),
                                    'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                    'keyword2'=>array('value'=>urlencode('进行中'),'color'=>'#FF0000'),
                                    'keyword3'=>array('value'=>urlencode('项目修改'),'color'=>'#FF0000'),
                                    'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                    'remark'=>array('value'=>urlencode('点击查看详情'),'color'=>'#FF0000'), )
                                );
                    $json_template=json_encode($template);
                    //echo $json_template;
                    //echo $this->access_token;
                    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                    $res=$jssdk->httpGet($url,urldecode($json_template));
                    $res=json_decode($res,true);
                    //var_dump($res);exit;
                    if ($res['errcode']==0){
                        //echo '发送成功';
                        //添加日志
                        $log['title'] = '推送修改记录';
                        $log['username'] = $_SESSION['userinfo']['username'];
                        $log['ip'] = $_SERVER['REMOTE_ADDR'];
                        M('log')->add($log);
                        echo "<script>alert('操作成功！');window.location='/admin.php/Remark/add_remark';</script>";
                    } else {
                        //echo '发送失败';
                        echo "<script>alert('推送失败！');window.history.go(-1);</script>";  
                    }
                }else{
                   echo "<script>alert('操作失败！');window.history.go(-1);</script>";  
                }
            }else{
                echo "<script>alert('请填写完整！');window.history.go(-1);</script>"; 
            }
        }
    }
    //查看修改记录页面
    public function item_record($item_id){
        $info = M("record a,manage_users b,manage_item c")
                ->field("a.id,a.remark,a.add_time,b.username,c.name")
                ->where("a.user_id = b.id and a.item_id = c.id and a.item_id = $item_id")
                ->select();
        $this->assign("info",$info);
        $this->display();
    }
    //查看记录详情页面
    public function item_record_details($id){
        $info = M("record a,manage_item b")
                ->where("a.item_id = b.id and a.id = $id")
                ->find();
        $this->assign('info',$info);
        $this->display();
    }
    //推送静态页面
    public function remark_web(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $this->assign(array(
                'display' => 'Remark',
                'current' => 'remark_web',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //上传压缩包并解压
    public function remark_web_do(){
        //var_dump($_FILES);exit;
        if($_FILES['file_name']['error'] == 0 && $_POST['name']){
            //解压文件所保存的目录
            $time = time();
            $dir = "./zip/".$time;
            if (file_exists($dir) == true) {
                //清空解压文件
                //$this->deldir($dir);
            }
            mkdir($dir);   //创建解压目录
            $title = $_FILES['file_name']['name'];  //上传压缩包名称
            //文件名
            $filename = basename($title,".zip");
            //exit;
            $media_jl = array();  //创建一个空数组
             
            $file = $_FILES['file_name']['tmp_name']; //需要压缩的文件[夹]路径
             
            $type_wj = pathinfo($title, PATHINFO_EXTENSION); //获取文件类型
             //判断文件类型
            if(strtolower($type_wj) == "zip" || strtolower($type_wj) == "rar"){
                if(strtolower($type_wj) == "zip"){
                    //解压zip文件
                    $res = $this->unzip_file($file,$dir); 
                    //解压成功
                    if($res){
                        //链接存表
                        $data['web_link'] = $_SERVER['SERVER_NAME'].'/zip/'.$time.'/'.$filename.'/work_index.html';
                        // echo $data['web_link'];exit;
                        $data['web_name'] = $_POST['name'];
                        $res1 = M('project')->add($data);
                        if($res1){
                            echo "<script>alert('上传成功！');window.location='/admin.php/Remark/remark_web'; </script>";
                        }
                    }else{
                       echo "<script>alert('上传失败！');window.history.back(); </script>"; 
                    }
                }else{
                    //解压rar文件
                   echo "<script>alert('上传文件格式错误！');window.history.back(); </script>";
                }
                //获取解压后的文件
                $array_file = $this->loopFun($dir);
                //var_dump($array_file);exit;
                $wj_count = count($array_file);
                //判断上传文件个数，上传文件不能多于10个
                if ($wj_count > 10) {
                    //清空解压文件
                    $this->deldir($dir);
                    $this->error('上传文件多于10个！');
                }
                //文件上传提交
                if (!empty($array_file)) {
                    foreach ($array_file as $k => $v) {
                       //此处就使用tp的上传或者自己的上传方法……
                    }
                }else{
                    $this->error('压缩包为空！');
                }
            }else{
                echo "<script>alert('上传文件格式错误！');window.history.back(); </script>";
            }
        }else{
           echo "<script>alert('请填写完整！');window.history.back(); </script>"; 
        }
        
    }
    //解压zip文件
    public function unzip_file($file, $dir){ 
        // 实例化对象 
        $zip = new \ZipArchive(); 
        //打开zip文档，如果打开失败返回提示信息 
        if ($zip->open($file) !== TRUE) { 
          die ("无法打开压缩包"); 
        } 
        //将压缩文件解压到指定的目录下 
        $res = $zip->extractTo($dir);
        if($res){
            return true;
            //关闭zip文档 
            $zip->close();
        }else{
            return false;
            //关闭zip文档 
            $zip->close();
        }
          
    } 
    //获取解压文件
    public function loopFun($dir)  
    {  
        $handle = opendir($dir.".");
        //定义用于存储文件名的数组
        $array_file = array();
        while (false !== ($file = readdir($handle)))
        {
            if ($file != "." && $file != "..") {
                $array_file[] = $dir.'/'.$file; //输出文件名
            }
        }
        closedir($handle);
        return $array_file;
        //print_r($array_file);
    }
    //清除解压文件（注：这个清除文件的方法不能清除中文名称的文件）
    function deldir($dir) {
        //先删除目录下的文件：
        $dh=opendir($dir);
        while ($file=readdir($dh)) {
            if($file!="." && $file!="..") {
                $fullpath=$dir."/".$file;
                    if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    deldir($fullpath);
              }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
    /*function deldir($dir){
        exec('rd /s /q '.$dir);
    }*/

    //推送项目页面
    public function remark_web_push(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $items = M("item")->where("is_finish = 0")->select();
            $this->assign('items',$items);
            $projects = M('project')->select();
            $this->assign(array(
                'projects'    => $projects,
                'display' => 'Remark',
                'current' => 'remark_web_push',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //推送项目
    public function remark_web_push_do(){
        if($_POST['id'] && $_POST['item_id']){
            $item_id = $_POST['item_id'];
            //查询用户openID
            $data1 = M('item')->where("id = $item_id")->find();
            //客户openID
            $openid = $data1['link_openid'];
            //项目名称
            $item_name = $data1['name'];
            $id = $_POST['id'];
            //查询推送链接
            $data = M('project')->where("id = $id")->find();
            $link = $data['web_link'];
            Vendor('WXAPI.JSSDK');
            $jssdk = new \JSSDK("","");
            $AccessToken = $jssdk->getAccessToken();
            //echo $this->access_token;exit;
            //模板消息
            $template=array(
                    'touser'=>$openid,//用户openID
                    'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                    'url'=>$link,//点击卡片链接地址 
                    'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                    'data'=>array(//模板字段列表
                        'first'=>array('value'=>urlencode("项目静态页面"),'color'=>"#FF0000"),
                        'keyword1'=>array('value'=>urlencode($item_name),'color'=>'#FF0000'),
                        'keyword2'=>array('value'=>urlencode('进行中'),'color'=>'#FF0000'),
                        'keyword3'=>array('value'=>urlencode('设计设计图'),'color'=>'#FF0000'),
                        'keyword4'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                        'remark'=>array('value'=>urlencode('点击查看...'),'color'=>'#FF0000'), )
                    );
            $json_template=json_encode($template);
            //echo $json_template;
            //echo $this->access_token;
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
            $res=$jssdk->httpGet($url,urldecode($json_template));
            $res=json_decode($res,true);
            //var_dump($res);exit;
            if ($res['errcode']==0){
                //echo '发送成功';
                //添加日志
                $log['title'] = '推送项目：'.$item_name;
                $log['username'] = $_SESSION['userinfo']['username'];
                $log['ip'] = $_SERVER['REMOTE_ADDR'];
                M('log')->add($log);
                echo "<script>alert('操作成功！');window.location='/admin.php/Remark/remark_web_push';</script>";
            } else {
                //echo '发送失败';
                echo "<script>alert('推送失败！');window.history.go(-1);</script>";  
            }
        }else{
            echo "<script>alert('请填写完整！');window.history.back(); </script>";
        }
    }
    //推送项目完结页面
    public function remark_money(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $items = M("item")->where("is_finish = 0")->select();
            $this->assign(array(
                'items'    => $items,
                'display' => 'Remark',
                'current' => 'remark_money',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //推送项目完结
    public function remark_money_do(){
        if($_POST['item_id'] && $_POST['content']){
            $item_id = $_POST['item_id'];
            $info = M('item')->where("id = $item_id")->find();
            //添加
            $res = M('details')->add($_POST);
            if($res){

                //推送信息
                Vendor('WXAPI.JSSDK');
                $jssdk = new \JSSDK("","");
                $AccessToken = $jssdk->getAccessToken();
                //echo $this->access_token;exit;
                //拼接链接
                $link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_affirm/id/'.$res;
                //模板消息
                $template=array(
                        'touser'=>$info['link_openid'],//用户openID
                        'template_id'=>"LyT3V71lZJvXvaASyKfZ5inoVAdys9N4WgHLfeel_Bg",//模板ID
                        'url'=>$link,//点击卡片链接地址 
                        'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                        'data'=>array(//模板字段列表
                            'first'=>array('value'=>urlencode("项目完结确认通知！"),'color'=>"#FF0000"),
                            'keyword1'=>array('value'=>urlencode($info['name']),'color'=>'#FF0000'),
                            'keyword2'=>array('value'=>urlencode('项目完结'),'color'=>'#FF0000'),
                            'keyword3'=>array('value'=>urlencode('项目完结确认'),'color'=>'#FF0000'),
                            'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                            'remark'=>array('value'=>urlencode('点击查看详情...'),'color'=>'#FF0000'), )
                        );
                $json_template=json_encode($template);
                //echo $json_template;
                //echo $this->access_token;
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                $res=$jssdk->httpGet($url,urldecode($json_template));
                $res=json_decode($res,true);
                //var_dump($res);exit;
                if ($res['errcode']==0){
                    //echo '发送成功';
                    //添加日志
                    $log['title'] = '推送项目完结：'.$info['name'];
                    $log['username'] = $_SESSION['userinfo']['username'];
                    $log['ip'] = $_SERVER['REMOTE_ADDR'];
                    M('log')->add($log);
                    echo "<script>alert('操作成功！');window.location='/admin.php/Remark/remark_money';</script>";
                }else{
                   echo "<script>alert('推送失败！');window.history.back(); </script>";
                }
            }
        }else{
           echo "<script>alert('请填写完整！');window.history.back(); </script>"; 
        }
    }
    //待客户确认
    public function wait_affirm(){
        $Auth = new \Think\Auth;
        $uid = $_SESSION['userinfo']['id'];
        $res = $Auth->check(MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME,$uid);
        if($res){
            $info = M('details a,manage_item b')
                ->field('a.id,b.name')
                ->where("a.item_id = b.id and a.result = 0")
                ->select();

            $this->assign(array(
                'info'      => $info,
                'display'   => 'Remark',
                'current'   => 'wait_affirm',
            ));
            $this->display();
        }else{
            echo "<script>alert('权限不足！');window.history.back();</script>";
        }
    }
    //帮助客户点击确认
    public function help_hit($id){
        $data['result'] = 1;
        $res = M('details')->where("id = $id")->save($data);
        if($res){
            echo "<script>alert('操作成功！');window.location='/admin.php/Remark/wait_affirm';</script>";
        }else{
            echo "<script>alert('操作失败！');window.history.back(); </script>";
        }
    }
    //三天自动确认(并推送消息告知客户)
    public function auto_hit(){
        $info = M('details')->where("result = 0")->select();
        $time = time();
        foreach ($info as $k => $v){
            $add_time = $v['add_time'];
            $cz = ($time - $add_time) / 86400;
            if($cz > 3){
                //自动帮助点击确认
                $data['result'] = 1;
                $id = $v['id'];
                $res = M("details")->where("id = $id")->save($data);
                if($res){
                    //推送消息:已经自动点击确认
                    $item_id = $v['item_id'];
                    $item = M('item')->where("id = $item_id")->find();
                    $openid = $item['link_openid'];
                    Vendor('WXAPI.JSSDK');
                    $jssdk = new \JSSDK("","");
                    $AccessToken = $jssdk->getAccessToken();
                    //echo $this->access_token;exit;
                    //拼接链接(通知类消息无需链接)
                    $link = $_SERVER['SERVER_NAME'].'/admin.php/Love/item_auto';
                    //模板消息
                    $template=array(
                            'touser'=>$openid,//用户openID
                            'template_id'=>"XQGZqmk1rdHw4XPQsy4HQXM6xPQbUwHYeGzF2GCl3LM",//模板ID
                            'url'=>$link,//点击卡片链接地址 
                            'topcolor'=>"#7B68EE",//消息卡片顶部颜色
                            'data'=>array(//模板字段列表
                                'first'=>array('value'=>urlencode("项目完结已自动确认！"),'color'=>"#FF0000"),
                                'keyword1'=>array('value'=>urlencode($item['name']),'color'=>'#FF0000'),
                                'keyword2'=>array('value'=>urlencode(date("Y-m-d H:i:s")),'color'=>'#FF0000'),
                                'remark'=>array('value'=>urlencode('点击查看详情'),'color'=>'#FF0000'), )
                            );
                    $json_template=json_encode($template);
                    //echo $json_template;
                    //echo $this->access_token;
                    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$AccessToken;
                    $res=$jssdk->httpGet($url,urldecode($json_template));
                    $res=json_decode($res,true);
                    //var_dump($res);exit;
                    if ($res['errcode']==0){
                        //echo '发送成功';
                        //添加日志
                        $log['title'] = '自动确认项目完结：'.$item['name'];
                        $log['username'] = '系统自动执行';
                        $log['ip'] = '0.0.0.0';
                        M('log')->add($log);
                    }else{
                       //echo '发送失败'; 
                    }
                }
            }
        }
    }
}