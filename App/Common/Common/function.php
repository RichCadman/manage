<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 阁下贵姓 <417626953@qq.com>
// +----------------------------------------------------------------------

/**
 *  自定义系统函数库
 */


/**
 * @Title: liu_percent
 * @Description: 求百分比
 * @param string $molecule分子
 * @param string $denominator分母
 * @return string
 * @author 阁下贵姓
 * @date 2017-12-22
 */

function liu_percent($molecule,$denominator){
	$percent = round(($molecule / $denominator)*100);
	$percent = $percent.'%';
	return $percent;
}