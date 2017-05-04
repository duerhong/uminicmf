// $('#myshuoming').popover(options);

// 动态加载效果
// function winload(){
// setTimeout(
// function(){
// ld.style.display="none";
//   f8=true;
//   },0);
// }

// 节点是否选中
$(".main-menu a").each(function(){
    if ($(this)[0].href == String(window.location) && $(this).attr('href')!="/") {
    	$(this).parents("li").addClass("active");
    	$("#menu-item-1").removeClass("active");
	}
});




// 菜单页隐藏和展开

$(document).ready(function(){
  $(".d-toggle").click(function(){
  ns=$(this).attr("data");//获取属性值
  $("."+ns).toggle();
  });
});


// 确认删除
function sure_delete(url) {
		if (!confirm("确认要删除？")) {
            window.event.returnValue = false;
        }
	}


  function comm_url(url) {
  		window.location.href=url;
  }

  // 确认删除
function sure_delete_list(url) {
		if (!confirm("确认要删除？")) {
            window.event.returnValue = false;
        }
    else{
			$("#form_list").attr("action", url);
	    $("#form_list").submit();
    }
}



// 这里是模态框出发时间、

$("#exampleModal1").on("hidden.bs.modal", function(e) {
  $(this).removeData("bs.modal");

});

$("#update_Modal1").on("hidden.bs.modal", function(e) {
  $(this).removeData("bs.modal");
});

$("#add_task_Modal").on("hidden.bs.modal", function(e) {
    $(this).removeData("bs.modal");
});


$('#update_task_Modal').on('hidden.bs.modal', function (e) {
  $(this).removeData("bs.modal");
})
