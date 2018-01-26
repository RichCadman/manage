<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;overflow-y:auto;">
<head>
<title>员工项目管理系统</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="stylesheet" href="/Pub/admin/css/bootstrap.min.css" />
<link rel="stylesheet" href="/Pub/admin/css/bootstrap-responsive.min.css" />
<link rel="stylesheet" href="/Pub/admin/css/matrix-style.css" />
<link rel="stylesheet" href="/Pub/admin/css/matrix-media.css" />
<link href="/Pub/admin/font-awesome/css/font-awesome.css" rel="stylesheet" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
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
    <div id="breadcrumb"> <a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>  
    </div>
  </div>
  <div class="container-fluid"><hr>
    <div class="quick-actions_homepage">
      <ul class="quick-actions">
        <?php if($_SESSION['userinfo']['position_id'] == 1): ?><li class="bg_lb"> <a href="/admin.php/Index/index.html"> <i class="icon-dashboard"></i> <span class="label label-important"></span> 仪表盘 </a> </li>
          <li class="bg_lo"> <a href="/admin.php/Item/item_index.html"> <i class="icon-th-list"></i> 项目总览</a> </li>
          <li class="bg_ly"> <a href="/admin.php/Users/users_index.html"> <i class="icon-group"></i> 员工总览</a> </li>
          <li class="bg_lg"> <a href="/admin.php/Log/log.html"> <i class="icon-time"></i> 日志总览</a> </li>
          <li class="bg_ls"> <a href="/admin.php/Performance/performance.html"> <i class="icon-bar-chart"></i> 绩效总览</a> </li><?php endif; ?>
        <?php if($_SESSION['userinfo']['position_id'] != 1): ?><li class="bg_lb span4"> <a href="/admin.php/Index/index.html"> <i class="icon-dashboard"></i> <span class="label label-important"></span> 仪表盘 </a> </li>
          <li class="bg_lo span4"> <a href="/admin.php/Item/my_item.html"> <i class="icon-th-list"></i> 我的项目</a> </li><?php endif; ?>
        <?php if($_SESSION['userinfo']['position_id'] == 2): ?><li class="bg_lg span4"> <a href="/admin.php/Index/upload_img.html"> <i class="icon-picture"></i> 上传效果图</a> </li><?php endif; ?>
        <li class="bg_ly span4"> <a href="/admin.php/My/my.html"> <i class="icon-user"></i><span class="label label-success"></span> 个人中心 </a> </li>
        <!-- <li class="bg_lo"> <a href="tables.html"> <i class="icon-th"></i> Tables</a> </li>
        <li class="bg_ls"> <a href="grid.html"> <i class="icon-fullscreen"></i> Full width</a> </li>
        <li class="bg_lo span3"> <a href="form-common.html"> <i class="icon-th-list"></i> Forms</a> </li>
        <li class="bg_ls"> <a href="buttons.html"> <i class="icon-tint"></i> Buttons</a> </li>
        <li class="bg_lb"> <a href="interface.html"> <i class="icon-pencil"></i>Elements</a> </li>
        <li class="bg_lg"> <a href="calendar.html"> <i class="icon-calendar"></i> Calendar</a> </li>
        <li class="bg_lr"> <a href="error404.html"> <i class="icon-info-sign"></i> Error</a> </li> -->
      </ul>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-briefcase"></i> </span>
            <h5 >服务器信息</h5>
          </div>
          <div class="widget-content">
            <div class="row-fluid">
              <div class="span6">
                <div class="over"  >
                <table class="" style="overflow:hidden">
                  <tbody>
                    <?php if(is_array($info)): foreach($info as $k=>$v): ?><tr>
                      <td><?php echo ($k); ?>：</td>
                      <td><?php echo ($v); ?></td>
                    </tr><?php endforeach; endif; ?>
                  </tbody>
                </table>
                </div>
              </div>
              <div class="span6">
                <!-- <div class="chart"></div> -->
              <!-- <div class="widget-box"> -->
                <!-- <div class="widget-title"> <span class="icon"><i class="icon-ok"></i></span>
                  <h5>项目进度</h5>
                  <div class="control-group ">
                    <label class="control-label span6">设计</label>
                    <div class="controls" >
                      <select name="user_id_ui">
                        <option value="0">请选择</option>
                        <?php if(is_array($UI)): foreach($UI as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["username"]); ?></option><?php endforeach; endif; ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="widget-content">
                  <ul class="unstyled">
                    <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 设计 <span class="pull-right strong">567</span>
                      <div class="progress progress-striped ">
                        <div style="width: 81%;" class="bar"></div>
                      </div>
                    </li>
                    <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 前端 <span class="pull-right strong">507</span>
                      <div class="progress progress-success progress-striped ">
                        <div style="width: 72%;" class="bar"></div>
                      </div>
                    </li>
                    <li> <span class="icon24 icomoon-icon-arrow-down-2 red"></span> 程序 <span class="pull-right strong">457</span>
                      <div class="progress progress-warning progress-striped ">
                        <div style="width: 53%;" class="bar"></div>
                      </div>
                    </li>
                    <li> <span class="icon24 icomoon-icon-arrow-up-2 green"></span> 总计 <span class="pull-right strong">8</span>
                      <div class="progress progress-danger progress-striped ">
                        <div style="width: 3%;" class="bar"></div>
                      </div>
                    </li>
                  </ul>
                </div> -->
             <!--  </div> -->
                 <table class="table table-bordered table-invoice">
                  <tbody>
                    <tr>
                    <tr>
                      <td class="width30">公司名称：</td>
                      <td class="width70"><strong>鼎赞网络科技</strong></td>
                    </tr>
                    <tr>
                      <td>经理：</td>
                      <td><strong>韩经理</strong></td>
                    </tr>
                    <tr>
                      <td>座机：</td>
                      <td><strong><a href="tel:0531-68852177">0531-68852177</a></strong></td>
                    </tr>
                    <tr>
                      <td>手机：</td>
                      <td><strong><a href="tel:15610124999">15610124999</a></strong></td>
                    </tr>
                    <tr>
                      <td>邮箱：</td>
                      <td><strong>hxh@dingzankj.com</strong></td>
                    </tr>
                  <td class="width30">地址：</td>
                    <td class="width70"><strong>山东省济南市天桥区堤口路68号E3座613</strong> <br>
                      <!-- 堤口路68号E3座613 <br>
                      NYNC 3654 <br>
                      Contact No: 123 456-7890 <br>
                      Email: youremail@companyname.com </td> -->
                  </tr>
                    </tbody>
                  
                </table> 
              </div>
            </div>
            <div class="row-fluid">
              <div class="span12">
                
                <!-- <table class="table table-bordered table-invoice-full">
                  <thead>
                    <tr>
                      <th class="head0">Type</th>
                      <th class="head1">Desc</th>
                      <th class="head0 right">Qty</th>
                      <th class="head1 right">Price</th>
                      <th class="head0 right">Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Firefox</td>
                      <td>Ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae</td>
                      <td class="right">2</td>
                      <td class="right">$150</td>
                      <td class="right"><strong>$300</strong></td>
                    </tr>
                    <tr>
                      <td>Chrome Plugin</td>
                      <td>Tro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt u eos et accusamus et iusto odio dignissimos ducimus  deleniti atque</td>
                      <td class="right">1</td>
                      <td class="right">$1,200</td>
                      <td class="right"><strong>$1,2000</strong></td>
                    </tr>
                    <tr>
                      <td>Safari App</td>
                      <td>Rro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt u expedita distinctio</td>
                      <td class="right">2</td>
                      <td class="right">$850</td>
                      <td class="right"><strong>$1,700</strong></td>
                    </tr>
                    <tr>
                      <td>Opera App</td>
                      <td>Orro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut</td>
                      <td class="right">3</td>
                      <td class="right">$850</td>
                      <td class="right"><strong>$2,550</strong></td>
                    </tr>
                    <tr>
                      <td>Netscape Template</td>
                      <td>Vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque</td>
                      <td class="right">5</td>
                      <td class="right">$50</td>
                      <td class="right"><strong>$250</strong></td>
                    </tr>
                  </tbody>
                </table> -->
                <!-- <table class="table table-bordered table-invoice-full">
                  <tbody>
                    <tr>
                      <td class="msg-invoice" width="85%"><h4>Payment method: </h4>
                        <a href="#" class="tip-bottom" title="Wire Transfer">Wire transfer</a> |  <a href="#" class="tip-bottom" title="Bank account">Bank account #</a> |  <a href="#" class="tip-bottom" title="SWIFT code">SWIFT code </a>|  <a href="#" class="tip-bottom" title="IBAN Billing address">IBAN Billing address </a></td>
                      <td class="right"><strong>Subtotal</strong> <br>
                        <strong>Tax (5%)</strong> <br>
                        <strong>Discount</strong></td>
                      <td class="right"><strong>$7,000 <br>
                        $600 <br>
                        $50</strong></td>
                    </tr>
                  </tbody>
                </table> -->
                <!-- <div class="pull-right">
                  <h4><span>Amount Due:</span> $7,650.00</h4>
                  <br>
                  <a class="btn btn-primary btn-large pull-right" href="#">Pay Invoice</a> 
                </div> -->
              </div>
            </div>
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
<script src="/Pub/admin/js/matrix.js"></script>
</body>
</html>