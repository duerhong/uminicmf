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
// 后台-菜单/节点管理
//----------------------------------
namespace System\Controller;
use Think\Controller;
class MenuController extends SystemController {
    public function lists_menu(){
        $model_data=D($this->THIS_MODEL['table_name']);
        // 搜索重组
        $this->list_data=$model_data->select();
        // 获取当前模型
        // 将单引号转化为双引号，将字符转化为数组
        // 树形菜单分三级 顶级 一级 二级。暂时没有无限级
        // 目前循环数据库中菜单表中所有菜单，有权限后循环赋值了权限的菜单
        // 导航菜单
        $node_map=array();
        $node_map['type']="0";
        $this->nav_menu=get_node_auth($node_map);

        $act_map=array('type'=>3,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_lists=get_node($act_map);

        $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_add=get_node($add_map,'find');

        $str=str_replace("'", '"', $this->THIS_MODEL['list_filed']);
        $this->title_lists=json_decode($str,true);
        $this->displayAuto($_GET['menu_id']);
    }


    public function submit_add(){
        if (form_verify($_POST)) {
            if (form_add($_POST,$this->THIS_MODEL['table_name'])) {
                $this->goList();
            }
            else{
                $this->goList();
            }
        }
    }

    //1:z增加一个顶级栏目。只有超级管理员才能添加
    //2:增加一个侧栏一级栏目（模型）
    //3：增加一个侧栏列表页
    //4.增加操作

    public function add_menu(){
        $fieldsModel=M('fields');
        $form_model=Form();
        $menupid=I('get.id');
        $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
        //模块，一级栏目 只有超级管理员才能添加顶级栏目的权利
        if ($_GET['act_type']=='1' and $_SESSION['user']['is_super']==1) {
            $fields=array(
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>0),
                array('title'=>'位置','plug'=>'form_hidden','field'=>'type','value'=>0),

                array('title'=>'标题','plug'=>'form_text','field'=>'title'),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>"System"),
                array('title'=>'url','plug'=>'form_text','field'=>'url','value'=>"0"),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>"glyphicon glyphicon-asterisk"),

                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['defaults']),


            );
        }
        //模型
        elseif ($_GET['act_type']=='2') {
            $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
            $fields=array(
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$menupid),
                array('title'=>'位置','plug'=>'form_hidden','field'=>'type','value'=>1),

                array('title'=>'标题','plug'=>'form_text','field'=>'title'),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>"Index"),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>"glyphicon glyphicon-menu-hamburger"),
                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['defaults']),


            );
        }
        //列表
        elseif ($_GET['act_type']=='3') {
            // $lists="lists:文字列表页
            // lists_ma:文字列表页（多操作）
            // lists_menu_tree:菜单列表
            // lists_region:地区列表
            // result:内容修改";

            $template_info=M('fields')->where('field="template" and model="menu"')->find();
            $inhert_node_info=M('fields')->where('field="inherit_node_name" and model="menu"')->find();

            $model_info=M('fields')->where('field="model" and model="menu"')->find();
            $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
            $fields=array(
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$menupid),
                array('title'=>'位置','plug'=>'form_hidden','field'=>'type','value'=>2),
                array('title'=>'标题','plug'=>'form_text','field'=>'title','value'=>$result['defaults']),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>"lists"),
                array('title'=>'继承节点','plug'=>'form_select','field'=>'inherit_node_name','config'=>$inhert_node_info['config']),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>"glyphicon glyphicon-list-alt"),

                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['defaults']),
                array('title'=>'模板页面','plug'=>'form_select','field'=>'template','config'=>$template_info['config']),
                array('title'=>'数据模型','plug'=>'form_foreign_select','field'=>'model','config'=>$model_info['config']),


            );
        }
        //操作
        elseif ($_GET['act_type']=='4') {
            $type_info=M('fields')->where('field="type" and model="menu"')->find();
            $template_info=M('fields')->where('field="template" and model="menu"')->find();
            $inhert_node_info=M('fields')->where('field="inherit_node_name" and model="menu"')->find();
            $model_info=M('fields')->where('field="model" and model="menu"')->find();
            $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
            $fields=array(
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$menupid),
                array('title'=>'位置','plug'=>'form_select','field'=>'type','config'=>$type_info['config']),
                array('title'=>'标题','plug'=>'form_text','field'=>'title','value'=>$result['defaults']),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>"lists"),
                array('title'=>'继承节点','plug'=>'form_select','field'=>'inherit_node_name','config'=>$inhert_node_info['config']),
                array('title'=>'模板页面','plug'=>'form_select','field'=>'template','config'=>$template_info['config']),
                array('title'=>'数据模型','plug'=>'form_foreign_select','field'=>'model','config'=>$model_info['config']),
                array('title'=>'前置函数','plug'=>'form_text','field'=>'pre_func','value'=>$result['defaults']),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>"glyphicon glyphicon-list-alt"),
                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['defaults']),

            );
        }

        $this->form=$form_model->user_form($fields);
        // $this->form=$form_model->model_form();
        if (isset($_POST['submit'])) {
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'add');
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
        }
        $this->display("System:result_menu");

    }




    public function update_menu(){
        $fieldsModel=M('fields');
        $form_model=Form();
        $menupid=I('get.id');
        $result=get_menu(array('id'=>$menupid),'find');
        $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
        //模块，一级栏目 只有超级管理员才能添加顶级栏目的权利
        if ($_GET['act_type']=='1' and $_SESSION['user']['is_super']==1) {
            $fields=array(
                array('title'=>'主键','plug'=>'form_hidden','field'=>'id','value'=>$result['id']),
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$result['pid']),
                array('title'=>'位置','plug'=>'form_hidden','field'=>'type','value'=>$result['type']),

                array('title'=>'标题','plug'=>'form_text','field'=>'title','value'=>$result['title']),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>$result['node_name']),
                array('title'=>'url','plug'=>'form_text','field'=>'url','value'=>$result['url']),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>$result['img']),
                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['rules']),
            );
        }
        //模型
        elseif ($_GET['act_type']=='2') {
            $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
            $fields=array(
                array('title'=>'主键','plug'=>'form_hidden','field'=>'id','value'=>$result['id']),
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$result['pid']),
                array('title'=>'位置','plug'=>'form_hidden','field'=>'type','value'=>$result['type']),

                array('title'=>'标题','plug'=>'form_text','field'=>'title','value'=>$result['title']),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>$result['node_name']),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>$result['img']),
                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['rules']),

            );
        }
        //列表
        elseif ($_GET['act_type']=='3') {
            // $lists="lists:文字列表页
            // lists_ma:文字列表页（多操作）
            // lists_menu_tree:菜单列表
            // lists_region:地区列表
            // result:内容修改";

            $template_info=M('fields')->where('field="template" and model="menu"')->find();
            $inhert_node_info=M('fields')->where('field="inherit_node_name" and model="menu"')->find();
            $model_info=M('fields')->where('field="model" and model="menu"')->find();
            $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
            $fields=array(
                array('title'=>'主键','plug'=>'form_hidden','field'=>'id','value'=>$result['id']),
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$result['pid']),
                array('title'=>'位置','plug'=>'form_hidden','field'=>'type','value'=>$result['type']),
                array('title'=>'标题','plug'=>'form_text','field'=>'title','value'=>$result['title']),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>$result['node_name']),
                array('title'=>'继承节点','plug'=>'form_select','field'=>'inherit_node_name','config'=>$inhert_node_info['config'],'value'=>$result['inherit_node_name']),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>$result['img']),

                array('title'=>'模板页面','plug'=>'form_select','field'=>'template','config'=>$template_info['config'],'value'=>$result['template']),
                array('title'=>'数据模型','plug'=>'form_foreign_select','field'=>'model','config'=>$model_info['config'],'value'=>$result['model']),
                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['rules']),
            );
        }
        //操作
        elseif ($_GET['act_type']=='4') {
            $type_info=M('fields')->where('field="type" and model="menu"')->find();
            $template_info=M('fields')->where('field="template" and model="menu"')->find();
            $inhert_node_info=M('fields')->where('field="inherit_node_name" and model="menu"')->find();
            $model_info=M('fields')->where('field="model" and model="menu"')->find();
            $rules_info=M('fields')->where('field="rules" and model="menu"')->find();
            $fields=array(
                array('title'=>'主键','plug'=>'form_hidden','field'=>'id','value'=>$result['id']),
                array('title'=>'上一级','plug'=>'form_hidden','field'=>'pid','value'=>$result['pid']),
                array('title'=>'位置','plug'=>'form_select','field'=>'type','config'=>$type_info['config'],'value'=>$result['type']),
                array('title'=>'标题','plug'=>'form_text','field'=>'title','value'=>$result['title']),
                array('title'=>'节点','plug'=>'form_text','field'=>'node_name','value'=>$result['node_name']),
                array('title'=>'继承节点','plug'=>'form_select','field'=>'inherit_node_name','config'=>$inhert_node_info['config'],'value'=>$result['inherit_node_name']),
                array('title'=>'模板页面','plug'=>'form_select','field'=>'template','config'=>$template_info['config'],'value'=>$result['template']),
                array('title'=>'数据模型','plug'=>'form_foreign_select','field'=>'model','config'=>$model_info['config'],'value'=>$result['model']),
                array('title'=>'前置函数','plug'=>'form_text','field'=>'pre_func','value'=>$result['pre_func']),
                array('title'=>'图标','plug'=>'form_text','field'=>'img','value'=>$result['img']),
                array('title'=>'规则','plug'=>'form_foreign_select','config'=>$rules_info['config'],'field'=>'rules','value'=>$result['rules']),
            );
        }

        $this->form=$form_model->user_form($fields);
        // $this->form=$form_model->model_form();
        if (isset($_POST['submit'])) {
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'update');
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
        }
        $this->display("System:result_menu");

    }





}
