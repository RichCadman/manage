<?php
return array(
	//'配置项'=>'配置值'
	/* 数据库设置 */
	'DB_TYPE'               =>  'mysqli',     // 数据库类型
	'DB_HOST'               =>  '127.0.0.21', // 服务器地址
	'DB_NAME'               =>  'manage',          // 数据库名
	'DB_USER'               =>  'root',      // 用户名
	'DB_PWD'                =>  'root',          // 密码
	'DB_PORT'               =>  '3306',        // 端口
	'DB_PREFIX'             =>  'manage_',    // 数据库表前缀

	
	/*'URL_ROUTER_ON' => TRUE, //开启路由
    'URL_MODEL'          => '2',    //REWRITE模式是在PATHINFO模式的基础上添加了重写规则的支持，
                                    //可以去掉URL地址里面的入口文件index.php

    'URL_ROUTE_RULES' => array(//配置路由


    //按顺序匹配,先走index_index,再走index_index/:id\d,这样传id时会得不到意向的结果
    'index_index'           =>'Index/index',//访问http://127.0.0.6/index_index等同于http://127.0.0.6/index.php/Index/index
    'index_index/:id\d'     =>"Index/index",//访问http://127.0.0.6/index_index/15等同于http://127.0.0.6/index.php/Index/index/id/15
    //以上两种要想实现匹配id的情况必须把顺序换过来*/
    //第一种方法:
    //即:
    /*'index_index/:id\d'     =>"Index/index",
    'index_index'           =>'Index/index',*/

    //第二种方法:加上 $ 后就会进行绝对匹配,完全符合条件才进行
    /*'index_index$'           =>'Index/index',
    'index_index/:id\d$'     =>"Index/index",*/

    //第三种方法利用正则来匹配  详见手册  路由实例说明


    //分页匹配原则:
    /*'/^list\/(.*\d)_(.*\d)$/'  =>      'article/list?id=:1&p=:2',

    'index_read$'           =>'Index/read',
    'index_read/:id\d$'     =>"Index/read",
    'news_index$'           =>'News/index',
    'news_index/:id\d$'     =>"News/index",
    'news_news$'           =>'News/news',
    'news_news/:id\d$'     =>"News/news",
    
    ),  */
);