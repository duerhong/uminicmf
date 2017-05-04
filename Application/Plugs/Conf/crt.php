<?php
return array(
	// 1.和分类绑定：侧栏分类绑定  侧栏分类必须有个所在控制器字段
	// 2.可以排序，可以是否显示
	'CTR_LIST'=>array(
				0=>array(
					'title'=>'模型管理',
					'crt_name'=>'Manage',
					'is_show'=>true,
					'url'=>'?s=Manage/Index/',
					),
				1=>array(
					'title'=>'字段管理',
					'crt_name'=>'Customer',
					'url'=>'',
					),
				
				2=>array(
					'title'=>'基础配置',
					'crt_name'=>'Home',
					'url'=>'',
					),

				3=>array(
					'title'=>'数据备份',
					'crt_name'=>'Home',
					'url'=>'',
					),
			), 
)
?>