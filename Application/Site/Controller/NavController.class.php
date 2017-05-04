<?php
namespace Site\Controller;
use Think\Controller;
// 跨模块继承
use System\Controller\SystemController;
class NavController extends SystemController {
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
    	$data['age']='18';
    	$this->ajaxReturn($data);
    }
}
