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
        
<div class="filter">
    <div class="row frow">
      <form action="" method="POST" name="form" class="myforms">
      <?php echo ($saerchForm); ?>
      <div class="col-md-1  col-xs-12 col-sm-1">
        <input  type="submit" class="btn btn-info btn-sm btn-block" value="条件查询">
      </div>
      </form>

    </div>
</div>
<form id="form_list" name="form_list" action="" method="post">
<table class="table  datalist  table-hover">
  <thead>
    <tr>
      <th style="font-size:12px;">
        <input type="checkbox" class="select_all" name="select_all">
      </th>
      <?php if($list_img_show != false): ?><th>图片</th><?php endif; ?>
      <?php if(is_array($title_lists)): foreach($title_lists as $key=>$row): ?><th>
          <?php echo ($row); ?>
          <?php if($_GET['order_by'] != $key.'|desc' and $_GET['order_by'] != $key.'|asc'): ?><a href="<?php echo add_url_parameter('order_by',$key.'|desc');?>">
              <i class="iconfont" title="通过<?php echo ($row); ?>排序">&#xe63b;</i>
            </a>

          <?php elseif($_GET['order_by'] == $key.'|desc'): ?>
            <a href="<?php echo add_url_parameter('order_by',$key.'|asc');?>">
              <i class="iconfont" title="通过<?php echo ($row); ?>升序排序">&#xe65c;</i>
            </a>


          <?php elseif($_GET['order_by'] == $key.'|asc'): ?>
            <a href="<?php echo add_url_parameter('order_by',$key.'|desc');?>">
              <i class="iconfont" title="已经通过<?php echo ($row); ?>降序排列">&#xe66e;</i>
            </a><?php endif; ?>

        </th><?php endforeach; endif; ?>
      <th class="tright pad-r21" scope="row">



          <!-- Split button -->
          <div class="btn-group">
            <?php if($action_add_button != false): ?><a type="button" class="btn btn-info btn-sm" href="<?php echo U($action_add_button['node_name']);?>"><?php echo ($action_add_button['title']); ?>+</a><?php endif; ?>
            <?php if($common_action_button != false): ?><button type="button" class="btn btn-primary dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              更多操作<span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <?php if(is_array($common_action_button)): foreach($common_action_button as $key=>$arow): if($arow['pre_func'] != false): ?><li><a onclick="<?php echo ($arow['pre_func']); ?>('<?php echo U($arow['node_name']);?>');"><?php echo ($arow['title']); ?></a></li>
                <?php else: ?>
                <li><a href="<?php echo U($arow['node_name']);?>" onclick="comm_url('<?php echo U($arow['node_name']);?>');"><?php echo ($arow['title']); ?></a></li><?php endif; endforeach; endif; ?>
            </ul><?php endif; ?>
          </div>


      </th>
    </tr>
  </thead>
  <tbody>
    <?php if(is_array($list_data)): foreach($list_data as $key=>$row): ?><tr>
      <td style="font-size:12px;">
        <input type="checkbox" name="select_id[]" value="<?php echo ($row['id']); ?>">
      </td>
      <?php if($list_img_show != false): ?><td><img src="<?php echo ($row[$list_img_show]); ?>" width=42 height=42 style="border:1px solid #ccc;padding:2px;"></td><?php endif; ?>
      <?php if(is_array($title_lists)): foreach($title_lists as $key1=>$row1): ?><td>
         <?php echo (get_model_value($row[$key1],$THIS_MODEL['model_name'],$key1)); ?>
        </td><?php endforeach; endif; ?>
      <td class="tright" scope="row">
        <?php if(is_array($action_lists)): foreach($action_lists as $key=>$act_row): ?><a href="<?php echo U($act_row['node_name'],array('id'=>$row['id']));?>"  onclick="<?php echo ($act_row['pre_func']); ?>();" class="abtn"><?php echo ($act_row['title']); ?></a>&nbsp;<?php endforeach; endif; ?>
      </td>
    </tr><?php endforeach; endif; ?>
  </tbody>
  <thead>
    <tr>
      <td style="font-size:12px;">
      </td>
      <?php if($list_img_show != false): ?><th>图片</th><?php endif; ?>
      <?php if(is_array($title_lists)): foreach($title_lists as $key=>$row): ?><th>
          <?php echo ($row); ?>
        </th><?php endforeach; endif; ?>
      <th class="tright pad-r21" scope="row">操作</th>
    </tr>
  </thead>
  </table>
  </form>
  <form action="" method="GET">
  <div class="page"><?php echo ($page); ?>&nbsp;&nbsp;
  <span>跳转至&nbsp;<input type="text" name="p" value="<?php echo ($_GET['p']); ?>"  style="padding:4px 6px;border:1px solid #ccc;border-radius: 4px;width: 50px;">&nbsp;页&nbsp;</span><input type="submit"  class="btn btn-info btn-sm" value="确认">
  </div>
  </form>

  <script type="text/javascript">
  $(".select_all").click(
    function(){
      if(this.checked){
        $("input[name='select_id[]']").each(function(){this.checked=true;});
      }else{
        $("input[name='select_id[]']").each(function(){this.checked=false;});
      }
    }
  );
  </script>

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