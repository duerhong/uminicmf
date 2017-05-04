<?php
/*

*/
namespace Vendor\Mylib;
class DbValidate{
	function __construct()
	{
	}
	// 必填
  function v_required($value,$limit=true)
  {
		if ($limit) {
			if (isset($value) and !empty($value)) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
  }

	// 邮箱
	public function v_email($value,$limit=true)
	{
		if ($limit) {
			if (strlen($value)==0 or $this->is_email($value)) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}

	// 手机号码
	public function v_mobile($value,$limit=true)
	{
		if ($limit) {
			if (strlen($value)==0 or  $this->is_mobile($value)) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}




	// url
	public function v_url($value,$limit=true)
	{
		if ($limit) {
			if (strlen($value)==0 or $this->is_url($value)) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}

	// 是否数字
	public function v_number($value,$limit=true)
	{
		if ($limit) {
			if (strlen($value)==0 or is_numeric($value)) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}

	// 是否整数
	public function v_digits($value,$limit=true)
	{
		if ($limit) {
			if (strlen($value)==0 or is_digits($value)) {
				return true;
			}
			else{
				return false;
			}
		}
		else{
			return true;
		}
	}
	//检测合法后缀名
	public function v_accept($value,$limit=true)
	{
		$list=explode('.',$value);
		$ext=end($list);
		$extdot='.'.$ext;
		if (strlen($value)==0 or $limit==$ext or $limit==$extdot) {
			return true;
		}
		else{
			return false;
		}
	}

	// 字符最大长度 只判断英文 无法验证中文
	public function v_maxlength($value,$limit=true)
	{
	  $len=strlen($value);
	  if (strlen($value)==0 or $len<=$limit) {
	    return true;
	  }
	  else{
	    return false;
	  }
	}
	// 最小字符长度 注意只判断英文，数字。无法验证中文
	public function v_minlength($value,$limit=true)
	{
	  $len=strlen($value);
	  if (strlen($value)==0 or $len>=$limit) {
	    return true;
	  }
	  else{
	    return false;
	  }
	}

	// 输入值长度区间
	public function v_rangelength($value,$limit=true)
	{
		$limit=str_replace('[','',$limit);
		$limit=str_replace(']','',$limit);
	  $list=explode(',',$limit);
	  $len=strlen($value);
	  $min=$list[0];
	  $max=$list[1];
		// echo $len.'|'.$min.'|'.$max;
		// exit();
	  if ($len==0 or ($len>=$min and $len<=$max)) {
	    return true;
	  }
	  else{
	    return false;
	  }
	}
	// 输入值区间
	function v_range($value,$limit=true)
	{
		$limit=str_replace('[','',$limit);
		$limit=str_replace(']','',$limit);
	  $list=explode(',',$limit);
	  $min=$list[0];
	  $max=$list[1];
	  if (strlen($value)==0 or ($value>=$min and $value<=$max)) {
	    return true;
	  }
	  else{
	    return false;
	  }
	}

	// 输入的最大值
	function v_max($value,$limit=true)
	{
	  if (strlen($value)==0 or $value<=$limit) {
	    return true;
	  }
	  else{
	    return false;
	  }
	}

	// 输入的最小值
	function v_min($value,$limit=true)
	{
		if (strlen($value)==0 or $value>=$limit) {
			return true;
		}
		else{
			return false;
		}
	}


	// ------------------ -公用验证-----=========================--------
	// 是否邮箱
	function is_email($email)
	{
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		 {
		 	return false;
		 }
		else
		 {
		 	return true;
		 }
	}
	//验证手机号码
	function is_mobile($str){
		if(preg_match("/1[3458]{1}\d{9}$/",$str)){
	    return true;
		}else{
		  return false;
		}
	}

	//验证url地址
	function is_url($str){
	  return preg_match("/^https:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\"])*$/", $str) or preg_match("/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\’:+!]*([^<>\"])*$/", $str);
	}


	function is_date($date)
	{
	    //匹配日期格式
	    if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
	    {
	        //检测是否为日期
	        if(checkdate($parts[2],$parts[3],$parts[1])){
	          return true;
	        }
	        else{
	            return false;
	        }
	    }
	    else{
	      return false;
	    }
	}

	function is_digits($str){
		if (is_numeric($str)) {
		  if (!strstr($str, '.')) {
		    return true;
		  }
	    else{
	      return false;
	    }
		}
	  else{
	    return false;
	  }
	}

	//检测后缀名 explode
	public function is_ext($str)
	{
		$file_list=explode('.');
	}






}
?>
