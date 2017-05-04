<?php
/**
*
除了通过启用模型自动构造表单可以创建表单外，也可以自定义构造表单
自定义构造方案： verify
1.可以自定义一个表单插件，比如 上传图片
2.可以自定义整个表单，包含form 标签
*/
// namesapce \Vender\Mylib;
namespace Vendor\Mylib;
class FormValidate{
	function __construct($preData,$model_name)
	{
		$this->model_name=$model_name;
		$this->preData=$preData;
	}

	// 1.引入模型 2.引入验证js代码
	//（参数:模型，分组）
	function model_form(){
		$plugs='
		<script type="text/javascript" src="'.__ROOT__.'/Public/plug/jquery-validation/jquery.validate.min.js" charset="utf-8"></script>
		<script type="text/javascript">

		$("#form_'.$this->model_name.'").validate({
		';
		$plugs=$plugs.$this->validate().$this->validate_info();
		$plugs=$plugs.'
		});
		</script>
		';
		return $plugs;
	}
	public function validate()
	{
		// print_r($this->preData);
		$validate="rules: {
			";
		foreach ($this->preData as $key => $row) {
			if (!isset($row["verify"]) or $row["verify"]=='') {
				continue; //如果为空 则不执行验证
			}

			else{
				$validate=$validate.$row['field'].':{
					';
				// 将约束改为
				$array = str_replace("\r","",$row['verify']);
				$array1 = str_replace("\n","",$array);
				$art_str = explode("#",$array1);
				// print_r($art_str);
				foreach ($art_str as $key_str => $row_str) {
					$str=explode('|', $row_str);
					$validate=$validate.$str[0].':'.$str[1].',
					';
				}
				$validate=$validate.'},
				';

			}



		}
		$validate=$validate."},";
		return $validate;
	}
	public function validate_info()
	{
		$validate="
		messages:{
			";
		foreach ($this->preData as $key => $row) {
			if (!isset($row["verify"]) or $row["verify"]=='') {
				continue; //如果为空 则不执行验证
			}

			else{
				$validate=$validate.$row['field'].':{
					';
				// 将约束改为
				$array = str_replace("\r","",$row['verify']);
				$array1 = str_replace("\n","",$array);
				$art_str = explode("#",$array1);

				foreach ($art_str as $key_str => $row_str) {
					$str=explode('|', $row_str);
					$validate=$validate.$str[0].':"'.$str[2].'",
					';
				}
				$validate=$validate.'},
				';

			}



		}
		$validate=$validate."},";
		return $validate;
	}
}
?>
