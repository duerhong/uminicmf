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
// 上传插件
//----------------------------------
namespace Plugs\Controller;
use Think\Controller;
class UploadController extends Controller {
    public function _initialize(){
    	header("Content-Type:text/html; charset=utf-8");
		// maxSize	文件上传的最大文件大小（以字节为单位），0为不限大小
		// rootPath	文件上传保存的根路径
		// savePath	文件上传的保存路径（相对于根路径）
		// saveName	上传文件的保存规则，支持数组和字符串方式定义
		// saveExt	上传文件的保存后缀，不设置的话使用原文件后缀
		// replace	存在同名文件是否是覆盖，默认为false
		// exts	允许上传的文件后缀（留空为不限制），使用数组或者逗号分隔的字符串设置，默认为空
		// mimes	允许上传的文件类型（留空为不限制），使用数组或者逗号分隔的字符串设置，默认为空
		// autoSub	自动使用子目录保存上传文件 默认为true
		// subName	子目录创建方式，采用数组或者字符串方式定义
		// hash	是否生成文件的hash编码 默认为true
		// callback	检测文件是否存在回调，如果存在返回文件信息数组
		// 1.获取模型，获取字段，该字段为要反馈的字段
		// 2.根据字段属性 config

		 // config:配置
		 // exts:jpg,png,jpeg  图片属性为默认属性
		 // path:default


    }

    public function index(){
    	$this->display('./Public/plug/upload/upload.html');
    }

    public function upload(){

    	$upload = new \Think\Upload();// 实例化上传类

    	$fieldModel=M('fields');
    	$map=array('model'=>$_GET['model'],'field'=>$_GET['field']);
    	$field=$fieldModel->where($map)->find();
    	$config=json_decode(toAry($field['config']),true);

    	if (!isset($config['maxSize'])) {
    		$config['maxSize']='3145728';
    	}

    	if (!isset($config['exts'])) {
    		$config['exts']=array('jpg', 'gif', 'png', 'jpeg');
    	}
    	else{
    		$config['exts']=explode(',',$config['exts']);
    	}

    	if (!isset($config['path'])) {
    		$config['path']='uploads';
    	}
	    $upload->maxSize   =     $config['maxSize'];// 设置附件上传大小
	    $upload->exts      =     $config['exts'];// 设置附件上传类型
	    $upload->rootPath  =     './Public/Uploads/'.$config['path'].'/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    $upload->subName   =    array('date','Ymd');

	    $rdate=date("Ymd",time());
	    if (!is_dir('./Public/Uploads/'.$config['path'])) {
	    	mkdir('./Public/Uploads/'.$config['path']);
	    }
	    // 上传文件
	    $info   =   $upload->upload();
	    $toSavePath='/Public/Uploads/'.$config['path'].'/'.$rdate.'/'.$info['image']['savename'];
	    $imgshow="<img src='".__ROOT__.$toSavePath."' style='max-height:90px;' />";
	    if(!$info) {// 上传错误提示错误信息
	        $this->redirect('Plugs/upload/index',array('model'=>$_GET['model'],'field'=>$_GET['field']),2, '上传失败！ ');
	    }else{// 上传成功
	    	echo '
		    	<script language="javascript">
		    	parent.window.document.getElementById("file_'.$_GET['field'].'").value="'.$toSavePath.'";
		    	parent.window.document.getElementById("show_'.$_GET['field'].'").innerHTML="'.$imgshow.'";
				</script>
				';
	    	$this->redirect('Plugs/upload/index',array('model'=>$_GET['model'],'field'=>$_GET['field']),2, '上传成功！ ');
	    }
    }


    // 简单版本
    public function mini_upload(){
    	$upload = new \Think\Upload();// 实例化上传类


    	$config['maxSize']='3145728';
    	$config['exts']=array('jpg', 'gif', 'png', 'jpeg');
    	$config['path']='members';
	    $upload->maxSize   =     $config['maxSize'];// 设置附件上传大小
	    $upload->exts      =     $config['exts'];// 设置附件上传类型
	    $upload->rootPath  =     './Public/Uploads/'.$config['path'].'/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    $upload->subName   =    array('date','Ymd');

	    $rdate=date("Ymd",time());
	    if (!is_dir('./Public/Uploads/'.$config['path'])) {
	    	mkdir('./Public/Uploads/'.$config['path']);
	    }
	    // 上传文件
	    $info   =   $upload->upload();
	    $toSavePath='/Public/Uploads/'.$config['path'].'/'.$rdate.'/'.$info['image']['savename'];
	    $imgshow="<img src='".__ROOT__.$toSavePath."' style='max-height:90px;' />";
	    if(!$info) {// 上传错误提示错误信息
	        redirect('//Plugs/upload/index&field='.$_GET['field'],2, '上传失败！ ');
	    }else{// 上传成功
	    	echo '
		    	<script language="javascript">
		    	parent.window.document.getElementById("file_'.$_GET['field'].'").value="'.$toSavePath.'";
		    	parent.window.document.getElementById("show_'.$_GET['field'].'").innerHTML="'.$imgshow .'";
				</script>
				';
	    	redirect('/Plugs/upload/index&field='.$_GET['field'],2, '上传成功！');
	    }
    }
    //百度上传插件
    public function wp_upload($value='')
    {
    	$upload = new \Think\Upload();// 实例化上传类
    	$config['maxSize']='3145728'; //3M 单个图片大小
    	$config['exts']=array('jpg', 'gif', 'png', 'jpeg');
    	$config['path']='wp';
	    $upload->maxSize   =     $config['maxSize'];// 设置附件上传大小
	    $upload->exts      =     $config['exts'];// 设置附件上传类型
	    $upload->rootPath  =     './Public/Uploads/'.$config['path'].'/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    $upload->subName   =    array('date','Ymd');

	    $rdate=date("Ymd",time());
	    if (!is_dir('./Public/Uploads/'.$config['path'])) {
	    	mkdir('./Public/Uploads/'.$config['path']);
	    }
	    // 上传文件
	    $info   =   $upload->upload();
	    $toSavePath='/Public/Uploads/'.$config['path'].'/'.$rdate.'/'.$info['file']['savename'];
	    if(!$info) {// 上传错误提示错误信息
	        $this->error($upload->getErrorMsg());
	    }else{// 上传成功
			// 这里重组保存的路径
			$this->ajaxReturn($toSavePath,"JSON");
	    }
    }
}
