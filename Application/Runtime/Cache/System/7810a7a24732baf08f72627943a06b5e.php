<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  

  <title>UminiCmf内容管理框架:  </title>
  <link href="/system/Public/boot/css/bootstrap.min.css" rel="stylesheet">
  <link href="/system/Public/<?php echo C(DEFAULT_THEME);?>/Static/css/base.css" rel="stylesheet" type="text/css" media="screen">

  <link href="/system/Public/<?php echo C(DEFAULT_THEME);?>/Static/css/myboot.css" rel="stylesheet" type="text/css" media="screen">
  <link rel="stylesheet" type="text/css" href="/system/Public/plug/datetimepicker/jquery.datetimepicker.css"/>
  <link href="/system/Public/icon/iconfont.css" rel="stylesheet">


  <script src="/system/Public/boot/js/jquery.js"></script>

  <script src="/system/Public/plug/datetimepicker/build/jquery.datetimepicker.full.js"></script>
  <script>
  $.datetimepicker.setLocale('zh');
  </script>
  <?php
 if(strpos($HTTP_SERVER_VARS[HTTP_USER_AGENT], "MSIE 8.0")) { echo ' <link href="/system/Public/<?php echo C(DEFAULT_THEME);?>/Static/css/ie.css" rel="stylesheet" type="text/css" media="screen">'; } ?>
  
</head>

<body>



<!-- 头部功能菜单 -->
<div class="top">

<!-- 必要时，可以替换header -->


<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#">UminiCMF内容管理框架</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">

        <?php if($PUB_TOPMENU_LIST != 0): if(is_array($PUB_TOPMENU_LIST)): foreach($PUB_TOPMENU_LIST as $key=>$row): if($row['url'] != '0'): ?><li>
                    <a class="model_name" href="<?php echo ($row['url']); ?>">
                      <span class="<?php echo ($row['img']); ?>" aria-hidden="true"></span>
                      &nbsp;&nbsp;<?php echo ($row['title']); ?>
                    </a>
                  </li>
                <?php else: ?>
                <li>
                  <a class="model_name" href="<?php echo U($row['node_name'].'/Index/index');?>">
                    <span class="<?php echo ($row['img']); ?>" aria-hidden="true"></span>
                    &nbsp;&nbsp;<?php echo ($row['title']); ?>
                  </a>
                </li><?php endif; endforeach; endif; endif; ?>


      </ul>

      <ul class="nav navbar-nav navbar-right header-tools">



        <li><img src="/system/Public/default/Static/images/avatar2.jpg" class="portrait"></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo ($_SESSION['user']['username']); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="#">修改个人资料</a></li>
            <li><a href="#">修改密码</a></li>
          </ul>
        </li>

        <li><a href="<?php echo U('Auth/Index/logout');?>">注销系统</a></li>
      </ul>

    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>





</div>


<div class="row top50">
  <div class="col-md-2 col-sm-3 col-xs-12">
    <div class="sleft">
    	<div class="menu">
        
          <!-- 侧栏列表 -->
          <div class="panel-group" id="sec_menu" role="tablist" aria-multiselectable="true">
 <?php if(is_array($SEC_MENU)): foreach($SEC_MENU as $key=>$row): ?><div class="panel panel-default">
    <div class="panel-heading" role="tab" id="sec_menu_<?php echo ($row['id']); ?>">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#sec_menu" href="#link_sec_menu_<?php echo ($row['id']); ?>" aria-expanded="false" aria-controls="#link_sec_menu_<?php echo ($row['id']); ?>">
          <span class="<?php echo ($row['img']); ?>" aria-hidden="true"></span>
            &nbsp;&nbsp;<?php echo ($row['title']); ?>
        </a>
      </h4>
    </div>
    <?php if($row['node_name'] == $PUB_URL_NODE): ?><div id="link_sec_menu_<?php echo ($row['id']); ?>" class="panel-collapse collapse  in" role="tabpanel" aria-labelledby="sec_menu_<?php echo ($row['id']); ?>">
    <?php else: ?>
      <div id="link_sec_menu_<?php echo ($row['id']); ?>" class="panel-collapse collapse " role="tabpanel" aria-labelledby="sec_menu_<?php echo ($row['id']); ?>"><?php endif; ?>
      <div class="panel-body">
          <?php $next_menu=get_menu(array('type'=>'2','pid'=>$row['id'])) ?>
          <?php if(is_array($next_menu)): foreach($next_menu as $key=>$row1): if($row1['node_name'] == $PUB_NODE): ?><a type="button" class="link next_active" href="<?php echo U($row1['node_name']);?>">
            <?php else: ?>
              <a type="button" class="link" href="<?php echo U($row1['node_name']);?>"><?php endif; ?>
              <span class="<?php echo ($row1['img']); ?>" aria-hidden="true"></span>
              &nbsp;&nbsp;<?php echo ($row1['title']); ?>
            </a><?php endforeach; endif; ?>
      </div>
    </div>
  </div>
  </if><?php endforeach; endif; ?>
</div>

        
      </div>
    </div>
  </div>
  <div class="col-md-10 col-sm-9 col-xs-12 sright">

  	<div class="main">
      <!-- 右侧标题区 [必须]-->
      <div class="main_map">
        
          <ol class="breadcrumb">
            <?php echo ($path_nav); ?>
            <?php if($back_url != false): ?><li style="float:right"><a href="<?php echo ($back_url); ?>" class="btn btn-success btn-xs">返回上页</a></li><?php endif; ?>

          </ol>
        
      </div>

      <div class="main_body">
        <!-- 工具条【可选】 -->
        

        <!-- 主要内容区域【可选】 -->
        
<link href="/system/Public/default/Static/css/mindex.css" rel="stylesheet">
	<div class="row index_main">
		<div class="col-md-8">
    		<div class="logs_main">
    			<div class="logs_tt"><h4>欢迎您，<?php echo ($_SESSION['user']['username']); ?></h4></div>

                <div class="logs_total">
                    <div class="inrow">
                    <?php if(is_array($shortcut)): foreach($shortcut as $key=>$row): ?><div class="initem">
                            <div class="inbtn"><a class="btn btn-default" href="<?php echo ($row['urls']); ?>"><i class="iconfont"></i> <?php echo ($row['title']); ?></a></div>
                        </div><?php endforeach; endif; ?>

                    </div>
                </div>


                <div class="logs_tt top30"><h4>范围分布</h4></div>
                <div class="logs_total">
                    <img src="/system/Public/default/Static/images/ditu.png" width="100%">
                </div>




                <div class="logs_tt top30"><h4>系统通知</h4></div>
                <div class="logs_total">
                    <p>今日访问量：1.0</p>
                </div>

                <div class="logs_tt top30"><h4>开发团队</h4></div>
                <div class="logs_total">
                    <p>后台开发：杜二红 qq:1186969412</p>
                    <p>前端设计：杜二红 qq:1186969412</p>

                </div>



    		</div>  <!-- end logs_main -->
    	</div>

    	<div class="col-md-4">
    		<div class="logs_lists">
    			<div class="logs_item">
    				<h4>最近操作</h4>
    			</div>

                <?php if(is_array($log_lists)): foreach($log_lists as $key=>$row): ?><div class="logs_item">
    				<div class="logs_action">
    					对【<?php echo (get_model_name($row['model_name'])); ?>】进行了【<?php echo ($row['action']); ?>】操作
    				</div>
    				<div class="logs_times">
    					IP:<?php echo ($row['ip']); ?>&nbsp;时间:<?php echo ($row['times']); ?>
    				</div>
    			</div><?php endforeach; endif; ?>



    		</div>
    	</div>
	</div>


      </div><!-- main_body -->
    </div>
  </div>
</div>




  <div class="footer">
版权归UminniCmf开发团队所有
</div>



<script src="/system/Public/boot/js/bootstrap.min.js"></script>

<script src="/system/Public/plug/datetime/bootstrap-datetimepicker.js" ></script>
<script src="/system/Public/plug/datetime/bootstrap-datetimepicker.zh-CN.js"></script>
<script>
$(".datetimepicker").datetimepicker({
    language:  "zh-CN",
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
    showMeridian: 1
});

$(".datepicker").datetimepicker({
    language:  "zh-CN",
    weekStart: 1,
    todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 2,
    forceParse: 0,
    showMeridian: 1
});
</script>

<script src="/system/Public/default/Static/js/my.js"></script>



<!-- <div id="ld" style="position:absolute; left:0px; top:0px; width:100%; height:100%; background-color:#FFFFFF;opacity:0.5; z-index:1000;">
<div id="center" style="position:absolute;"> </div>
</div>  -->



</body>
</html>





<?php if($_SESSION['act_info'] != false): ?><div class="alt_msg">
  <div class="show_msg">
    <?php
 echo $_SESSION['act_info']; unset($_SESSION['act_info']); ?>
  </div>
</div>
<script type="text/javascript">
var intimer=setInterval(function(){    //开启定时器
  {
    $(".alt_msg").fadeOut(500);
      clearInterval(intimer);    //清除定时器
  }
},2000);
</script><?php endif; ?>
</body>
</html>