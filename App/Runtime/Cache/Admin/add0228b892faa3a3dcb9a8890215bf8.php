<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;overflow-y:auto;">
<head>
<title>员工项目管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Pub/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Pub/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Pub/admin/css/uniform.css" />
<link rel="stylesheet" href="/Pub/admin/css/select2.css" />
<link rel="stylesheet" href="/Pub/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Pub/admin/css/matrix-media.css" />
<link href="/Pub/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
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
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> 首页</a> <a href="#" class="current">我的项目</a> </div>
    <h1 style="float:left">我的项目</h1>
  </div>
  <div class="container-fluid">
    <hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>项目名称</th>
                  <th>工期</th>
                  <th>开始</th>
                  <th>结束</th>
                  <th>工期比</th>
                  <th>更改记录</th>
                  <th>工作者</th>
                </tr>
              </thead>
              <tbody>
                <?php if(is_array($my_item)): foreach($my_item as $k=>$v): ?><tr class="gradeX">
                  <td><?php echo ($k+1); ?></td>
                  <td><?php echo ($v["name"]); ?></td>
                  <td><?php echo ($v["duration"]); ?></td>
                  <?php if($v['start_time']): ?><td><a href="/admin.php/Item/item_query/item_id/<?php echo ($v["item_id"]); ?>" class="btn btn-info">查看进度</a></td>
                  <?php else: ?>
                    <td><a href="javascript:viod();" onclick="start(<?php echo ($v["id"]); ?>,<?php echo ($v["item_id"]); ?>)" class="btn btn-success">点击开始</a></td><?php endif; ?>

                  <?php if($v['end_time']): ?><td><?php echo date("Y-m-d H:i",$v['start_time']);?>--<?php echo date("Y-m-d H:i",$v['end_time']);?></td>
                  <?php else: ?>
                    <?php if($v['start_time']): ?><td><a href="javascript:viod();" onclick="ok(<?php echo ($v["id"]); ?>)" class="btn btn-danger">点击完成</a></td>
                    <?php else: ?>
                    <td><a href="" class="btn btn-info">我是个按钮</a></td><?php endif; endif; ?>

                  <?php if($v['ratio']): ?><td><?php echo ($v["ratio"]); ?></td>
                  <?php else: ?>
                    <td>暂无</td><?php endif; ?>
                  <td><a href="/admin.php/Remark/item_record/item_id/<?php echo ($v["item_id"]); ?>" class="btn btn-info">修改记录</a></td>
                  <td><?php echo ($v["username"]); ?></td>
                </tr><?php endforeach; endif; ?>
              </tbody>
            </table>
          </div>
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
  function del(obj,id){
    // 发送sql语句
    if(confirm("确定删除吗？")){
      $.post('/admin.php/Item/item_del',{id:id},function(data){
        // 判断是否成功
        if (data.statu==200) {
          $(obj).parent().parent().remove();
          alert(data.info);
        }else{
          alert(data.info);
        }
      });
    }
  }
  //点击完成
  function ok(id){
    if(confirm("确定完成吗？")){
      window.location="/admin.php/Item/my_item_ok/id/"+id;
    }
  }
  //点击开始
  function start(id,item_id){
    if(confirm("确定开始吗？")){
      window.location="/admin.php/Item/my_item_start/id/"+ id + "/item_id/" + item_id;
    }
  }
</script>
</body>
</html>