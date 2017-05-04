<?php 
/**
* 自定义构建表单
*/
namespace Vendor\Mylib;
class UserForm{
	function __construct()
	{
		// 设置为静态变量，外部可以操作
		$this->name=$this->create_name();
		$this->action=$this->create_action();
		$this->class=$this->create_class();
		$this->method=$this->create_method();
		$this->fields=$this->create_fields();
		$this->submit=$this->create_submit();

	}

	function create_form(){
		$form="";
		$form=$form."<form name='".$this->name."' action='".$this->action."' class='".$this->class."' method='".$this->method."'>";
		$form=$form.$this->fields;
		$form=$form.$this->submit;
		$form=$form."</form>";
		return $form;
	}

	function create_action(){
		return "";
	}

	function create_name(){
		return "form";
	}

	function create_class(){
		return "userform";
	}

	function create_method(){
		return "POST";
	}

	function create_fields(){
		return "";
	}

	function create_submit(){
		return '
		<div class="form-group">
      	<label class="col-sm-2 control-label"></label>
      	<div class="col-sm-6">
      	  <input type="submit" class="form-submit" value="提交">
     	 	</div>
			</div>
		';
	}

}
// $form=new Form('menu');
	
?>