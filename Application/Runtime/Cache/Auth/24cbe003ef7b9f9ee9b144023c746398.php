<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta http-equiv="X-UA-Compatible" content="IE=edge">


<title><?php echo ($site_info['site_title']); ?> </title>
<link href="/system/Public/boot/css/bootstrap.min.css" rel="stylesheet">
<link href="/system/Public/<?php echo C(DEFAULT_THEME);?>/Static/css/base.css" rel="stylesheet" type="text/css" media="screen">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="http://cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="http://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!--[if lt IE 8]>
  <link href="/Public/<?php echo C(DEFAULT_THEME);?>/css/ie.css" rel="stylesheet" type="text/css" media="screen">
<![endif]-->

</head>
<body>
<div class="lgbg">
	<div class="login">
	    <form method="post" action="<?php echo U('Auth/Index/login_auth');?>">
	        <h4 class="nomargin">系统登录</h4>
	        <p class="mt5 mb20"><em>欢迎使用<?php echo ($site_info['site_title']); ?></em></p>

	        <input type="text" class="form-control" placeholder="账号" name="username">
	        <br />
	        <input type="password" class="form-control" placeholder="密码" name="password">
	        <br />
	        <a href=""><small>忘记密码？</small></a>你可以
					<a href=""><small>联系管理员</small></a>
	        <br /><br />
	        <button class="btn btn-success btn-block">登录</button>

	    </form>
	</div>
</div>







<div class="cropy">开发版权归UminiCmf团队所有</div>


<script src="http://cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

</body>
</html>