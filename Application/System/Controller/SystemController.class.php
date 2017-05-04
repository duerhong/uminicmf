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
// 后台-重要控制器-基础操作管理
//----------------------------------

/*
公共变量命名规则
必须以PUB开头
如果是数组，后缀必须是_LIST
常用常量：
当前顶部菜单：         PUB_TOPMENU，
顶部菜单列表：         PUB_TOPMENU_LIST，
当前菜单：             PUB_THISMENU
当前同级菜单列表       PUB_MENU_LIST
// {$Think.MODULE_NAME}
// {$Think.CONTROLLER_NAME}
// {$Think.ACTION_NAME}
*/
// 公共方法：
// lists add  update  submit_update submit_add query delete 等方法
namespace System\Controller;
use Think\Controller;
class SystemController extends Controller {
    public function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        // 用户登录权限认证
        // 如果模块要加载登录认证，必须加载认证方法 user_auth();
        $is_login=user_auth();
        if (!$is_login) {
            $this->redirect('/Auth/Index/login','',2, '亲，还未登录,2s后跳转到登录界面');
        }

        // 获取节点URL，只用在侧栏
        $this->PUB_URL_NODE=$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME;

        // 获取节点
        $this->PUB_NODE=$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME;
        if (($Think.CONTROLLER_NAME =='Index' or $Think.CONTROLLER_NAME =='index') and ($Think.ACTION_NAME =='Index' or $Think.ACTION_NAME =='index')) {
            $this->PUB_NODE=$Think.MODULE_NAME;
        }

        // 公共判断，用户是否有该节点权限。有的话才能访问
        $this->thisNode=auth_action($this->PUB_NODE);
        if (!$this->thisNode) {
            $this->redirect('/System','',5, '对不起，你没有操作权限,5秒后跳转至首页...');
        }


        // 这里做路径导航
        $path_nav="";
        $path_nav='<li><a href="'.__ROOT__.'/System/Index/index">首页</a></li>';
        if ($Think.MODULE_NAME) {
            $thisdd=get_menu(array('node_name'=>$Think.MODULE_NAME),'find');
            $path_nav=$path_nav.'<li><a href="'.__ROOT__.'/'.$Think.MODULE_NAME.'">'.$thisdd['title'].'</a></li>';
        }
        if ($Think.CONTROLLER_NAME) {
            $thisdd=get_menu(array('node_name'=>$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME),'find');
            $path_nav=$path_nav.'<li><a>'.$thisdd['title'].'</a></li>';
        }

        if ($Think.ACTION_NAME) {
            $thisdd=get_menu(array('node_name'=>$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME),'find');
            $path_nav=$path_nav.'<li><a href="'.__ROOT__.'/'.$Think.MODULE_NAME.'/'.$Think.CONTROLLER_NAME.'/'.$Think.ACTION_NAME.'">'.$thisdd['title'].'</a></li>';
        }
        $this->path_nav=$path_nav;

        // 判断用户是否为配置文件中的超级管理员
        // 如果是配置文件中的超级管理员，才能访问其他模块，否则只能访问商户中心模块



        $map=array('type'=>'0');
        $this->PUB_TOPMENU_LIST=get_menu($map);

        $this->PUB_THISMENU=get_menu(array('node_name'=>$this->PUB_NODE),'find');//当前菜单

        // 当前顶部菜单
        $this->PUB_TOPMENU=get_top_menu($this->PUB_THISMENU);
        // 当前侧栏一级菜单列表
        $this->SEC_MENU=get_menu(array('pid'=>$this->PUB_TOPMENU['id'],'type'=>'1'));

        //节点所在的当前模型
        $this->THIS_MODEL=get_model($this->PUB_THISMENU['model']);




    }

    //获取用户节点，是否有继承节点，如果没有则跳转相应节点，如果有，则继承父类节点
    public function _empty(){
        if (isset($this->thisNode['inherit_node_name']) and $this->thisNode['inherit_node_name'] !="0") {
            $atingd=$this->thisNode['inherit_node_name'];
            $this->$atingd();
        }
    }


    public function index(){
        $log_map=array();
        if (!$_SESSION['user']['is_super']==1) {
          $log_map['user_id']=$_SESSION['user']['user_id'];
        }
        $this->log_lists=M('user_log')->where($log_map)->order('id desc')->limit('0,18')->select();
        $this->shortcut=M('shortcut')->limit(0,12)->select();
        $this->display('System:index');
    }

    // 过滤器，排序，自定义页面
    public function lists($map=false,$orderby=false,$self_page=false,$page_mun=10){
        $model_data=D($this->THIS_MODEL['table_name']);
        if (!$orderby) {
            if (isset($this->THIS_MODEL['default_order']) and !empty($this->THIS_MODEL['default_order'])) {
                $orderby=$this->THIS_MODEL['default_order'];
            } else {
                $orderby='orderid desc,id desc';
            }
        }
        if (!$map) {
            $map=array();
        }
        if (IS_POST) {

            // 当post为空的时候，应该删除该项
            foreach ($_POST as $pkey => $prow) {
                if (empty($prow)) {
                    unset($_POST[$pkey]);
                    continue;
                }
                $map[$pkey]=array('like',"%".$prow."%");
            }
        }
        if ($_GET['order_by']) {
          $orderby=str_replace('|',' ',$_GET['order_by']);
        }
        if ($_GET['filter']) {
          $f_list=explode('-',$_GET['filter']);
          foreach ($f_list as $fl_key => $fl_row) {
             $fr_map=explode('|',$fl_row);
             array_push($map,array($fr_map[0]=>$fr_map[1]));
          }
        }
        $count=$model_data->where($map)->count();
        $Page= new \Think\Page($count,$page_mun);// 实例化分页类 传入总记录数和每页显示的记录数
        $Page->lastSuffix = false;
        $Page->setConfig('first','首页');
        $Page->setConfig('last','尾页');
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $this->page = $Page->show();// 分页显示输出
        $this->list_data=$model_data->where($map)->order($orderby)->limit($Page->firstRow.','.$Page->listRows)->select();
        // echo $model_data->getLastSql();
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

        //增加操作
        $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_add_button=get_menu($add_map,'find');

        //更多公共操作
        $comm_map=array('type'=>5,'pid'=>$this->PUB_THISMENU['id']);
        $this->common_action_button=get_menu($comm_map,'select');



        if (!$self_page) {
            $this->displayAuto($this->PUB_THISMENU['id']);
        }
        else{
            $this->display($self_page);
        }

        // 这里设置
    }


    // 树形菜单，不分页
    // 必须字段 parent_id cat_name grade
    public function tree_lists(){
        $map=array();
        $this->catModel=M($this->THIS_MODEL['table_name']);
        $this->list_data=$this->catModel->where('parent_id=0')->order('id asc')->select();
        $art_str=toAry($this->THIS_MODEL['list_filed']);
        $this->title_lists=json_decode($art_str,true);
        // 获取当前菜单栏目所在的所有操作方法(比如修改 删除)
        $act_map=array('type'=>3,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_lists=get_menu($act_map);
        $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_add_button=get_menu($add_map,'find');
        $this->displayAuto($this->PUB_THISMENU['id']);
    }



    // 以字段model过滤
    public function filter_lists(){
        $model_data=D($this->THIS_MODEL['table_name']);
        // 搜索重组
        $map=array('model'=>$this->THIS_MODEL['model_name']);
        $this->list_data=$model_data->where($map)->select();
        $art_str=toAry($this->THIS_MODEL['list_filed']);
        $this->title_lists=json_decode($art_str,true);

        // 获取当前菜单栏目所在的所有操作方法(比如修改 删除)
        $act_map=array('type'=>3,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_lists=get_menu($act_map);

        $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_add_button=get_menu($add_map,'find');

        $this->displayAuto($this->PUB_THISMENU['id']);
    }

    public function add($otdata=false,$redirect_url=false){
        //save 自动生成。获取当前模型的save
        // 获取字段分组

        $this->field_group_list=M('field_group')->where('model_id='.$this->THIS_MODEL['id'])->select();

        $fieldsModel=M('fields');
        $form_model=Form($this->THIS_MODEL['model_name']);

        // 构建表单
        $this->form=$form_model->model_form(true,$this->THIS_MODEL['id']);
        // 构建表单验证
        $form_validate_model=FormValidate($this->THIS_MODEL['model_name']);
        $this->form_validate=$form_validate_model->model_form();



        // if ($otdata) {
        //     foreach ($otdata as $key => $row) {
        //         $_POST[$key]=$row;
        //     }
        // }
        if ($otdata) {
             $_POST=array_merge($_POST,$otdata);
        }
        if (isset($_POST['submit'])) {
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'add',$map);
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            if ($redirect_url) {
                $this->redirect($redirect_url,'',0,'页面跳转中...');
            }
            else{
                $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
            }

        }
        $this->displayAuto();
    }

    // 树栏目添加
    public function tree_add()
    {
        $otdata=false;
        if (isset($_POST['submit'])) {
            $res=M($this->THIS_MODEL['table_name'])->where('id='.$_POST['parent_id'])->find();
            $otdata['grade']=intval($res['grade'])+1;
        }
        $this->add($otdata);
    }

    //加入回收站
    public function recycle($map=false){
        $dataModel=M($this->THIS_MODEL['table_name']);
        if (isset($map['id'])) {
            unset($map['id']);
        }
        $map['id']=intval($_GET['id']);
        $dataModel->where($map)->save($data);
        action_log("回收",$this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],$map['id']);//执行add成功日志
        // 删除后提示操作成功还是失败。并且加入日志
        $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
        $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
    }

    public function delete($map=false,$redirect_url=false){
        $dataModel=M($this->THIS_MODEL['table_name']);
        // 删除前必须提示
        if (isset($map['id'])) {
            unset($map['id']);
        }
        $map['id']=intval($_GET['id']);
        $dataModel->where($map)->delete();
        action_log("删除",$this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],$map['id']);//执行add成功日志
        // 删除后提示操作成功还是失败。并且加入日志
        $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
        if ($redirect_url) {
            $this->redirect($redirect_url,'',0,'页面跳转中...');
        }
        else{
            $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
        }

    }

    public function delete_lists($map=false,$redirect_url=false)
    {
      $dataModel=M($this->THIS_MODEL['table_name']);
      $id_list=I('post.select_id');
      foreach ($id_list as $key => $row) {
        $map=array();
        if (isset($map['id'])) {
            unset($map['id']);
        }
        $map['id']=$row;
        $dataModel->where($map)->delete();
        action_log("删除",$this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],$map['id']);//执行add成功日志
      }
      // 删除后提示操作成功还是失败。并且加入日志
      $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
      if ($redirect_url) {
          $this->redirect($redirect_url,'',0,'页面跳转中...');
      }
      else{
          $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
      }
    }

    public function restore($data=false,$map=false){
        $dataModel=M($this->THIS_MODEL['table_name']);
        if (isset($map['id'])) {
            unset($map['id']);
        }
        $map['id']=intval($_GET['id']);
        $dataModel->where($map)->save($data);
        action_log("还原",$this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],$map['id']);
        // 删除后提示操作成功还是失败。并且加入日志
        $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
        $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
    }


    public function update($otdata=false,$map=false,$redirect_url=false){
        $dataModel=M($this->THIS_MODEL['table_name']);
        $data=$dataModel->where('id='.intval($_GET['id']))->find();
        if ($map) {
            $map['id'] = intval($_GET['id']);
        }
        $this->field_group_list=M('field_group')->where('model_id='.$this->THIS_MODEL['id'])->select();

        $form_model=Form($this->THIS_MODEL['model_name'],$data);
        $this->form=$form_model->model_form(true,$this->THIS_MODEL['id']);

        $form_validate_model=FormValidate($this->THIS_MODEL['model_name']);
        $this->form_validate=$form_validate_model->model_form();

        if ($otdata) {
             $_POST=array_merge($_POST,$otdata);
        }
        if (isset($_POST['submit'])) {
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'update',$map);
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            if ($redirect_url) {
                $this->redirect($redirect_url,'',0,'页面跳转中...');
            }
            else{
                $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
            }
        }
        $this->displayAuto();

    }








    public function submit_update(){
        if (form_verify($_POST)) {
            if (form_update($_POST,$this->THIS_MODEL['table_name'])) {
                $this->goList();
            }
            else{
                $this->goList();
            }
        }
    }


    public function goLogin(){
        $this->redirect('/New/category/cate_id/2','', 5, '页面跳转中...');
    }

    public function goHome(){
        $this->redirect('/New/category/cate_id/2','',5, '页面跳转中...');
    }

    public function displayAuto(){
        $map['node_name']=$this->PUB_NODE;
        $actgo=get_menu($map,'find');
        $this->display("System:".$actgo['template']);
    }

    public function tests(){
        $sql_data=M();
        $rescolumns = $sql_data->query("show tables");
        foreach ($rescolumns as $key => $row) {
            print_r($row);
            echo '<br />';
        }

    }


}
