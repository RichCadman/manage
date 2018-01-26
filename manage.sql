/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : manage

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-12-26 09:49:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for manage_affirm
-- ----------------------------
DROP TABLE IF EXISTS `manage_affirm`;
CREATE TABLE `manage_affirm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL COMMENT '关联项目id',
  `link` varchar(255) DEFAULT NULL COMMENT 'UI设计生成链接',
  `result` tinyint(4) DEFAULT '0' COMMENT '设计图确认结果',
  `add_time` varchar(255) DEFAULT NULL COMMENT '录入时间1',
  `stage` varchar(255) DEFAULT NULL COMMENT '项目阶段',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_allot
-- ----------------------------
DROP TABLE IF EXISTS `manage_allot`;
CREATE TABLE `manage_allot` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目分配表',
  `user_id` int(11) DEFAULT NULL COMMENT '关联用户id',
  `item_id` int(255) DEFAULT NULL COMMENT '关联项目id',
  `duration` int(11) DEFAULT NULL COMMENT '分配的工期',
  `is_ok` tinyint(4) DEFAULT '0' COMMENT '是否已完成',
  `start_time` varchar(255) DEFAULT NULL COMMENT '开始时间',
  `end_time` varchar(255) DEFAULT NULL COMMENT '结束时间',
  `ratio` float(4,2) DEFAULT NULL COMMENT '工期比',
  `is_remind` tinyint(4) DEFAULT '0' COMMENT '项目到期是否提醒',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_authority
-- ----------------------------
DROP TABLE IF EXISTS `manage_authority`;
CREATE TABLE `manage_authority` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '权限表',
  `p_id` int(11) DEFAULT NULL COMMENT '父级id',
  `name` varchar(255) DEFAULT NULL COMMENT '权限名称',
  `URL` varchar(255) DEFAULT NULL COMMENT '权限路径',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_customer
-- ----------------------------
DROP TABLE IF EXISTS `manage_customer`;
CREATE TABLE `manage_customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `openid` int(11) DEFAULT NULL COMMENT 'openID',
  `title` varchar(255) DEFAULT NULL COMMENT '客户信息',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_details
-- ----------------------------
DROP TABLE IF EXISTS `manage_details`;
CREATE TABLE `manage_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text COMMENT '消息内容',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_img
-- ----------------------------
DROP TABLE IF EXISTS `manage_img`;
CREATE TABLE `manage_img` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '绑定用户id',
  `item_id` int(11) DEFAULT NULL COMMENT '关联项目id',
  `content` text COMMENT '效果图',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '录入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_item
-- ----------------------------
DROP TABLE IF EXISTS `manage_item`;
CREATE TABLE `manage_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '项目表',
  `name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `durations` int(255) DEFAULT NULL COMMENT '项目工期',
  `describe` text COMMENT '项目描述',
  `contract` varchar(255) DEFAULT NULL COMMENT '合同',
  `flow` varchar(255) DEFAULT NULL COMMENT '流程图',
  `is_allot` tinyint(4) DEFAULT '0' COMMENT '项目是否已经分配',
  `is_finish` tinyint(4) DEFAULT '0' COMMENT '是否完成',
  `link_openid` varchar(255) DEFAULT NULL COMMENT '关联客户openID',
  `is_block` tinyint(4) DEFAULT '0' COMMENT '项目是否冻结',
  `is_push` tinyint(4) DEFAULT '0' COMMENT '是否已推送',
  `block_time` varchar(255) DEFAULT NULL COMMENT '冻结时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_log
-- ----------------------------
DROP TABLE IF EXISTS `manage_log`;
CREATE TABLE `manage_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '日志表',
  `title` varchar(255) DEFAULT NULL COMMENT '日志标题',
  `username` varchar(255) DEFAULT NULL COMMENT '操作者',
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '操作时间',
  `ip` varchar(255) DEFAULT NULL COMMENT '用户IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_performance
-- ----------------------------
DROP TABLE IF EXISTS `manage_performance`;
CREATE TABLE `manage_performance` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '绩效表',
  `user_id` int(11) DEFAULT NULL COMMENT '关联用户id',
  `item_id` int(11) DEFAULT NULL COMMENT '关联项目id',
  `result` varchar(255) DEFAULT NULL COMMENT '绩效结果',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_position
-- ----------------------------
DROP TABLE IF EXISTS `manage_position`;
CREATE TABLE `manage_position` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT '职位名称',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '录入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_project
-- ----------------------------
DROP TABLE IF EXISTS `manage_project`;
CREATE TABLE `manage_project` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '上传项目表',
  `web_link` varchar(255) DEFAULT NULL COMMENT '项目链接',
  `web_name` varchar(255) DEFAULT NULL COMMENT '项目名称',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_record
-- ----------------------------
DROP TABLE IF EXISTS `manage_record`;
CREATE TABLE `manage_record` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) DEFAULT NULL COMMENT '绑定项目id',
  `user_id` int(11) DEFAULT NULL COMMENT '绑定用户id',
  `remark` text COMMENT '修改记录',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '录入时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_test
-- ----------------------------
DROP TABLE IF EXISTS `manage_test`;
CREATE TABLE `manage_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for manage_users
-- ----------------------------
DROP TABLE IF EXISTS `manage_users`;
CREATE TABLE `manage_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '员工表',
  `position_id` int(11) DEFAULT NULL COMMENT '关联职位id',
  `authority_id` int(11) DEFAULT NULL COMMENT '关联权限id',
  `username` varchar(255) DEFAULT '' COMMENT '员工名称',
  `phone` varchar(255) DEFAULT NULL COMMENT '登录账号',
  `password` varchar(255) DEFAULT NULL COMMENT '密码',
  `sex` tinyint(4) DEFAULT NULL COMMENT '性别',
  `birthday` varchar(255) DEFAULT NULL COMMENT '生日',
  `entry_time` varchar(255) DEFAULT NULL COMMENT '入职日期',
  `add_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间',
  `is_del` tinyint(4) DEFAULT '0' COMMENT '是否已删除',
  `is_sadmin` tinyint(4) DEFAULT '0' COMMENT '是否是超级管理员',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
