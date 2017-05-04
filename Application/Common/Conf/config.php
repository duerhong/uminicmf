<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2016 http://www.uminicmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 杜二红 <1186969412@qq.com>
// +----------------------------------------------------------------------
// | Created by: 2015-10-11 00:00:00
// +----------------------------------------------------------------------


//----------------------------------
// 基础配置文件
//----------------------------------
return array(
	//'配置项'=>'配置值'
	// 'LAYOUT_ON'=>true,


	'URL_MODEL' => '2',


	'VIEW_PATH'=>'./Public/',
	'STATIC_ROOT'=>'../Public/',
	'DEFAULT_MODULE'=>'Auth',

	// 入口路径
	'URL_INDEX'=>'index.php?s=',
	'TMPL_PARSE_STRING'  =>array(
		'__SHARE_VIEW__'=>'./Application/Public/View',
	),
	'DEFAULT_THEME' 	=> 'default',
	'SESSION_AUTO_START' => true,

	'LOAD_EXT_CONFIG' => 'db,data', //配置列表 各类数据 文件
);
