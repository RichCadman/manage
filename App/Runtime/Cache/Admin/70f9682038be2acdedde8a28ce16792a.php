<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;overflow-y:auto;">
<head>
    <title>添加规则</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/Pub/admin/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/Pub/admin/css/bootstrap-responsive.min.css" />
    <link rel="stylesheet" href="/Pub/admin/css/colorpicker.css" />
    <link rel="stylesheet" href="/Pub/admin/css/datepicker.css" />
    <link rel="stylesheet" href="/Pub/admin/css/uniform.css" />
    <link rel="stylesheet" href="/Pub/admin/css/select2.css" />
    <link rel="stylesheet" href="/Pub/admin/css/matrix-style.css" />
    <link rel="stylesheet" href="/Pub/admin/css/matrix-media.css" />
    <link rel="stylesheet" href="/Pub/admin/css/bootstrap-wysihtml5.css" />
    <link href="/Pub/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/Lib/kindeditor/themes/default/default.css" />
    <link rel="stylesheet" href="/Lib/kindeditor/plugins/code/prettify.css" />
    <script charset="utf-8" src="/Lib/kindeditor/kindeditor.js"></script>
    <script charset="utf-8" src="/Lib/kindeditor/lang/zh_CN.js"></script>
    <script charset="utf-8" src="/Lib/kindeditor/plugins/code/prettify.js"></script>
    <script>
        KindEditor.ready(function(K) {
            var editor1 = K.create('textarea[name="describe"]', {
                width: "91.3%", //编辑器的宽度
                height: "430px", //编辑器的高度
                filterMode: false, //不会过滤HTML代码
                resizeType: 0,
                allowPreviewEmoticons: true,
                allowImageUpload: true,
                cssPath: '/Lib/kindeditor/plugins/code/prettify.css',
                uploadJson: '/Lib/kindeditor/php/upload_json.php',
                fileManagerJson: '/Lib/kindeditor/php/file_manager_json.php',
                allowFileManager: true,
                afterCreate: function() {
                    var self = this;
                    K.ctrl(document, 13, function() {
                        self.sync();
                        K('form[name=news]')[0].submit();
                    });
                    K.ctrl(self.edit.doc, 13, function() {
                        self.sync();
                        K('form[name=news]')[0].submit();
                    });
                }
            });
            prettyPrint();
        });


    </script>
</head>
<body>

<style>
.over{
  width:100%;
  overflow:auto;
  /* white-space:pre-wrap;不换行 */
  /* overflow-x:hidden;内容超出宽度时隐藏超出部分的内容  */
}
</style>

<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<!--Header-part-->
<div id="header">
  <h1><a href="dashboard.html">员工项目管理系统</a></h1>
</div>
<!--close-Header-part--> 

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
  <ul class="nav">
    <li  class="dropdown" id="profile-messages" ><a title="" href="#" data-toggle="dropdown" data-target="#profile-messages" class="dropdown-toggle"><i class="icon icon-user"></i>  <span class="text">Welcome <?php echo ($_SESSION['userinfo']['username']); ?></span><b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a href="/admin.php/My/my.html"><i class="icon-user"></i> 个人中心</a></li>
        <li class="divider"></li>
        <!-- <li><a href="#"><i class="icon-check"></i> My Tasks</a></li> -->
        <li class="divider"></li>
        <li><a href="/admin.php/Login/login_out.html"><i class="icon-key"></i> 登出</a></li>
      </ul>
    </li>
    <!-- <li class="dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">项目</span> <span class="label label-important">5</span> <b class="caret"></b></a>
      <ul class="dropdown-menu">
        <li><a class="sAdd" title="" href="#"><i class="icon-plus"></i> new message</a></li>
        <li class="divider"></li>
        <li><a class="sInbox" title="" href="#"><i class="icon-envelope"></i> inbox</a></li>
        <li class="divider"></li>
        <li><a class="sOutbox" title="" href="#"><i class="icon-arrow-up"></i> outbox</a></li>
        <li class="divider"></li>
        <li><a class="sTrash" title="" href="#"><i class="icon-trash"></i> trash</a></li>
      </ul>
    </li> -->
    <!-- <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li> -->
    <li class=""><a title="" href="/admin.php/Login/login_out.html"><i class="icon icon-share-alt"></i> <span class="text">登出</span></a></li>
  </ul>
</div>

<!--start-top-serch-->
<div id="search">
  <?php if($_SESSION['userinfo']['position_id']==1): ?><a href="javascript:void();" onclick="cache()" class="btn btn-info" style="margin-bottom:9px;margin-right:15px;">清除缓存</a><?php endif; ?>
  <!-- <input type="text" placeholder="Search here..."/>
  <button type="submit" class="tip-bottom" title="Search"><i class="icon-search icon-white"></i></button> -->
</div>
<!--close-top-serch--> 

<script>
  function cache(){
    if(confirm("确定清除吗？")){
      window.location="/admin.php/Index/clear_cache";
    }
  }
</script>
<!-- 定时弹框提醒项目进度 -->
<script type="text/javascript">
   function ajax(){
        var url="/admin.php/Item/remind";
        $(function(){
            $.post(url,function(data){
                //alert(data);
                /*if(data.statu == 200){
                  alert(data.msg);
                }*/
            })
        });
        
    }
    setInterval("ajax()",10000);//每隔10秒检查一次
  </script>
  <!-- 定时查看推送消息，超过三天自动确认 -->
<script type="text/javascript">
   function auto(){
        var url="/admin.php/Remark/auto_hit";
        $(function(){
            $.post(url,function(data){
                //alert(data);
                /*if(data.statu == 200){
                  alert(data.msg);
                }*/
            })
        });
        
    }
    setInterval("auto()",10000);//每隔10秒检查一次
  </script>
<!--sidebar-menu-->
<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> 菜单栏</a>
    <ul>
        <li class="<?php echo ($current=='index'?'active':''); ?>"><a href="/admin.php/Index/index.html"><i class="icon icon-dashboard"></i> <span>仪表盘</span></a> </li>
        <?php if(is_array($menu)): foreach($menu as $key=>$v): ?><!--判断是否有权限显示-->
            <?php if(in_array($v['id'],$menu_array)): ?><li class="submenu <?php echo ($current=='item'?'active':''); ?>">
                    <?php if($v['p_id'] == 0): ?><a href="#"><i class="icon <?php echo ($v["icon"]); ?>"></i> <span><?php echo ($v["title"]); ?></span></a>
                        <ul style="display:<?php echo ($display == $v['controller'] ? 'block' : 'none'); ?>">
                            <?php if(is_array($menu)): foreach($menu as $key=>$vv): if($vv['p_id'] == $v['id']): ?><!--判断是否有权限显示-->
                                    <?php if(in_array($vv['id'],$rule_array)): ?><li class="<?php echo ($current == $vv['function'] ? 'active' : ''); ?>"><a href="/admin.php/<?php echo ($vv["controller"]); ?>/<?php echo ($vv["function"]); ?>.html"><?php echo ($vv["title"]); ?></a></li><?php endif; ?>
                                    <!--判断是否有权限显示--><?php endif; endforeach; endif; ?>
                        </ul><?php endif; ?>
                </li><?php endif; ?>
            <!--判断是否有权限显示--><?php endforeach; endif; ?>
        <!-- <li class="content"> <span>Monthly Bandwidth Transfer</span>
          <div class="progress progress-mini progress-danger active progress-striped">
            <div style="width: 77%;" class="bar"></div>
          </div>
          <span class="percent">77%</span>
          <div class="stat">21419.94 / 14000 MB</div>
        </li>

        <li class="content"> <span>Disk Space Usage</span>
          <div class="progress progress-mini active progress-striped">
            <div style="width: 87%;" class="bar"></div>
          </div>
          <span class="percent">87%</span>
          <div class="stat">604.44 / 4000 MB</div>
        </li> -->

    </ul>
</div>
<!--close-left-menu-stats-sidebar-->

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="tip-bottom">规则管理</a> <a href="#" class="current">添加规则</a> </div>
        <h1>添加规则</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span6">


                <div class="widget-box">
                    <div class="widget-title"> <span class="icon"> <i class="icon-align-justify"></i> </span>
                    </div>
                    <div class="widget-content nopadding">
                        <form action="/admin.php/Auth/add_rule_do" method="post" class="form-horizontal" enctype="multipart/form-data" >

                            <div class="control-group">
                                <label class="control-label">规则名称</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="title" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">规则</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="name" />
                                </div>
                            </div>

                            <div class="control-group ">
                                <label class="control-label span6">父级规则</label>
                                <div class="controls">
                                    <select name="p_id">
                                        <option value="0">--顶级规则--</option>
                                        <?php if(is_array($info)): foreach($info as $key=>$v): if($v['p_id'] == 0): ?><option value="<?php echo ($v["id"]); ?>">&nbsp;&nbsp;|--<?php echo ($v["title"]); ?></option>
                                                <?php if(is_array($info)): foreach($info as $key=>$vv): if($vv['p_id'] == $v['id']): ?><option value="<?php echo ($vv["id"]); ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|--<?php echo ($vv["title"]); ?></option><?php endif; endforeach; endif; endif; endforeach; endif; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">导航</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="nav" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">是否显示</label>
                                <div class="controls">
                                    <label>
                                        <input type="radio" name="is_visible" value="1"/>
                                        显示</label>
                                    <label>
                                        <input type="radio" name="is_visible" checked value="2" />
                                        隐藏</label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">菜单图标</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="icon" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">控制器标识</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="controller" />
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">方法标识</label>
                                <div class="controls">
                                    <input type="text" class="span6" name="function" />
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-success">添加</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div></div>
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2017 &copy; 员工项目管理系统 --<a href="http://www.dingzankj.com">鼎赞网络科技</a> </div>
</div>
<!--end-Footer-part-->
<script src="/Pub/admin/js/jquery.min.js"></script>
<script src="/Pub/admin/js/jquery.ui.custom.js"></script>
<script src="/Pub/admin/js/bootstrap.min.js"></script>
<script src="/Pub/admin/js/bootstrap-colorpicker.js"></script>
<script src="/Pub/admin/js/bootstrap-datepicker.js"></script>
<script src="/Pub/admin/js/jquery.toggle.buttons.html"></script>
<script src="/Pub/admin/js/masked.js"></script>
<script src="/Pub/admin/js/jquery.uniform.js"></script>
<script src="/Pub/admin/js/select2.min.js"></script>
<script src="/Pub/admin/js/matrix.js"></script>
<script src="/Pub/admin/js/matrix.form_common.js"></script>
<script src="/Pub/admin/js/wysihtml5-0.3.0.js"></script>
<script src="/Pub/admin/js/jquery.peity.min.js"></script>
<script src="/Pub/admin/js/bootstrap-wysihtml5.js"></script>
<script>
    /*$('.textarea_editor').wysihtml5();*/
</script>
</body>
</html>