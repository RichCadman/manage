<?php
// +----------------------------------------------------------------------
// | ThinkPHP3.2 [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 阁下贵姓 <417626953@qq.com>
// +----------------------------------------------------------------------

// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//定义编码方式
header("content-type:text/html;charset=utf-8");
//绑定默认模块
define("BIND_MODULE",'Home');
//定义缓存目录
define("APP_PATH",'App/');
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define("APP_DEBUG",true);
// 引入ThinkPHP入口文件
include_once './Lib/ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
