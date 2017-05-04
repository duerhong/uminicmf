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
// 数据
//----------------------------------
return array(
	'FORM_PLUGS'=>array(
			array(
			 'group_title'=>'常用插件',
	 		 'group_list'=>array(
	 			 'form_text' => '输入框',
	 			 'form_html'=>'纯html',
	 			 'form_select'=>'下拉列表',
	 			 'form_textarea' => '文本域',
	 			 'form_hidden' => '隐藏域',
	 			 'form_bool'=>'布尔型',
	 		 	),
			),

			array(
			 'group_title'=>'高级插件',
	 		 'group_list'=>array(
	 			 'form_foreign_select'=>'外键（下拉）',
	 			 'form_uploads'=>'单图上传插件',
	 			 'form_m_uploads'=>'多图上传插件',
	 			 'form_hidden' => '隐藏域',
	 			 'form_date' => 'date选择器',
				 'form_datetimes' => 'datetime选择器',
				 'form_mini_kindeditor'=>'kindeditor简单版',
				 'form_all_kindeditor'=>'kindeditor完整版',
	 		 	),
			),

			array(
			 'group_title'=>'其他插件',
	 		 'group_list'=>array(
	 			 'form_tree'=>'无限极分类',
				 'form_menutree'=>'树形菜单',
				 'form_sj_regiontree'=>'省级',
				 'form_regiontree'=>'树形地区（县级）',
				 'form_tables'=>'数据表',
	 		 	),
			),
		),

		'TABLE_LIST'=>array(
			'message'=>'信息',
			'article'=>'文章',
			'auth_group'=>'权限组',
			'databackup'=>'数据备份',
			'database'=>'数据',
			'fields'=>'字段',
			'field_group'=>'字段分组',
			'field_model'=>'字段模型',
			'icon'=>'图标管理',
			'members'=>'会员',
			'menu'=>'菜单',
			'model'=>'模型',
			'template'=>'模板',
			'user'=>'用户',
		),
);
