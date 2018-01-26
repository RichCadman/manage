<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;overflow-y:auto;">
<head>
    <title>添加权限组</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="stylesheet" href="/Pub/admin/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="/Pub/admin/css/bootstrap-responsive.min.css"/>
    <link rel="stylesheet" href="/Pub/admin/css/uniform.css"/>
    <link rel="stylesheet" href="/Pub/admin/css/select2.css"/>
    <link rel="stylesheet" href="/Pub/admin/css/matrix-style.css"/>
    <link rel="stylesheet" href="/Pub/admin/css/matrix-media.css"/>
    <link href="/Pub/admin/font-awesome/css/font-awesome.css" rel="stylesheet"/>
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    <style>
        .table td {
            text-align: center;
        }
    </style>
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

<div id="content">
    <div id="content-header">
        <div id="breadcrumb"><a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a
                href="#" class="tip-bottom">权限组管理</a><a href="#" class="current">权限组总览</a></div>
        <h1 style="float:left">添加权限组</h1>
    </div>
    <div class="container-fluid">
        <hr>
        <div class="row-fluid">
            <div class="span12">
                <div class="widget-box">
                    <!--<div class="widget-title"><span class="icon"> <i class="icon-th"></i> </span>-->
                        <!--<h5>Static table with checkboxes in box with padding</h5>-->
                        <!--<span class="label label-info">Featured</span>-->
                    <!--</div>-->
                    <form action="/admin.php/Auth/add_group_do" method="post">
                        <div class="control-group" style="margin-left: 20px;margin-top: 20px;">
                            <!--<label class="control-label">员工名称</label>-->
                            <div class="controls">
                                <span style="text-align: center;margin-right: 5px;font-size: 16px;">用户组名：</span>
                                <input type="text" class="span3" name="title" />
                            </div>
                        </div>
                        <div class="widget-content ">
                            <table style="line-height: 40px">
                                <thead>
                                <!--<tr>-->
                                <!--<th>导航</th>-->
                                <!--<th>Column name</th>-->
                                <!--<th>Column name</th>-->
                                <!--<th>Column name</th>-->
                                <!--<th>Column name</th>-->
                                <!--</tr>-->
                                </thead>
                                <tbody>
                                <?php if(is_array($info)): foreach($info as $k=>$v): ?><tr>
                                        <?php if($v['p_id'] == 0): ?><td><input type="checkbox" class="xunz_box" data-id="<?php echo ($k); ?>" data-select="0"/><?php echo ($v["title"]); ?>：&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;</td>
                                            <?php if(is_array($info)): foreach($info as $key=>$vv): if($vv['p_id'] == $v['id']): ?><td><input type="checkbox"  class="input_box" data-id="<?php echo ($k); ?>" name="rules[]" value="<?php echo ($vv["id"]); ?>"/><?php echo ($vv["title"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><?php endif; endforeach; endif; endif; ?>
                                    </tr><?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-success" style="margin-left: 20px;margin-top: 20px;margin-bottom: 20px;">提交</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Footer-part-->
<div class="row-fluid">
  <div id="footer" class="span12"> 2017 &copy; 员工项目管理系统 --<a href="http://www.dingzankj.com">鼎赞网络科技</a> </div>
</div>
<!--end-Footer-part-->
<script src="/Pub/admin/js/jquery.min.js"></script>
<script src="/Pub/admin/js/jquery.ui.custom.js"></script>
<script src="/Pub/admin/js/bootstrap.min.js"></script>
<script src="/Pub/admin/js/jquery.uniform.js"></script>
<script src="/Pub/admin/js/select2.min.js"></script>
<script src="/Pub/admin/js/jquery.dataTables.min.js"></script>
<script src="/Pub/admin/js/matrix.js"></script>
<script src="/Pub/admin/js/matrix.tables.js"></script>

<script type="text/javascript">
    // 删除数据方法
    function del(obj, id) {
        // 发送sql语句
        if (confirm("确定放入回收站吗？")) {
            $.post('/admin.php/Users/users_del', {id: id}, function (data) {
                // 判断是否成功
                if (data.statu == 200) {
                    $(obj).parent().parent().remove();
                    alert(data.info);
                } else {
                    alert(data.info);
                }
            });
        }
    }
</script>
<script>
    $('.xunz_box').click(function () {
        var select = $(this).data('id');
        var ifSelect = parseInt($(this).data('select'));
        if(!ifSelect){
            $(this).parents('tr').find('.input_box').prop('checked',true);
            $(this).parents('tr').find('.input_box').parent().addClass('checked');
            $(this).data('select',1)
        }
        else {
            $(this).parents('tr').find('.input_box').prop('checked',false);
            $(this).parents('tr').find('.input_box').parent().removeClass('checked');
            $(this).data('select',0)
        }
    });
</script>
</body>
</html>