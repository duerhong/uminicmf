<?php
namespace System\Controller;
use Think\Controller;
class IndexController extends SystemController {
    public function index(){
      $log_map=array();
      if (!$_SESSION['user']['is_super']==1) {
        $log_map['user_id']=$_SESSION['user']['user_id'];
      }

      $this->log_lists=M('user_log')->where($log_map)->order('id desc')->limit('0,18')->select();
      $this->shortcut=M('shortcut')->limit(0,12)->select();
      $this->display('System:index');
    }


    public function region_lists(){
        $model_data=D("nation");
        // 搜索重组
        $this->list_data=$model_data->select();
        // 获取当前模型
        // 将单引号转化为双引号，将字符转化为数组
        // 树形菜单分三级 顶级 一级 二级。暂时没有无限级
        // 目前循环数据库中菜单表中所有菜单，有权限后循环赋值了权限的菜单
        // 导航菜单
        $this->nav_menu=get_region_node('pid=1');

        $act_map=array('type'=>3,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_lists=get_node($act_map);

        $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_add=get_node($add_map,'find');

        $str=str_replace("'", '"', $this->THIS_MODEL['list_filed']);
        $this->title_lists=json_decode($str,true);
        $this->displayAuto($_GET['menu_id']);
    }
}
