<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8" /><meta http-equiv="Cache-Control" content="no-siteapp" /><meta http-equiv="Cache-Control" content="no-transform" /><meta name="mobile-agent" content="format=xhtml;url=http://m.nbnbk.com/"><link rel="alternate" media="only screen and (max-width: 640px)" href="http://m.nbnbk.com/" />
<title><?php echo sysconfig('CMS_SEOTITLE'); ?></title><meta name="keywords" content="<?php echo sysconfig('CMS_KEYWORDS'); ?>" /><meta name="description" content="<?php echo sysconfig('CMS_DESCRIPTION'); ?>" /><link rel="stylesheet" href="<?php echo sysconfig('CMS_BASEHOST'); ?>/css/style.css" media="all"><script type="text/javascript" src="<?php echo sysconfig('CMS_BASEHOST'); ?>/js/jquery.min.js"></script><script type="text/javascript" src="<?php echo sysconfig('CMS_BASEHOST'); ?>/js/ad.js"></script><script>uaredirect("http://m.nbnbk.com/");</script></head><body><script>site();</script>
<div id="header"><div id="navlink" class="box"><h1><a class="webname" href="<?php echo sysconfig('CMS_BASEHOST'); ?>/"><?php echo sysconfig('CMS_WEBNAME'); ?></a></h1><span class="nav"><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/cat1">笔记本电脑知识</a><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/cat2">笔记本推荐</a><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/cat3">笔记本系统</a><script>navjs();</script></span><form method="get" target="_blank" class="m-sch fr" name="formsearch" action="<?php echo sysconfig('CMS_BASEHOST'); ?>/plus/search.php"><input class="sch-txt" name="q" type="text" value="搜索 按Enter键" onfocus="if(value=='搜索 按Enter键') {value=''}" onblur="if(value=='') {value='搜索 按Enter键'}"></form><div class="cl"></div></div></div><div id="tad"><script>tjs();</script></div>

<div class="box mt10"><div class="fl_640">
<div class="notice">苹果/ThinkPad笔记本电脑无须担心质量问题。</div>
<?php $posts=arclist(array("row"=>11,"tuijian"=>array('<>',1)));foreach($posts as $row){ ?><div class="list"><?php if(!empty($row['litpic'])){ ?><a class="limg" href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><img alt="<?php echo $row['title']; ?>" src="<?php echo $row['litpic']; ?>"></a><?php } ?>
<strong class="tit"><a href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>" target="_blank"><?php echo $row['title']; ?></a></strong><p><?php echo mb_strcut($row['description'],0,150,'UTF-8'); ?>..</p>
<div class="info"><span class="fl"><?php if(!empty($row['writer'])){ echo '<i>'.$row['writer'].'</i>'; }elseif(!empty($row['source'])){ echo '<i>'.$row['source'].'</i>'; } ?><em><?php echo date("m-d H:i",$row['pubdate']); ?></em></span><span class="fr"><em><?php echo $row['click']; ?></em>人阅读</span></div><div class="cl"></div></div><?php } ?>

<div id="iad2"><script>ijs2();</script></div><div class="pages"><ul><li><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/cat1">更多笔记本知识</a></li></ul><div class="cl"></div></div>
<div id="iad3"><script>ijs3();</script></div></div><!-- fl_640 end -->

<div class="fr_300"><div id="rad1"><script>rjs1();</script></div>
<div class="side"><div class="stit"><div class="stith"><strong>热门话题</strong></div><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/tags.html" target="_blank" class="more">更多</a><div class="cl"></div></div>
<div class="ws-tag"><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/cat5" target="_blank">重装系统</a><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/cat4" target="_blank">笔记本电脑配置知识</a></div></div>

<div class="side"><div class="stit"><h2>文章排行</h2><a href="javascript:getmore({PageSize:5,tuijian:1,mode:1,orderby:'rand()'});" class="more">换一换</a><div class="cl"></div></div>	
<ul class="uli chs" id="xglist"><?php $posts=arclist(array("row"=>5,"tuijian"=>1));foreach($posts as $row){ ?><li><a target="_blank" href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><?php echo $row['title']; ?></a></li><?php } ?></ul><div class="cl"></div></div>

<div class="side"><div class="stit"><h2>猜你喜欢</h2><a href="javascript:getmore({PageSize:5,mode:2,orderby:'rand()'});" class="more">换一换</a><div class="cl"></div></div>
<div class="uli2" id="xglike"><?php $posts=arclist(array("row"=>5,"orderby"=>'rand()'));foreach($posts as $row){ ?><div class="suli"><?php if(!empty($row['litpic'])){ ?><a class="limg" href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><img alt="<?php echo $row['title']; ?>" src="<?php echo $row['litpic']; ?>"></a><?php } ?><a target="_blank" href="<?php echo get_front_url(array("id"=>$row['id'],"catid"=>$row['typeid'],"type"=>'content')); ?>"><?php echo $row['title']; ?></a><div class="sulii"><?php if(!empty($row['writer'])){echo '<span class="time">'.$row['writer'].'</span>';}elseif(!empty($row['source'])){echo '<span class="time">'.$row['source'].'</span>';} ?> 阅读(<?php echo $row['click']; ?>)</div><div class="cl"></div></div><?php } ?><div class="cl"></div></div></div>

<div id="rad3"><script>rjs3();</script></div>

<div class="side"><div class="stit"><div class="stith"><strong>友情链接</strong></div><div class="cl"></div></div>
<div class="rtags mt10"><a href="<?php echo sysconfig('CMS_BASEHOST'); ?>/">笔记本之家官网</a></div></div>

<div id="rad2"><script>rjs2();</script></div></div><!-- fr_300 end --></div><!-- box end -->
<script>
function getmore(condition)
{
    var url = "<?php echo sysconfig('CMS_BASEHOST'); ?>/api/listarc";
    //var typeid = "";
    $.post(url,condition,function(res){
        if(res.code==0)
        {
            var json = res.data; //数组
            var str = '';
            $.each(json, function (index) {
                //循环获取数据
                //var title = json[index].title;
                if(condition.mode==1)
                {
                    str = str + '<li><a target="_blank" href="'+json[index].url+'">'+json[index].title+'</a></li>';
                }
                else if(condition.mode==2)
                {
                    var litpic = '';if(json[index].litpic!==''){litpic = '<a class="limg" href="'+json[index].url+'"><img alt="'+json[index].title+'" src="'+json[index].litpic+'"></a>';}
                    str = str + '<div class="suli">'+litpic+'<a target="_blank" href="'+json[index].url+'">'+json[index].title+'</a><div class="sulii">阅读('+json[index].click+')</div><div class="cl"></div></div>';
                }
            });
            
            if(str!='' && str!=null && condition.mode==1)
            {
                $('#xglist').html(str);
            }
            else if(str!='' && str!=null && condition.mode==2)
            {
                $('#xglike').html(str);
            }
        }
        else
        {
            
        }
    },'json');
}
</script>
@include('home.common.footer')</body></html>