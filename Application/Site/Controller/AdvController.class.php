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
// 后台-广告管理
//----------------------------------
namespace Site\Controller;
use Think\Controller;
// 跨模块继承
use System\Controller\SystemController;
class AdvController extends SystemController{
    public function index(){
      $this->display();
    }

    public function adv_lists()
    {
        $this->add_adv_btn=auth_action('Site/Adv/adv_add');
        $this->update_adv_btn=auth_action('Site/Adv/adv_update');
        $this->delete_adv_btn=auth_action('Site/Adv/adv_delete');

        $map['adv_type'] = intval($_GET['id']);
        $orderby=false;
        $self_page='Site:adv_lists';
        $this->list_img_show = 'img';
        $this->lists($map,$orderby,$self_page);

    }

    public function adv_add(){
        $redirect_url='/Site/Adv/adv_lists&id='.I('get.adv_id');
        $otdata['adv_type']=intval(I('get.adv_id'));
        $this->add($otdata,$redirect_url);

    }
    public function adv_update()
    {
        $redirect_url='/Site/Adv/adv_lists&id='.I('get.adv_id');
        $this->update(false,false,$redirect_url);
    }

    public function adv_delete()
    {
        $redirect_url='/Site/Adv/adv_lists&id='.I('get.adv_id');
        $this->delete(false,false,$redirect_url);
    }

}
