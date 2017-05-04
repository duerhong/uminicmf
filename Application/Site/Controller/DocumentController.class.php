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
// 后台-站点-文档管理
//----------------------------------
namespace Site\Controller;
use Think\Controller;
// 跨模块继承
use System\Controller\SystemController;
class DocumentController extends SystemController {
    public function index(){
    	$this->display();
    }


    public function graph(){
    	$this->display("Temp:graph");
    }



    public function gallery(){
    	$this->display("Temp:graph");
    }

    public function toajax(){

    }
}
