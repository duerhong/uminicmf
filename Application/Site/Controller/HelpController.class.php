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
// 后台-站点-帮助文档管理
//----------------------------------
namespace Site\Controller;
use Think\Controller;
// 跨模块继承
use System\Controller\SystemController;
class HelpController extends SystemController{
    public function index(){
      $this->display();
    }

    public function posts_lists()
    {
        $this->add_btn=auth_action('Site/Help/posts_add');
        $this->update_btn=auth_action('Site/Help/posts_update');
        $this->delete_btn=auth_action('Site/Help/posts_delete');

        $map['sort_id'] = intval($_GET['id']);
        $orderby=false;
        $self_page='Site:self_lists';
        $this->lists($map,$orderby,$self_page);
    }

    public function posts_add(){
        $redirect_url='/Site/Help/posts_lists&id='.I('get.sort_id');
        $otdata['sort_id']=intval(I('get.sort_id'));
        $this->add($otdata,$redirect_url);

    }
    public function posts_update()
    {
        $redirect_url='/Site/Help/posts_lists&id='.I('get.sort_id');
        $this->update(false,false,$redirect_url);
    }

    public function posts_delete()
    {
        $redirect_url='/Site/Help/posts_lists&id='.I('get.sort_id');
        $this->delete(false,false,$redirect_url);
    }



    public function help_sort_add()
    {
        $this->tree_add();
    }

    public function help_sort()
    {
        $this->tree_lists();
    }

}
