<?php
/**
*
除了通过启用模型自动构造表单可以创建表单外，也可以自定义构造表单
自定义构造方案：
1.可以自定义一个表单插件，比如 上传图片
2.可以自定义整个表单，包含form 标签
*/
// namesapce \Vender\Mylib;
namespace Vendor\Mylib;
class Form{
	function __construct($preData)
	{
		$this->preData=$preData;
		$this->cat_text_menu="";
	}

	// 根据model生成模型，启动表单自动构造
	//（参数:模型，分组）
	function model_form($group=false,$model_id=false){

		if (!$group) {//没有分组
			$plugs="";
			foreach ($this->preData as $key => $row) {
				if (isset($row["value"]) and $row["value"]!='') {
					$value=$row["value"];
				}
				else{
					$value=$row["defaults"];
				}

				if ($row['plug']=='form_hidden') {
					$plugs=$plugs.$this->form_hidden($row,str_replace('"',"'",$value));
				}
				else{

					$fun=$row['plug'];
					$plugs=$plugs.'
						<div class="form-group">
				        	<label  class="col-sm-2 control-label">'.$row["title"].'</label>
				        	<div class="col-sm-10 mywidth form-item">
				        	  '.$this->$fun($row,str_replace('"',"'",$value),'model').'
				       	 	</div>
				      	</div>
					';
				}

			}
		}
		else{
			// 根据model，查询分组。根据分组去循环
			$field_group_list=M('field_group')->where('model_id='.$model_id)->select();
			$myi=0;
			$plugs="";
			foreach ($field_group_list as $key_field => $row_field) {
				if ($myi==0) {
					$plugs=$plugs.'<div role="tabpanel" class="tab-pane active" id="tab_'.$row_field['id'].'">';
				}
				else{
					$plugs=$plugs.'<div role="tabpanel" class="tab-pane" id="tab_'.$row_field['id'].'">';
				}
				foreach ($this->preData as $key => $row) {

					if($row['field_group_id']==$row_field["id"]){
						if (isset($row["value"]) and $row["value"]!='') {
							$value=$row["value"];
						}
						else{
							$value=$row["defaults"];
						}

						if ($row['plug']=='form_hidden') {
							$plugs=$plugs.$this->form_hidden($row,str_replace('"',"'",$value));
						}
						else{
							$fun=$row['plug'];
							$plugs=$plugs.'
								<div class="form-group">
						        	<label  class="col-sm-2 control-label">'.$row["title"].'</label>
						        	<div class="col-sm-10 mywidth form-item">
						        	  '.$this->$fun($row,str_replace('"',"'",$value),'model').'
						       	 	</div>
						      	</div>
							';
						}
					}



				}


				$plugs=$plugs."</div>";
				$myi++;
			}

		}
		return $plugs;
	}



	function search_form($filter_list){

		$plugs="";
		foreach ($this->preData as $key => $row) {
			$row['help_text']="";
			if (in_array($row['field'],$filter_list)) {

				if (isset($row["value"]) and $row["value"]!='') {
					$value=$row["value"];
				}
				else{
					$value=I('post.'.$row['field']);
				}

				if ($row['plug']!='form_text' and $row['plug']!='form_item' and $row['plug']!='form_select' and $row['plug']!='form_foreign_select' and $row['plug']!='form_bool') {

					$fun=$row['plug'];
					$plugs=$plugs.'
					<div class="col-md-2  col-xs-12 col-sm-2">
						<div class="input-group my-btn-group">
							<span class="input-group-addon" id="sizing-addon3">'.$row["title"].'</span>
				        	  '.$this->form_text($row,str_replace('"',"'",$value),'search').'
				      	</div>
				     </div>
					';
				}
				else{
					$fun=$row['plug'];
					$plugs=$plugs.'
					<div class="col-md-2  col-xs-12 col-sm-2">
						<div class="input-group my-btn-group">
							<span class="input-group-addon" id="sizing-addon3">'.$row["title"].'</span>
				        	  '.$this->$fun($row,str_replace('"',"'",$value),'search').'
				      	</div>
				     </div>
					';
				}
			}

		}
		return $plugs;
	}

	// 用户自定义构造(参数：数据数组,分组)
	function user_form($fields,$group=false){
		if (!$group) {//没有分组
			$plugs="";
			foreach ($fields as $key => $row) {
				if ($row['plug']=='form_hidden') {
					$plugs=$plugs.$this->form_hidden($row,str_replace('"',"'",$row["value"]));
				}
				else{
					$fun=$row['plug'];
					$plugs=$plugs.'
						<div class="form-group">
				        	<label  class="col-sm-2 control-label">'.$row["title"].'</label>
				        	<div class="col-sm-10 mywidth">
				        	  '.$this->$fun($row,str_replace('"',"'",$row["value"]),'user').'
				       	 	</div>
				      	</div>
					';
				}

			}
		}
		return $plugs;
	}
	// 各类插件
	//隐藏域
	function form_hidden($field,$value='',$type="model"){
		return '
	      <input type="hidden"  id="'.$field['field'].'" name="'.$field['field'].'"  value="'.$value.'">
		';
	}

	// ==================纯html======================
	function form_html($field,$value='',$type='model'){
		return "<div class='form_html'>".$value."</div>";
	}

	function form_text($field,$value='',$type='model'){
		return '
	      <input type="text" class="form-control input-sm" id="'.$field['field'].'" name="'.$field['field'].'" placeholder="'.$field['title'].'" value="'.$value.'">'.'<span>'.$field['help_text'].'</span>';
	}

	function form_password($field,$value='',$type='model'){
		return '
	      <input type="password" class="form-control input-sm" id="'.$field['field'].'" name="'.$field['field'].'" placeholder="'.$field['title'].'" value="'.$value.'">
		';
	}



	function form_textarea($field,$value='',$type='model'){
		return '
	      <textarea class="form-control" rows="3" name="'.$field['field'].'">'.$value.'</textarea>
		';
	}

	// ======================日期类=================
	function form_date($field,$value='',$type='model'){
		if ($value=="") {
			$value=date('Y-m-d',time());
		}
		else{
			$value=date('Y-m-d',strtotime($value));
		}
		//如果没有导入jquery，请导入jquery，否则会失效，这里不导入jquery
		return '
		<input type="text" class="form-control input-sm" id="'.$field['field'].'" name="'.$field['field'].'" placeholder="'.$field['title'].'" value="'.$value.'">
		<script>
		$("#'.$field['field'].'").datetimepicker({
			format:"Y-m-d",
			formatDate:"Y-m-d",
			timepicker:false
		});
		</script>
		';
	}

	function form_datetimes($field,$value='',$type='model'){
		if ($value=="") {
			$value=date('Y-m-d H:i:s',time());
		}
		else{
			$value=date('Y-m-d H:i:s',strtotime($value));
		}
		//如果没有导入jquery，请导入jquery，否则会失效，这里不导入jquery
		return '
		<input type="text" class="form-control input-sm" id="'.$field['field'].'" name="'.$field['field'].'" placeholder="'.$field['title'].'" value="'.$value.'">
		<script>
		$("#'.$field['field'].'").datetimepicker({
			format:"Y-m-d H:i:s",
			formatDate:"Y-m-d H:i:s",
		});
		</script>
		';
	}





	// ======================富文本编辑器类=================
	function form_ueditor($field,$value='',$type='model'){
		return '
	      <textarea class="form-control" rows="3" name="'.$field['field'].'">'.$value.'</textarea>
		';
	}

	function form_mini_kindeditor($field,$value='',$type='model'){
		return '
		<link rel="stylesheet" href="'.__ROOT__.'/Public/plug/kindeditor-4.1.10/themes/default/default.css" />
		<script charset="utf-8" src="'.__ROOT__.'/Public/plug/kindeditor-4.1.10/kindeditor-min.js"></script>
		<script charset="utf-8" src="'.__ROOT__.'/Public/plug/kindeditor-4.1.10/lang/zh_CN.js"></script>
		<script>
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create(\'textarea[name="'.$field['field'].'"]\', {
					resizeType : 1,
					allowPreviewEmoticons : false,
					allowImageUpload : false,
					items : [
						\'fontname\', \'fontsize\', \'|\', \'forecolor\', \'hilitecolor\', \'bold\', \'italic\', \'underline\',
						\'removeformat\', \'|\', \'justifyleft\', \'justifycenter\', \'justifyright\', \'insertorderedlist\',
						\'insertunorderedlist\', \'|\', \'emoticons\', \'image\', \'link\']
				});
			});
		</script>
		<textarea class="form-control" rows="3" name="'.$field['field'].'" style="height:370px;width:800px;">'.$value.'</textarea>
		';
	}

	function form_all_kindeditor($field,$value='',$type='model'){
		return '
		<link rel="stylesheet" href="'.__ROOT__.'/Public/plug/kindeditor-4.1.10/themes/default/default.css" />
		<script charset="utf-8" src="'.__ROOT__.'/Public/plug/kindeditor-4.1.10/kindeditor-min.js"></script>
		<script charset="utf-8" src="'.__ROOT__.'/Public/plug/kindeditor-4.1.10/lang/zh_CN.js"></script>
		<script>
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create(\'textarea[name="'.$field['field'].'"]\', {
					allowFileManager : true
				});
			});
		</script>
		<textarea class="form-control" style="width:800px;height:400px;"  rows="3" name="'.$field['field'].'">'.$value.'</textarea>
		';
	}



	// ======================上传文件类=================
	function form_img($field,$value='',$type='model'){
		return '
	      <textarea class="form-control" rows="3" name="'.$field['field'].'">'.$value.'</textarea>
		';
	}

	function form_file($field,$value='',$type='model'){
		return '
	      <textarea class="form-control" rows="3" name="'.$field['field'].'">'.$value.'</textarea>
		';
	}

	// ========================选择类===================
	function form_bool($field,$value='',$type='model'){
		$obj='<select class="form-control input-sm" name="'.$field['field'].'">';
		if ($type=='search') {
			$obj=$obj.'<option value="" selected = "selected" >请选择</option>';
		}

		if ($value=='1' and $type != "search" ) {
			$obj=$obj.'<option value="1" selected = "selected" >是</option>';
			$obj=$obj.'<option value="0">否</option>';
		}
		else{
			$obj=$obj.'<option value="1">是</option>';
			$obj=$obj.'<option value="0" selected = "selected" >否</option>';
		}
		$obj=$obj.'</select>';
		return $obj;
	}

	function form_select($field,$value='',$type='model'){
		$art_str=toAry($field['config']);

        $fk=json_decode($art_str,true);
        if ($fk['_CONFIG']) {
        	$fk=C($fk['_CONFIG']);
        	if (!$fk) {
        		$fk=array('-1'=>'警告：没有在配置文件配置该项！');
        	}
        }
        $text_select='<select name="'.$field['field'].'" class="form-control input-sm">';
				if ($type=='search') {
					$text_select=$text_select.'<option value="" selected = "selected" >请选择</option>';
				}
				foreach ($fk as $key => $row) {

        	if ($value==$key  and $type != "search") {
        		$text_select=$text_select.'<option value="'.$key.'" selected = "selected">'.$row.'</option>';
        	}
        	else{
        		$text_select=$text_select.'<option value="'.$key.'">'.$row.'</option>';
        	}

        }
        $text_select=$text_select.'</select>';
        return $text_select;
	}


	function form_checkbox($field,$value='',$type='model'){
		return '
	      <textarea class="form-control" rows="3" name="'.$field['field'].'">'.$value.'</textarea>
		';
	}


	// 树形菜单选择器
	function form_menutree($field,$value='',$type='model'){
		// $value 就是上级菜单id
		$menu_map0=array();
		$menu_map1=array();
		$menu_map2=array();
		$menu_map0['pid']="0";
		$menulist=get_node_auth($menu_map0);
		$text_menu='<select name="'.$field['field'].'" class="form-control input-sm">';
		$text_menu=$text_menu.'<option value="0">-| 顶级栏目</option>';
		foreach ($menulist as $keytree => $rowtree) {
			if ($value==$rowtree['id']) {
				$text_menu=$text_menu.'<option value="'.$rowtree['id'].'"  selected = "selected" >';

			}
			else{
				$text_menu=$text_menu.'<option value="'.$rowtree['id'].'">';
			}
			$text_menu=$text_menu.'--| '.$rowtree['title'];
			$text_menu=$text_menu.'</option>';

			$menu_map1['pid']=$rowtree['id'];

			$cor_menu=get_node_auth($menu_map1);
			if ($cor_menu) {
				foreach ($cor_menu as $key1 => $row1) {
					if ($value==$row1['id']) {
						$text_menu=$text_menu.'<option value="'.$row1['id'].'"  selected = "selected" >';
					}
					else{
						$text_menu=$text_menu.'<option value="'.$row1['id'].'">';
					}
					$text_menu=$text_menu.'----| '.$row1['title'];
					$text_menu=$text_menu.'</option>';
					$menu_map2['pid']=$row1['id'];
					$act_menu=get_node_auth($menu_map2);
					if ($act_menu) {
						foreach ($act_menu as $key2 => $row2) {
							if ($value==$row2['id']) {
								$text_menu=$text_menu.'<option value="'.$row2['id'].'"  selected = "selected" >';
							}
							else{
								$text_menu=$text_menu.'<option value="'.$row2['id'].'">';
							}
							// $text_menu=$text_menu.'<option value="'.$row2['id'].'">';
							$text_menu=$text_menu.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| '.$row2['title'];
							$text_menu=$text_menu.'</option>';
						}
					}
				}
			}
		}
		$text_menu=$text_menu.'</select>';
		return $text_menu;
	}

	//使用他的时候 表结构必须一致
	function form_tree($field,$value='',$type='model'){
		$m=$field['config'];

		$catModel=M($m);
		$cat_list=$catModel->where('parent_id=0')->order('id asc')->select();
		$this->cat_text_menu='<select name="'.$field['field'].'" class="form-control input-sm">';
		$this->cat_text_menu=$this->cat_text_menu.'<option value="0">顶级分类</option>';
		foreach ($cat_list as $key1 => $row1) {
			if ($value==$row1['id']) {
				$this->cat_text_menu=$this->cat_text_menu.'<option value="'.$row1['id'].'" selected = "selected"> '.$this->set_tree_style($row1['grade']).$row1['cat_name'].'</option>';
        	}
        	else{
        		$this->cat_text_menu=$this->cat_text_menu.'<option value="'.$row1['id'].'"> '.$this->set_tree_style($row1['grade']).$row1['cat_name'].'</option>';
        	}


	 		$cat_list1=$catModel->where('parent_id='.$row1['id'])->order('id asc')->select();
	 		if ($cat_list1) {

	 			$this->get_tree($this->cat_text_menu,$catModel,$row1['id'],$value);
	 		}
	 	}
		$this->cat_text_menu=$this->cat_text_menu.'</select>';
		return $this->cat_text_menu;
	}

	function get_tree($text_menu,$catModel,$pid,$value)
	{
		$cat_list=$catModel->where('parent_id='.$pid)->order('id asc')->select();
		foreach ($cat_list as $key2 => $row2) {
			if ($value==$row2['id']) {
				$this->cat_text_menu=$this->cat_text_menu.'<option value="'.$row2['id'].'" selected = "selected"> '.$this->set_tree_style($row2['grade']).$row2['cat_name'].'</option>';
        	}
        	else{
        		$this->cat_text_menu=$this->cat_text_menu.'<option value="'.$row2['id'].'"> '.$this->set_tree_style($row2['grade']).$row2['cat_name'].'</option>';
        	}
			$cat_list2=$catModel->where('parent_id='.$row2['id'])->order('id asc')->select();
			if ($cat_list2) {
				$this->get_tree($this->cat_text_menu,$catModel,$row2['id'],$value);
			}
		}
	}

	function set_tree_style($grade){
		$style="";
		for ($i=0; $i <$grade ; $i++) {
			$style='├─'.$style;
		}
		return $style;
	}


	// 地区选择，类似菜单,县级
	function form_regiontree($field,$value='',$type='model'){
		// $value 就是上级菜单id
		// 获取顶级sort_id或者
		$menuData=M('nation');

		$menulist=$menuData->where('pid=1')->select();
		$text_menu='<select name="'.$field['field'].'" class="form-control input-sm">';
		$text_menu=$text_menu.'<option value="0">-| 全国</option>';
		foreach ($menulist as $keytree => $rowtree) {
			if ($value==$rowtree['id']) {
				$text_menu=$text_menu.'<option value="'.$rowtree['id'].'"  selected = "selected" >';

			}
			else{
				$text_menu=$text_menu.'<option value="'.$rowtree['id'].'">';
			}
			$text_menu=$text_menu.'--| '.$rowtree['province'];
			$text_menu=$text_menu.'</option>';
			$cor_menu=get_region_node('pid='.$rowtree['id']);
			if ($cor_menu) {
				foreach ($cor_menu as $key1 => $row1) {
					if ($value==$row1['id']) {
						$text_menu=$text_menu.'<option value="'.$row1['id'].'"  selected = "selected" >';
					}
					else{
						$text_menu=$text_menu.'<option value="'.$row1['id'].'">';
					}
					$text_menu=$text_menu.'----| '.$row1['city'];
					$text_menu=$text_menu.'</option>';
					// 取消第三级
					// $act_menu=get_region_node('pid='.$row1['id']);
					// if ($act_menu) {
					// 	foreach ($act_menu as $key2 => $row2) {
					// 		if ($value==$row2['id']) {
					// 			$text_menu=$text_menu.'<option value="'.$row2['id'].'"  selected = "selected" >';
					// 		}
					// 		else{
					// 			$text_menu=$text_menu.'<option value="'.$row2['id'].'">';
					// 		}
					// 		// $text_menu=$text_menu.'<option value="'.$row2['id'].'">';
					// 		$text_menu=$text_menu.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;| '.$row2['title'];
					// 		$text_menu=$text_menu.'</option>';
					// 	}
					// }
				}
			}
		}
		$text_menu=$text_menu.'</select>';
		return $text_menu;
	}


	// 地区选择，类似菜单,省级
	function form_sj_regiontree($field,$value='',$type='model'){
		// $value 就是上级菜单id
		$menuData=M('nation');
		$menulist=$menuData->where('pid=1')->select();
		$text_menu='<select name="'.$field['field'].'" class="form-control input-sm">';
		$text_menu=$text_menu.'<option value="0">-| 全国</option>';
		foreach ($menulist as $keytree => $rowtree) {
			if ($value==$rowtree['id']) {
				$text_menu=$text_menu.'<option value="'.$rowtree['id'].'"  selected = "selected" >';
			}
			else{
				$text_menu=$text_menu.'<option value="'.$rowtree['id'].'">';
			}
			$text_menu=$text_menu.'--| '.$rowtree['province'];
			$text_menu=$text_menu.'</option>';
		}
		$text_menu=$text_menu.'</select>';
		return $text_menu;
	}



	function form_tables($field,$value='',$type='model'){
		$sql_data=M();
        $db_prefix_key="tables_in_".C('DB_NAME');
        $rescolumns = $sql_data->query("show tables");
        $tables='<select name="'.$field['field'].'" class="form-control input-sm">';
        $tables=$tables.'<option value="">请选择一个表</option>';
        foreach ($rescolumns as $key => $row) {
        	$tabname=str_replace(C('DB_PREFIX'), '', $row[$db_prefix_key]);
        	if ($row[$db_prefix_key]==C('DB_PREFIX').$value) {
        		$tables=$tables.'<option value="'.$tabname.'" selected = "selected" >'.$tabname.'</option>';
        	}
        	else{
        		$tables=$tables.'<option value="'.$tabname.'">'.$tabname.'</option>';
        	}
        }
        $tables=$tables.'</select>';
        return $tables;
	}

	function form_foreign_select($field,$value='',$type='model'){
		$art_str=toAry($field['config']);
        $fk=json_decode($art_str,true);

        $ifun=$fk['fun'];//获取过滤参数
        if ($ifun) {
        	$imap=$fk['fun']();
        }
        else{
        	$imap="1=1";
        }

        $modelData=M($fk['table']);
        $data=$modelData->where($imap)->select();
        $text_select='<select name="'.$field['field'].'" class="form-control input-sm">';
        $text_select=$text_select.'<option value="">选择</option>';
        foreach ($data as $key => $row) {
        	if ($value==$row[$fk['key_field']]) {
        		$text_select=$text_select.'<option value="'.$row[$fk['key_field']].'" selected="selected">'.$row[$fk['show_field']].'</option>';
        	}
        	else{
        		$text_select=$text_select.'<option value="'.$row[$fk['key_field']].'">'.$row[$fk['show_field']].'</option>';
        	}
        }
        $text_select=$text_select.'</select>';
        return $text_select;
	}
	//单图上传
	function form_uploads($field,$value='',$type='model'){
				if ($value) {
					return '
	        <input id="file_'.$field['field'].'" class="form-control input-sm"  type="text" name="'.$field['field'].'" value="'.$value.'">
	        <iframe scrolling="no" frameborder=0 marginheight=10 height=31 width=400 name="inmain" src="'.U("/Plugs/upload/index",array('model'=>$field['model'],'field'=>$field['field'])).'"></iframe>
	        <div class="imgshow" id="show_'.$field['field'].'"><img src="'.__ROOT__.$value.'" style="max-height:90px;" /></div>
	        ';
				}
				else{
					return '
	        <input id="file_'.$field['field'].'" class="form-control input-sm"  type="text" name="'.$field['field'].'" value="'.$value.'">
	        <iframe scrolling="no" frameborder=0 marginheight=10 height=31 width=400 name="inmain" src="'.U("/Plugs/upload/index",array('model'=>$field['model'],'field'=>$field['field'])).'"></iframe>
	        <div class="imgshow" id="show_'.$field['field'].'"></div>
	        ';
				}

	}




	function form_m_uploads($field,$value="")
	{
		$li_list="";
		$imglist=explode(",",$value);
		foreach ($imglist as $row) {
			if ($row!="") {
				$li_list=$li_list."<li style='background:url(".__ROOT__."$row);    background-position: center;
    background-size: contain;
    background-repeat: no-repeat;'><span class='imgdel' dataval='$row'>删除</span></li>";
			}

		}

		return '
			<style>
			.scitem{position: fixed;display: none;overflow: hidden;width: 800px;top: 10px;z-index:9999;}
			.sc-head{border-bottom: 5px solid #00B7EE;color: #fff;font-family: "微软雅黑";background: #fff;overflow: hidden;height: 50px;}
			.sc-title{margin: 5px;color:#009CEE;border:1px solid #00B7EE;cursor: pointer;padding: 3px 8px 3px 8px;font-weight: bold;float: left;background: #fff;border-radius: 4px;}
			.sc-close{margin: 5px;color:#009CEE;border:1px solid #00B7EE;cursor: pointer;padding: 3px 8px 3px 8px;font-weight: bold;float: right;background: #fff;border-radius: 4px;}
			.scmain{background: #fff;margin: 50px 50px 50px 0px;overflow: hidden;border: 5px #33B5E5  solid;border-top: 5px #33B5E5  solid;border-radius: 10px;
			}
			.bn{width:120px;cursor: pointer;padding: 8px;font-weight: bold;background: #00B7EE;border-radius: 4px;color: #fff;text-align: center;}

			#imglist{overflow: hidden;list-style-type:none;margin:0px;padding:0px;}

			#imglist li{text-align: center; float: left;line-height: 80px;margin-right: 5px;padding: 2px;border: 1px solid #ccc;height:80px;width:120px;
			}

			#imglist li img{
			    vertical-align: middle;
			    max-height: 120px;
			    max-width: 120px;
			}

			#imglist li p{
			    line-height: 28px;
			}
			.clear{
			    clear:both;
			}

			.form-item .imgdel{
			        font-size: 11px;
				    display: none;
				    position: absolute;
				    line-height: 22px;
				    /* margin-top: -122px; */
				    padding: 0px 11px;
				    cursor: pointer;
				    background: #00B7EE;
				    color: #fff;
				    /* margin-left: 76px; */
				    border-radius: 2px;
				    height: 20px;
				    width: 50px;
			}

			#imglist li:hover .imgdel{
			    display: block;

			}

			.hidbder{
				border:0px;
			}

			.bn{
				float:left;
			}

			.bn a{
				color:#fff;
			}

			.piclist{
				margin:10px 0px;
			}


			</style>

			<div class="main bn">
			<a id="sc">点击上传图片</a>
			</div>
			<div class="clear"></div>
			<div class="piclist">
			    <input type="hidden" class="hidbder" value="'.$value.'"  name="'.$field["field"].'"  id="file_'.$field["field"].'" />
			    <ul id="imglist">
			    	'.$li_list.'
			    </ul>
			</div>

			<div class="scitem">
			    <div class="scmain">
			    <div class="sc-head">
			        <div class="sc-title">完成上传</div>
			        <div class="sc-close">关闭X</div>
			    </div>
			    <div class="sc-body">
			        <iframe frameborder="no" width=100% height=500 name="photoUploadDialog" id="photoUploadDialog" src="'.__ROOT__.'/Public/plug/WP/examples/image-upload?form_id=file_'.$field["field"].'"></iframe>
			    </div>
			    </div>
			</div>
			<script>
			$(document).ready(function(){


				$("#sc").click(function(){
				  $(".scitem").show();
				  document.getElementById(\'photoUploadDialog\').contentWindow.location.reload(true);
				});


				$(".sc-close").click(function(){
				  $(".scitem").hide();
				  document.getElementById(\'photoUploadDialog\').contentWindow.location.reload(true);
				});

				$(".sc-title").click(function(){
				  $(".scitem").hide();
				  document.getElementById(\'photoUploadDialog\').contentWindow.location.reload(true);
				});

				function iFrameHeight() {
				    var ifm= document.getElementById("photoUploadDialog");
				    var subWeb = document.frames ? document.frames["photoUploadDialog"].document : ifm.contentDocument;
				    if(ifm != null && subWeb != null) {
				       ifm.height = subWeb.body.scrollHeight;
				       ifm.width = subWeb.body.scrollWidth;
				    }
				}

				$("body").on("click",".imgdel",function(){
					var is_go=confirm("删除是不可恢复的，你确认要删除吗？");
				  if (is_go) {
					  result = $("#file_'.$field["field"].'").val(); //获取值
					  reslist = result.split(",");//值列表
					  delimg = $(this).attr("dataval");
					  var this_id=-1;
					  for(var i=0,n=0;i<reslist.length;i++)
					  {
					      if (delimg==reslist[i]) {
					      	this_id=i;//得到要删除的下标
					      	break;
					      }
					  }

				  reslist.splice(this_id,1);
				  new_value=reslist.join(",");
				  $("#file_'.$field["field"].'").val(new_value);
				  alert("删除成功!");
				  $(this).parent().remove();
				 }
				});

			});

			</script>

		';
	}



}

// $form=new Form('menu');

// 传送的数据格式
// 2个参数模型名称，必须填写 隐藏字段，

?>
