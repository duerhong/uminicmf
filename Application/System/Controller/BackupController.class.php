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
// 后台-站点-数据库备份
//----------------------------------
namespace System\Controller;
use Think\Controller;
class BackupController extends SystemController {
    public function index(){
    	$this->show('hello world!');
    }

    public function lists(){
    	$this->show('对不起，该功能暂未开放，请升级系统到最新版本或者联系管理员，管理员联系电话:<i>18993068696</i>,或者返回<a href="http://localhost:8088/index.php?">首页</a>');
    }


    
}
