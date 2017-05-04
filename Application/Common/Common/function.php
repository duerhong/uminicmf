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
// UminiCmf主函数库
//----------------------------------

// -----------------------------------主函数库------------------------------------
// 用户权限
function user_auth(){
	if (!isset($_SESSION['user']['username'])) {
		return false;
	}
	else{
		return true;
	}
}



//操作日志
function action_log($action,$table_name="",$model_name="",$obj_id=""){
	$user_id=session('user.user_id');
	if (isset($user_id) and !empty($user_id)) {
		$log_data['user_id']=$user_id;
		$log_data['table_name']=$table_name;
		$log_data['model_name']=$model_name;
		$log_data['action']=$action; //增加，修改，删除，回收
		$log_data['obj_id']=intval($obj_id); //增加，修改，删除，回收
		$log_data['times']=date("Y-m-d H:i:s",time());
		$log_data['ip']=get_client_ip();
		M('user_log')->data($log_data)->add();
	}
	else{
		die('用户未登录');
	}
	return true;
}


function form_verify($post=''){
	return true;
}

// ============================报错系统==================================
function error(){
	// 根据返回的错误代码，在数据库中查找相应的记录，方便解决下次遇到同类问题
}
// ===================================菜单读取=================================
// 节点与权限分配与检测  返回一个数组，该数组中包含了各种要查询的结果集。
function get_menu($map,$type='select'){

	$Model_data=M('menu');
	$data_list=$Model_data->where($map)->$type();
	// echo $Model_data->getLastSql();
	// exit();
	$auth_node=array();
	if ($_SESSION['user']['is_super']) {
		// echo "===========is_super-----------";
		// 超级管理员获取所有的节点，并且是动态获取
		$auth_all_list=get_node('1');
		$auth_list=array();
		foreach ($auth_all_list as $key => $row) {
         array_push($auth_list, $row['id']);
     }
	}
	else{
		$auth_list=$_SESSION['auth_menu'];
	}

	if ($type=='find') {
		if (in_array($data_list['id'], $auth_list)) {
			$auth_node=$data_list;
		}
	}
	elseif ($type=='select') {
		foreach ($data_list as $key => $row) {
			if (in_array($row['id'], $auth_list)) {
				array_push($auth_node, $row);
			}
		}
	}
	else{
		die('对不起，你没有权限，或者该没有该模块');
	}
	if ($auth_node) {
		return $auth_node;
	}
	else{
		return false;
	}

}

function get_node($map,$type='select'){
	$Model_data=M('menu');
	$data_list=$Model_data->where($map)->$type();
	// echo $Model_data->getLastSql();
	// exit();
	if ($data_list) {
		return $data_list;
	}
	else{
		return false;
	}
}

function get_node_auth($map,$type='select'){
	if ($_SESSION['user']['is_super']) {
		// 超级管理员获取所有的节点，并且是动态获取
		$auth_all_list=get_node('1');
		$auth_list=array();
		foreach ($auth_all_list as $key => $row) {
         array_push($auth_list, $row['id']);
     }
	}
	else{
		$auth_list=$_SESSION['auth_menu'];
	}

	$map['id']  = array('in',$auth_list);
	$Model_data=M('menu');
	$data_list=$Model_data->where($map)->$type();
	if ($data_list) {
		return $data_list;
	}
	else{
		return false;
	}
}



function get_region_node($map,$type='select'){
	$Model_data=M('nation');
	$data_list=$Model_data->where($map)->$type();
	// echo $Model_data->getLastSql();
	// exit();
	if ($data_list) {
		return $data_list;
	}
	else{
		return false;
	}
}

function get_top_menu($menu){
	$Model_data=M('menu');
	if ($menu['type']=='0') {
		return $menu;
	}
	elseif ($menu['type']=='1') {
		$up1_menu=get_menu('id='.$menu['pid'],'find');
		return $up1_menu;
	}
	elseif ($menu['type']=='2') {
		$up1_menu=get_menu('id='.$menu['pid'],'find');
		$up2_menu=get_menu('id='.$up1_menu['pid'],'find');
		return $up2_menu;
	}
	else {
		$up1_menu=get_menu('id='.$menu['pid'],'find');
		$up2_menu=get_menu('id='.$up1_menu['pid'],'find');
		$up3_menu=get_menu('id='.$up2_menu['pid'],'find');
		return $up3_menu;
	}
}

// 表信息
function get_table_info($table_name){
	$table = M();
	$rescolumns = $table->query("SHOW FULL COLUMNS FROM `xq_model`");
	// print_r($rescolumns);
	foreach ($rescolumns as $key => $row) {
	  echo $row['field'].' | '.$row['type'].' | '.$row['comment'];
	  echo "<br />";
	}
}


// =================获取Model========================

function get_model($model_name){
	$Model_data=M('model');
	$map=array('model_name'=>$model_name);
	$result=$Model_data->where($map)->find();
	if ($result) {
		return $result;
	}
	else{
		return false;
	}
}

function get_model_id($id){
	$Model_data=M('model');
	$map=array('id'=>$id);
	$result=$Model_data->where($map)->find();
	if ($result) {
		return $result;
	}
	else{
		return false;
	}
}


// 获取操作node
function get_action_node($menu_id){

}


// =================检测字段表中是否存在模型下某个字段========================
function checked_field($model,$field){
	$Model_data=M('fields');
	$map=array('field'=>$field,'model'=>$model);
	$result=$Model_data->where($map)->find();
	if ($result) {
		return true;
	}
	else{
		return false;
	}
}

function get_value($field,$value,$model){
	$Model_data=M('fields');
	$map=array('field'=>$field,'model'=>$model);
	$result=$Model_data->where($map)->find();
	if ($result) {
		return $result[$value];
	}
	else{
		return false;
	}
}


function Form($model=false,$data=false){
	if ($model) {
		$ModelData=M('fields');
		$map=array('model'=>$model,'status'=>'1');
		$preData=$ModelData->where($map)->order('orderid asc')->select();
		if ($data) {
			$datalist=array();
			foreach ($preData as $key => $new_fields) {
				$new_fields['value']=$data[$new_fields['field']];
				array_push($datalist,$new_fields);
			}
			return  new \Vendor\Mylib\Form($datalist);
		}
		else{
			return  new \Vendor\Mylib\Form($preData);
		}
	}
	else{
		return  new \Vendor\Mylib\Form();
	}
}

function FormValidate($model=false){
	if ($model) {
		$ModelData=M('fields');
		$map=array('model'=>$model,'status'=>'1');
		$preData=$ModelData->where($map)->order('orderid asc')->select();
		return  new \Vendor\Mylib\FormValidate($preData,$model);
	}
	else{
		die('数据模型不存在！错位位置FormValidate');
	}
}

// 用户生成表单
function UserForm($model=false,$data=false){
	return  new \Vendor\Mylib\UserForm();
}



function get_path($path){
	$path_list=explode('-',$path);
	$map='';
	$str_list='';
	foreach ($path_list as $key => $row) {
		if ($row=='0') {
			$str_list=$str_list.'<li><a href="#">首页</a></li>';
		}
		else{
			$map='id='.$row;
			$smenu=get_menu($map,$type='find');
			$str_list=$str_list.'<li><a href="#">'.$smenu['title'].'</a></li>';
		}
	}
	return $str_list;

}

// 获取表索引
function index_table($tables){
	$sql_data=M();
    $rescolumns = $sql_data->query("show index from ".$tables);
    return $rescolumns;
}

// 获取表索引
function table_count($tables){
	$sql_data=M();
    $rescolumns = $sql_data->query("select count(*) as ct from ".$tables);
    return $rescolumns[0]['ct'];
}

// ===============检测状态是否被选中===========
function is_checked($node_id,$node_str){
	$node_list=explode(',',$node_str);
	if (in_array($node_id,$node_list)) {
		return 'checked';
	}
	else{
		return '';
	}
}


// 将字段集 key:value 换行；转换为数组
function toAry($str)
{
	$str='{"'.$str;
	$str=str_replace("\n", '","', $str);
	$str=str_replace(":", '":"',$str);
	$str=$str.'"}';
	return str_replace("\r",'',$str);
}


function alert($info){
	echo "<script>";
	echo "alert('".$info."');";
	echo "</script>";
}

function goback(){
	echo "<script>";
	echo "javascript:history.go(-1);";
	echo "</script>";
	exit();
}

function confirm($info="是否确认删除？"){
	echo "<script>";
	echo "
		if(window.confirm(".$info.")){
           return true;
        }else{
           return false;
       }
	";
	echo "</script>";
}




function init_m_about($m_id){
	$mSite=M('m_site');
    $map=array('m_id'=>$m_id);
    $result=$mSite->where($map)->find();
    if (!$result) {
    	$data['content']="请输入个人/公司简介";
    	$data['m_id']=$m_id;
    	$mSite->add($data);
    }
    return true;
}




//是否为检测是否为账号
function is_account($str){
	if(preg_match("/^[a-zA-Z\s]+$/",$str)){
		return true;
	}
	else{
		return false;
	}

}


// 判断全是中文
function all_znstr($str){
	if(!eregi("[^\x80-\xff]",$str)){
		return true;
	}else{
		return false;
	}

}

function is_phone($str){
	if(preg_match("/1[3458]{1}\d{9}$/",$str)){
    return true;
	}else{
	  return false;
	}

}

function is_email($email)
{
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	 {
	 	return false;
	 }
	else
	 {
	 	return true;
	 }
}


// 获取当前登录用户
function get_user(){
	$current_user=M('user')->where('username="'.$_SESSION['user']['username'].'"')->find();
	return $current_user;
}


// 获取用户权限，返回数组
function get_user_act_auth(){
	$user=get_user();
	$auth_group=explode(',',$user['auth_group']);
	$auth_list=array();
	foreach ($auth_group as $key => $row) {
	 $auth=M('auth_group')->where('id='.$row)->find();
	 $auth_list=array_merge($auth_list,explode(',',$auth['rules']));
	}
	return $auth_list;
}




// 判断用户是否有某个操作的权限
function auth_action($act_name){
	// 1.判断用户是否为超级管理员，如果是超级管理员，则有这个权限
	if ($_SESSION['user']['is_super']) {
		$res=M('menu')->where('node_name="'.$act_name.'"')->find();
		return $res;
	}

	$user=get_user();
	$auth_group=explode(',',$user['auth_group']);
	$auth_list=array();
	$res_act=false;
	foreach ($auth_group as $key => $row) {
	 $auth=M('auth_group')->where('id='.$row)->find();
	 $auth_list=array_merge($auth_list,explode(',',$auth['rules']));
	 foreach ($auth_list as $key => $row) {
	 	$res=M('menu')->where('id="'.$row.'"')->find();
	 	if ($res['node_name']==$act_name) {
	 		$res_act=true;
	 		return $res;
	 	}
	 }
	}
	if (!$res_act) {
		return false;
	}
}

// 重新排序
function againOrder($map, $tab, $field) {
    $node = M($tab);
    foreach ($map as $key => $row) {
        $data['id'] = $key;
        $data[$field] = $row;
        $node->save($data);
    }
    return true;
}

// 执行保存
// 应该用I()方法重新组装下数据
function sys_save($table_name,$model_name,$types,$map=false){
	$data=array();
	$field_data=array();
	// 获取模型中的字段列表
	$field_list=M('fields')->where('model="'.$model_name.'" and status=1')->select();


	foreach ($field_list as $key => $row) {
		array_push($field_data,$row['field']);
	}
	foreach ($_POST as $key => $row) {
		if (in_array($key,$field_data)) {
			// 这里做表单验证 1.获取验证函数，2调用类去验证，3.返回true，false。
			// 如果是false，则获取当前地址，提示保存失败，弹出提示信息，跳转当前页面
			$this_field=M('fields')->field('field,verify')->where('verify!="" and model="'.$model_name.'" and status=1 and field="'.$key.'"')->find();
			if ($this_field['verify']) {
				$validate_db=new \Vendor\Mylib\DbValidate();
				$array = str_replace("\r","",$this_field['verify']);
				$array1 = str_replace("\n","",$array);
				$art_str = explode("#",$array1);
				foreach ($art_str as $key_str => $row_str) {
					$str=explode('|', $row_str);
					$funs='v_'.$str[0];
					if(!method_exists($validate_db,$funs)){
						continue;
					}
					else{
						$yz_res=$validate_db->$funs($_POST[$key],$str[1]);
						if (!$yz_res) {
							alert($str[2]);
							goback();
						}
					}
				}
			}

			$data[$key]=I('post.'.$key);
		}
	}
    if ($types=='add') {
        if (form_verify($data)) {
			$result=M($table_name)->add($data);
			// echo M($table_name)->getLastSql();
			// exit();
			if ($result) {
				$_SESSION['act_info']='新增成功！';
				$action_info="增加";
				action_log($action_info,$table_name,$model_name,$result);//执行add成功日志
				return true;
			}
			else{
				$_SESSION['act_info']='新增失败！';
				// action_log();//执行add失败日志
				return false;
			}
        }
    }
    elseif($types=='update'){
        if (form_verify($data)) {
			if ($map) {
				$result=M($table_name)->where($map)->save($data);
			}
			else{

				$result=M($table_name)->save($data);
				// echo M($table_name)->getLastSql();
			}
			if ($result) {
				$action_info="修改";
				action_log($action_info,$table_name,$model_name,$data['id']);//执行add成功日志
				$_SESSION['act_info']='更新成功！';
				return true;
			}
			else{
				$_SESSION['act_info']='更新失败！';
				// action_log();//执行add失败日志
				return false;
			}
        }
    }
}




function get_status($status)
{
	if ($status=='1') {
		return '是';
	}
	else {
		return '否';
	}
}



// 设置列表页
// 一般列表页有搜索，有标题栏，有内容列表区域，还有增加，修改操作区域
function lists($table_name){
	$model_data=D($table_name);
    if (!isset($_GET['p'])) {
        $start_page=1;
    }
    else{
        $start_page=$_GET['p'];
    }
    if ($_POST['submit']) {
        // 当post为空的时候，应该删除该项
        foreach ($_POST as $pkey => $prow) {
            if (empty($prow)) {
                unset($_POST[$pkey]);
                continue;
            }
            $map[$pkey]=array('like',$prow);
        }

    }
    if ($map) {
        $map['_logic'] = 'AND';
        $this->list_data = $model_data->where($map)->order('orderid desc,id desc')->page($start_page.',10')->select();

        $count      = $model_data->where($map)->count();// 查询满足要求的总记录数
    }
    else{
        $this->list_data = $model_data->order('orderid desc,id desc')->page($start_page.',10')->select();
        $count      = $model_data->count();// 查询满足要求的总记录数
    }


    // 实现分页###########################################################

    // 搜索表单###########################################################
    $filter_list=explode(',', $this->THIS_MODEL['filter']);

    $fieldsModel=M('fields');
    $form_model=Form($this->THIS_MODEL['model_name']);
    $this->saerchForm=$form_model->search_form($filter_list);
    // 搜索表单###########################################################

    $art_str=toAry($this->THIS_MODEL['list_filed']);
    $this->title_lists=json_decode($art_str,true);

    // 获取当前菜单栏目所在的所有操作方法(比如修改 删除)
    $act_map=array('type'=>3,'pid'=>$this->PUB_THISMENU['id']);
    $this->action_lists=get_menu($act_map);
    $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
    $this->action_add_button=get_menu($add_map,'find');
    $this->displayAuto($this->PUB_THISMENU['id']);
}

function get_process_cycle_user_list($process_cycle_id){
	        // 审批流程用户列表
    $datalist=M('process_cycle_user')->where('process_cycle_id='.$process_cycle_id)->order('orderid asc,id asc')->select();
    return $datalist;
}

function get_obj_user($id,$key=false){
	$obj_user=M('user')->where('id='.$id)->find();
	if ($key) {
		return $obj_user[$key];
	}
	else{
		return $obj_user;
	}
}

function get_field($model_name,$key=false,$field=false){
	$Model_data=M('fields');
	$map['model']=$model_name;
	$map['field']=$key;
	$result=$Model_data->where($map)->find();
	if (!$field) {
		$field='title';
	}
	if ($key) {
		if ($result) {
			return $result[$field];
		}
		else{
			return false;
		}
	}
	else{
		if ($result) {
			return $result;
		}
		else{
			return false;
		}
	}
}



function is_valid_field($model_name,$key=false){
	$Model_data=M('fields');
	$map['field']=$key;
	$map['model']=$model_name;
	$map['status']=array('neq',0);
	$map['plug']=array('neq','form_hidden');
	$result=$Model_data->where($map)->find();

	// echo $Model_data->getLastSql();
	if ($result) {
		return true;
	}
	else{
		return false;
	}
}

function set_tree_style($grade){
	$style="";
	for ($i=0; $i <$grade ; $i++) {
		if ($i==$grade-1) {
			$style='╠'.$style;
		}
		else{
			$style='═'.$style;
		}
	}
	return "<span style='letter-spacing: -2;margin-right: 3px;'>".$style."</span>";
}

function get_tree($title_lists,$action_lists,$catModel,$pid)
{
    $cat_list=$catModel->where('parent_id='.$pid)->order('id asc')->select();

    foreach ($cat_list as $keyy => $rowy) {
	    echo "<tr>";
	    foreach ($title_lists as $key2 => $row2) {
	        echo "<td>".set_tree_style($rowy['grade']).$rowy[$key2]."</td>";
	    }
	    echo  "<td>";
	    foreach ($action_lists as $act_key => $act_row) {
	    	echo "<a href='/".$act_row['node_name']."&id=".$rowy['id']."' onclick='".$act_row['pre_func']."();'>";
	    	echo $act_row['title'];
	    	echo "</a>&nbsp;";
	    }
	    echo "</td>";
	    echo "</tr>";
	    $cat_list3=$catModel->where('parent_id='.$rowy['id'])->order('id asc')->select();
	    if ($cat_list3) {
			get_tree($title_lists,$action_lists,$catModel,$rowy['id']);
		}
    }
}


function menu_tree($list,$pk="id",$pid="parent_id",$child="_child",$root=0)
{
	$tree=array();
	$Temparr=array(); //定义临时数组

	// 1、建立以id为键值的数组；
	foreach ($list as $row) {
		$Temparr[$row[$pk]]=$row;
	}

	foreach ($Temparr as $key1 => $row1) {
		// 将一级栏目加入tree
		if ($row1[$pid]==$root) {
			$tree[]=& $Temparr[$key1]; //tree 跟temparr将引用同一个地址
		}
		else{
			// 为当前值的父类增加多维数组
			$Temparr[$row1[$pid]]["_child"][]=& $Temparr[$key1];
			$lemp=$Temparr[$row1[$pid]]["_child"];
		}
	}
	return $tree;

	//上面是生成标准树
	//一下是将树转化为无限极目录

}

function set_tree_lev(&$list,$root=true)
{
	if(is_array($list)){
	 foreach ($list as $key=>$row)
	 {
	 	if ($root) {
	 		$list[$key]['lev']=0;
	 	}

	   if(is_array($list[$key]['_child'])){
	   	foreach ($list[$key]['_child'] as $key1 => $value1) {
	   		$list[$key]['_child'][$key1]['lev']=$list[$key]['lev']+1;
	   	}
	     set_tree_lev($list[$key]['_child'],false);
	   }
	 }
	}
}

function set_tree($tree,$title_lists,$action_lists,$model,$root=true)
{
	foreach ($tree as $row) {
		if ($root) {
			echo "<tr style='background:#f8f8f8'>";
		}
		else{
			echo "<tr>";
		}

		    foreach ($title_lists as $key2 => $row2) {
		    	if ($key2=="cat_name") {
		    		echo "<td>".set_tree_style($row['lev']).get_model_value($row[$key2],$model,$key2)."</td>";
		    	}
		    	else{
		    		echo "<td>".get_model_value($row[$key2],$model,$key2)."</td>";
		    	}
		    }

		    echo  "<td width='400'>";
		    foreach ($action_lists as $act_key => $act_row) {
		    	echo "<a href='/".$act_row['node_name']."&id=".$row['id']."' onclick='".$act_row['pre_func']."();' class='abtn'>";
		    	echo "<i class='iconfont'>".htmlspecialchars_decode($act_row['img'])."</i>";
		    	echo $act_row['title'];
		    	echo "</a>&nbsp;";
		    }
		    echo "</td>";
		echo "</tr>";
		if ($row['_child']) {
			set_tree($row['_child'],$title_lists,$action_lists,$model,false);
		}
	}
}




// ==================================================非框架类函数======================================================================================================

function zwstr($str,$length,$suffix=false,$start=0,$charset="utf-8")
{
if(function_exists("mb_substr")){
          if($suffix)
          return mb_substr($str, $start, $length, $charset)."...";
          else
               return mb_substr($str, $start, $length, $charset);
     }
     elseif(function_exists('iconv_substr')) {
         if($suffix)
              return iconv_substr($str,$start,$length,$charset)."...";
         else
              return iconv_substr($str,$start,$length,$charset);
     }
     $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
              [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
     $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
     $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
     $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
     preg_match_all($re[$charset], $str, $match);
     $slice = join("",array_slice($match[0], $start, $length));
     if($suffix) return $slice."…";
     return $slice;
}


function slice_img($str,$key='null',$fenggefu=',')
{
	$str_list = explode($fenggefu,$str);
	if ($key=='null') {
		return $str_list;
	}
	else{
		return $str_list[$key];
	}
}



function formatting_day($day)
{
	$day=intval($day);
	if ($day<10) {
		return '0'.$day;
	}
	else{
		return $day;
	}
}



function str_date($dates,$f='Y-m-d')
{
  return date($f,strtotime($dates));
}




// 判断输入的验证码是否合适
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

function get_members($id,$field=false)
{
	$res=M('members')->where('id='.$id)->find();
	if ($field) {
		if ($field=="phone") {
			return substr($res[$field],0,3)."*****".substr($res[$field],-3);
		}
		else{
			return $res[$field];
		}

	}
	else{
		return $res;
	}
}


function utf8_strlen($string = null) {
	// 将字符串分解为单元
	preg_match_all("/./us", $string, $match);
	// 返回单元个数
	return count($match[0]);
}

//根据列表值获取属性
function get_model_value($val,$model,$field)
{
	$Model_data=M('fields');
	$map=array('field'=>$field,'model'=>$model);
	$result=$Model_data->where($map)->find();
	// echo $result['plug'];
	// 1.如果是外键，则调用外键值
	if ($val=="") {
		return "未定义";
	}
	elseif ($result['plug']=='form_foreign_select') {
		$art_str=toAry($result['config']);
        $fk=json_decode($art_str,true);
        $fk_map=array();
        $fk_map[$fk['key_field']]=$val;
		$rs=M($fk['table'])->where($fk_map)->find();
		return $rs[$fk['show_field']];
	}
	elseif ($result['plug']=='form_select') {
		$art_str=toAry($result['config']);
        $fk=json_decode($art_str,true);
        if ($fk['_CONFIG']) {
        	$fk=C($fk['_CONFIG']);
        	if (!$fk) {
        		$fk=array('-1'=>'警告：没有在配置文件配置该项！');
        	}
        }
		return $fk[$val];
	}

	elseif ($result['plug']=='form_bool') {
		if ($val=="1") {
			return "是";
		}
		else{
			return "否";
		}
	}


	else{
		return $val;
	}
}



function get_table_name($table_name)
{
	$table=C('TABLE_LIST');
	if ($table[$table_name]) {
		return $table[$table_name];
	}
	else{
		return '系统';
	}
}

function get_model_name($model_name)
{
	$map=array();
	$map['model_name']=$model_name;
	$data=M('model')->where($map)->find();
	if ($data) {
		return $data['title'];
	}
	else{
		return '系统';
	}
}

function DiffDate($date1, $date2) {
  if (strtotime($date1) > strtotime($date2)) {
    $ymd = $date2;
    $date2 = $date1;
    $date1 = $ymd;
  }
  list($y1, $m1, $d1) = explode('-', $date1);
  list($y2, $m2, $d2) = explode('-', $date2);
  $y = $m = $d = $_m = 0;
  $math = ($y2 - $y1) * 12 + $m2 - $m1;
  $y = round($math / 12);
  $m = intval($math % 12);
  $d = (mktime(0, 0, 0, $m2, $d2, $y2) - mktime(0, 0, 0, $m2, $d1, $y2)) / 86400;
  if ($d < 0) {
    $m -= 1;
    $d += date('j', mktime(0, 0, 0, $m2, 0, $y2));
  }
  $m < 0 && $y -= 1;
  return array($y, $m, $d);
}


//追url中追加参数
function add_url_parameter($key, $value) {
	$node=$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME;
	$up=$_GET;
	$key_exist=false;
	foreach ($up as $key1 => $value1) {
		if ($key==$key1) {
			$up[$key1]=$value;
			$key_exist=true;
			break;
		}
	}
	if (!$key_exist) {
		$up[$key]=$value;
	}
	$url=U($node,$up);
	return $url;
}
?>
