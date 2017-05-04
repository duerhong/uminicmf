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
// 后台-站点-导航管理
//----------------------------------
namespace System\Controller;
use Think\Controller;
class NavController extends SystemController {
    public function m_lists(){
        $map=array();
        $this->catModel=M('m_menu');
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

    public function m_menu_add(){
        $otdata=false;
        if (isset($_POST['submit'])) {
            $res=M('m_menu')->where('id='.$_POST['parent_id'])->find();
            $otdata['grade']=intval($res['grade'])+1;
        }
        $this->add($otdata);
    }

    public function m_menu_update(){
        $otdata=false;
        if (isset($_POST['submit'])) {
            $res=M('m_menu')->where('id='.$_POST['parent_id'])->find();
            $otdata['grade']=intval($res['grade'])+1;
        }
        $this->update($otdata);
    }   
}



