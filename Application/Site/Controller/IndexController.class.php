<?php
namespace Site\Controller;
use Think\Controller;
// 跨模块继承
use System\Controller\SystemController;
class IndexController extends SystemController{
    public function index(){
        $log_map=array();
        $log_map['user_id']=4;
        $this->log_lists=M('user_log')->where($log_map)->order('id desc')->limit('0,4')->select();
        $this->display('Merchants:index');
    }

    public function menu()
    {
        $model_data=D($this->THIS_MODEL['table_name']);
        if (!$orderby) {
            if (isset($this->THIS_MODEL['default_order']) and !empty($this->THIS_MODEL['default_order'])) {
                $orderby=$this->THIS_MODEL['default_order'];
            } else {
                $orderby='orderid asc,id asc';
            }
        }
        if (!$map) {
            $map=array();
        }
        if ($_POST['submit']) {
            // 当post为空的时候，应该删除该项
            foreach ($_POST as $pkey => $prow) {
                if (empty($prow)) {
                    unset($_POST[$pkey]);
                    continue;
                }
                $map[$pkey]=array('like',$prow);
            }
        }

        $this->list_data=$model_data->where($map)->order($orderby)->select();
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

        $add_map=array('type'=>4,'pid'=>$this->PUB_THISMENU['id']);
        $this->action_add_button=get_menu($add_map,'find');



        if (!$self_page) {
            $this->displayAuto($this->PUB_THISMENU['id']);
        }
        else{
            $this->display($self_page);
        }

    }

    public function manage_content()
    {
        // 操作按钮定义
        $this->add_adv_btn=auth_action('Site/Index/manage_content_add');
        $this->update_adv_btn=auth_action('Site/Index/manage_content_update');
        $this->delete_adv_btn=auth_action('Site/Index/manage_content_delete');

        // 路径导航定义
        $this->path="<a>首页</a> > <a>站点菜单</a> > 关于我们";

        $this->m_menu=M('m_menu')->where('id='.intval($_GET['id']))->find();
        $map['menu_id'] = intval($_GET['id']);
        $orderby=false;
        $self_page='Site:manage_content';
        $this->THIS_MODEL=get_model_id($this->m_menu['model']);
        $this->lists($map,$orderby,$self_page);
    }

    public function manage_content_add(){
        $this->m_menu=M('m_menu')->where('id='.intval($_GET['menu_id']))->find();
        $this->THIS_MODEL=get_model_id($this->m_menu['model']);

        $redirect_url='/Site/Index/manage_content&id='.I('get.menu_id');
        $otdata['menu_id']=intval(I('get.menu_id'));
        $this->add($otdata,$redirect_url);

    }
    public function manage_content_update()
    {
        $this->m_menu=M('m_menu')->where('id='.intval($_GET['menu_id']))->find();
        $this->THIS_MODEL=get_model_id($this->m_menu['model']);
        $otdata['menu_id']=intval(I('get.menu_id'));
        $redirect_url='/Site/Index/manage_content&id='.I('get.menu_id');
        $this->update($otdata,false,$redirect_url);
    }

    public function manage_content_delete()
    {
        $this->m_menu=M('m_menu')->where('id='.intval($_GET['menu_id']))->find();
        $this->THIS_MODEL=get_model_id($this->m_menu['model']);
        $redirect_url='/Site/Index/manage_content&id='.I('get.menu_id');
        $this->delete(false,false,$redirect_url);

    }

    // 增加子栏目
    function add_under_menu()
    {
        $otdata['parent_id']=intval(I('get.id'));
        $this->add($otdata);
    }



}
