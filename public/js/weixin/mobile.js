$(function(){
	//头部菜单
	$('.classreturn .nav_menu a:last').click(function(e){
		$('.tpnavf').toggle();
		e.stopPropagation();
	});
	//左侧导航
	$('.classlist ul li').click(function(){
		$(this).addClass('red').siblings().removeClass('red');
	});
});

//删除确认框
function delconfirm(url,des)
{
	if(!des){des='确定要删除吗？';}
    
    //询问框
    layer.open({
        content: des
        ,btn: ['确定', '取消']
        ,yes: function(index){
            location.href= url;
            layer.close(index);
        }
    });
}