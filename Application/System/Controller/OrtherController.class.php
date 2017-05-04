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
// 后台-其他-管理
//----------------------------------
namespace System\Controller;
use Think\Controller;
class OrtherController extends SystemController {
    public function index(){
    	$this->display();
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


    public function site($value='')
    {
        $this_site=M('site')->find();
        $redirect_url="/System/Orther/site";
        if (isset($this_site) and !empty($this_site)) {
            if (!isset($_GET['id']) and empty($_GET['id'])) {
                $redirect_url="/System/Orther/site&id=".intval($this_site['id']);
                $this->redirect($redirect_url);
            }
            $this->update(false,false,$redirect_url);
        }
        else{
            $otdata=false;
            $this->add($otdata,$redirect_url);
        }
    }

    



}
