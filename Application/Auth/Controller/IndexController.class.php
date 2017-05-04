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
// UminiCmf用户登录，授权
//----------------------------------

namespace Auth\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function _initialize(){
    	header("Content-Type:text/html; charset=utf-8");
      $this->site_info=M('site')->find();
    }

    public function index()
    {
      $this->redirect('/Auth/Index/login','',0,'');
    }
    //登录界面
	public function login(){
    	$this->display("Public:login");
    }

    //登出界面
    public function logout(){
    	unset($_SESSION['user']);
    	$this->redirect('/Auth/Index/login','',2, '注销成功');
    }

    // 登录认证：登录判断，以及赋值+授权
    public function login_auth(){
         $userData=M('user');
         $username=I('post.username');
         $password=I('post.password');
         $password=md5($password);
         $map=array('username'=>$username,'password'=>$password);
         $user=$userData->where($map)->find();

         $first_module=false;
         if ($user) {
            // 登录后，保存用户信息，并且分配权限
             $_SESSION['user']['username']=$user['username'];
             $_SESSION['user']['nickname']=$user['nickname'];
             $_SESSION['user']['is_super']=$user['is_super'];
             $_SESSION['user']['user_id']=$user['id'];
             $auth_group=explode(',',$user['auth_group']);
             $auth_group_data=M('auth_group');

             $auth_list=array();
             if ($_SESSION['user']['is_super']==1) {
                 $first_module='System';
                 $auth_list=array();
             }
             else{
                 foreach ($auth_group as $key => $row) {
                     $auth=$auth_group_data->where('id='.$row)->find();
                     $auth_list=array_merge($auth_list,explode(',',$auth['rules']));
                 }
             }
             $_SESSION['auth_menu']=$auth_list;

             // 获取用户的第一个模块
             $node_list=M('menu')->where('type=0')->select();
             foreach ($node_list as $key => $row) {
                 if (in_array($row['id'], $auth_list)) {
                    $first_module=$row['node_name'];
                    break;
                 }
             }

             if ($first_module) {
                action_log('登录成功');
                $this->redirect('/'.$first_module,'',0, '登录成功');
             }
             else{
                unset($_SESSION['user']);
                $this->redirect('/Auth/Index/login','',3, '登录失败，该用户没有任何模块被授权,3s后跳转到登录界面');
             }


         }
         else{
            $this->redirect('/Auth/Index/login','',2, '亲，用户名或者密码错误,2s后跳转到登录界面');
         }
    }
}
