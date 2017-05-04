<?php
namespace Customer\Controller;
use Think\Controller;
class UploadController extends Controller{
    public function _initialize(){
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
    	
    }

    public function upload(){
    	$upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	    $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件    
	    $info   =   $upload->upload();
	    if(!$info) {// 上传错误提示错误信息
	        $this->error($upload->getError());
	    }else{// 上传成功
	        $this->success('上传成功！');
	    }
    }
}
