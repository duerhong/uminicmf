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
// 后台-用户管理
//----------------------------------

// 方法命名规则：
// 方法名称_方法属性 比如
// user_add,user_update,user_lists,user_submit
//
namespace System\Controller;
use Think\Controller;
class UserController extends SystemController {
    // 授权首页：未定义
    public function index(){
    	$this->show('user');
    }

    //授权列表
    public function user_auth_list(){
        $this->lists();
    }

    //增加授权
    public function auth_group_add(){
        $auth_map['type']="0";
        $this->nav_menu=get_node_auth($auth_map);
        if (isset($_POST['submit'])) {
            // 预处理post数据
            $rules=implode(',', $_POST['rules']);
            $_POST['rules']=$rules;
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'add');
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
        }
        $this->display("System:result_auth_group");
    }

    //更新授权
    public function auth_group_update(){
        $fieldsModel=M('auth_group');
        $this->data=$fieldsModel->where('id='.I('get.id'))->find();
        $node_list=explode(',',$this->data['node']);
        $auth_map['type']="0";
    	$this->nav_menu=get_node_auth($auth_map);

        if (isset($_POST['submit'])) {
            $rules=implode(',', $_POST['rules']);
            $_POST['rules']=$rules;
            sys_save($this->THIS_MODEL['table_name'],$this->THIS_MODEL['model_name'],'update');
            $up_node=get_menu(array('id'=>$this->PUB_THISMENU['pid']),'find');//当前栏目上一级
            $this->redirect('/'.$up_node['node_name'],'',0,'页面跳转中...');
        }
    	$this->display("System:result_auth_group");
    }

    // 更新
    function user_update()
    {
      $otdata=false;
      if ($_POST) {
        $umap=array();
        $umap['username']=I('post.username');
        $user=M('user')->where($umap)->find();
        if ($user['id']!=I('get.id')) {
          alert('用户名输入有误！');
          goback();
        }
        $otdata=array();
        $pwd=I('post.new_password');
        if (isset($pwd) and !empty($pwd)) {
            $otdata['password']=md5($pwd);
        }

      }
      $this->update($otdata);
    }

    //新增
    function user_add()
    {
      if ($_POST) {
        $umap=array();
        $umap['username']=I('post.username');
        $exist_user=M('user')->where($umap)->find();
        if ($exist_user) {
          alert('用户名重复！');
          goback();
        }
        $otdata=array();
        $pwd=I('post.new_password');
        if (isset($pwd) and !empty($pwd)) {
            $otdata['password']=md5($pwd);
        }
      }
      $this->add($otdata);
    }
}
