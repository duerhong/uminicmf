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
// 后台-模型管理
//----------------------------------
namespace System\Controller;
use Think\Controller;
class ModelController extends SystemController {
    public function index(){
    	$this->show('hello world!');
    }

    //字段管理
    public function fields_manage(){
    	// $this->funs=get_class_methods();
        // 获取表单插件
    	$this->form_plugs=C('FORM_PLUGS');



        $modelModel=M('model');
        // $map=array('id='.I('get.id'));
        // 获取当前表单模型
        $this->this_model=$modelModel->where('id='.I('get.id'))->find();

        // 获取未注册的字段
        $table = M();
        $rescolumns = $table->query("SHOW FULL COLUMNS FROM `xq_".$this->this_model['table_name']."`");
        // 字段列表
        $field_list=array();
        // 未注册字段
        $new_fields_list=array();
        foreach ($rescolumns as $key => $row) {
            array_push($field_list, $row['field']);
        }

        foreach ($rescolumns as $key => $row) {
            $is_val=checked_field($this->this_model['model_name'],$row['field']);
            if (!$is_val) {
                array_push($new_fields_list,$row);
            }

        }
        $this->new_fields_list=$new_fields_list;

        $this->model_field_list=M('fields')->where('model="'.$this->this_model['model_name'].'" and plug!="form_hidden"  and status=1')->order('field_group_id asc,orderid asc')->select();
        //获取隐藏
        $this->hidden_field_list=M('fields')->where('model="'.$this->this_model['model_name'].'" and plug="form_hidden"')->order('field_group_id asc,orderid asc')->select();
        // print_r(expression)
        //获取禁用
        $this->disable_field_list=M('fields')->where('model="'.$this->this_model['model_name'].'" and status!=1')->order('field_group_id asc,orderid asc')->select();



        // 获取模型下所有字段
        $modelFields=M('fields');
        $map1['model']=$this->this_model['model_name'];
        $model_fields=$modelFields->field('field')->where($map1)->select();
        $model_fields_list=array();
        foreach ($model_fields as $key => $row) {
            array_push($model_fields_list, $row['field']);
        }

        // 获取字段分组
        $this->field_group_list=M('field_group')->where('model_id='.$this->this_model['id'])->select();

        // 模型中废弃字段
        // $this->discard_field=array_diff($model_fields_list, $field_list);
        if (isset($_POST['submit'])) {
            for($i=0;$i<count($_POST['orderid']);$i++){
                $add_list=array();
                // print_r($_POST);
                // exit();
                foreach ($_POST as $key => $row) {
                  //如果他的field为空，则忽略该条字段
                    $add_list[$key]=$row[$i];
                }
                // print_r($add_list);
                // exit();
                if (!$add_list['field'] or empty($add_list['field'])) {
                  continue;
                }
                // 检测是否存在，存在情况下为更新，否则为增加
                $modelModel=M('model');
                $map=array('id'=>I('get.id'));
                $this_model=$modelModel->where($map)->find();
                $is_exist=checked_field($this_model['model_name'],$add_list['field']);
                if (!$is_exist) {
                    $add_list['model']=$this_model['model_name'];
                    M('fields')->add($add_list);
                }
                //更新操作
                else{
                    $add_list['model']=$this_model['model_name'];
                    $map=array('field'=>$add_list['field'],'model'=>$this_model['model_name']);
                    M('fields')->where($map)->save($add_list);
                }
            }

            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            $this->redirect('System/Model/fields_manage',array('id'=>$_GET['id']),0,'页面跳转中...');
        }

        $this->display("System:result_fields");
    }

    public function database_manage(){
        // 这里或许验证其他登录session
        $sql_data=M();
        $this->db_prefix_key="tables_in_".C('DB_NAME');
        $this->all_tables = $sql_data->query("show tables");
        $this->displayAuto($_GET['menu_id']);
    }


    public function fields_update(){
    	// id 是每一个表都有的，所以选择id
		for($i=0;$i<count($_POST['orderid']);$i++){

			$add_list=array();
			foreach ($_POST as $key => $row) {
				$add_list[$key]=$row[$i];
			}
			// 检测是否存在，存在情况下为更新，否则为增加
            $modelModel=M('model');
            $map=array('id'=>I('get.id'));
            $this_model=$modelModel->where($map)->find();

			$is_exist=checked_field($this_model['model_name'],$add_list['field']);
			if (!$is_exist) {
				$add_list['model']=$this_model['model_name'];
				if (form_add($add_list,'fields')) {
			        continue;
			    }
			}
			//更新操作
			else{
				$add_list['model']=$this_model['model_name'];
				$map=array('field'=>$add_list['field'],'model'=>$this_model['model_name']);
				if (form_update($add_list,'fields',$map)) {
			        continue;
			    }
			}
		}

    	$this->redirect('System/Model/fields_manage',array('model'=>$this->THIS_MODEL['model_name'],'menu_id'=>$this->PUB_THISMENU['id'],'act_id'=>$_GET['act_id'],'id'=>$_GET['id']),0,'页面跳转中...');
    }

    public function fields_advance_manage(){
        $modelModel=M('model');
        $map1=array('id'=>I('get.id'));
        $this_model=$modelModel->where($map1)->find();
    	$fieldsModel=M('fields');
    	$map=array('model'=>$this_model['model_name'],'field'=>$_GET['field']);
    	$result=$fieldsModel->where($map)->find();
    	$form_model=Form();
        $fields=array(
                array('title'=>'默认值','plug'=>'form_text','field'=>'defaults','value'=>$result['defaults']),
                array('title'=>'插件配置','plug'=>'form_textarea','field'=>'config','value'=>$result['config']),
                array('title'=>'约束配置','plug'=>'form_textarea','field'=>'verify','value'=>$result['verify']),
                array('title'=>'帮助说明','plug'=>'form_text','field'=>'help_text','value'=>$result['help_text']),
            );
        $this->form=$form_model->user_form($fields);
        if (isset($_POST['submit'])) {
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'update',$map);
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            $this->redirect('System/Model/fields_manage',array('id'=>$_GET['id']),0,'页面跳转中...');
        }
        $this->display('System:result_fields_advance_manage');
    }


    public function model_delete(){
        // 根据模型查找，字段，先删除字段，然后删除模型
        $modelModel=M('model');
        $map1=array('id'=>I('get.id'));
        $this_model=$modelModel->where($map1)->find();
        $fieldsModel=M('fields');
        $map=array('model'=>$this_model['model_name']);
        // 删除字段信息
        $fieldsModel->where($map)->delete();
        // 删除模型信息
        $modelModel->where($map1)->delete();
        $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
        $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
    }

    public function fields_delete()
    {
      $modelModel=M('model');
      $this_model=$modelModel->where('id='.I('get.id'))->find();

      $map = array();
      $map['field']=I('get.field');
      $map['model']=$this_model['model_name'];
      if ($map) {
        M('fields')->where($map)->delete();
      }
      $this->redirect('System/Model/fields_manage',array('id'=>$_GET['id']),0,'页面跳转中...');
    }

    public function field_group(){
        // 过滤器
        $map=array();
        $map['model_id']=I('get.id');

        // 排序
        $orderby=false;

        // 定义显示页面
        $self_page="System:lists_section";

        // 定义操作
        $this->action_btn_add=auth_action('System/Model/fields_group_add');//增加操作
        // $action_btn_common[]=auth_action('Core/Room/room_owner_update');//公共操作 修改户主
        // $action_btn_common[]=auth_action('Core/Room/room_owner_delete');//公共操作 删除户主

        $action_btn_select[]=auth_action('System/Model/fields_group_update');//单项操作 修改户主
        $action_btn_select[]=auth_action('System/Model/fields_group_delete');//单项操作 删除户主
        $this->action_btn_select=$action_btn_select;

        // 返回链接
        $this->back_url=__ROOT__."/System/Model/lists";
        $this->lists($map,$orderby,$self_page);
    }

    public function fields_group_add()
    {
      $otdata=array();
      $otdata['model_id']=I('get.sec_id');

      $url="/System/Model/field_group?id=".I('get.sec_id');
      $this->back_url=__ROOT__.$url;
      $this->add($otdata,$url);
    }


    public function fields_group_update()
    {
      $otdata=array();
      $otdata['model_id']=I('get.sec_id');

      // 返回链接
      $url="/System/Model/field_group?id=".I('get.sec_id');
      $this->back_url=__ROOT__.$url;

      $this->update($otdata,false,$url);
    }




    public function fields_group_delete()
    {
      $url="/System/Model/field_group?id=".I('get.sec_id');
      $this->delete(false,$url);
    }





}
