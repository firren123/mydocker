<?php defined('ROOT') or exit('Can\'t Access !'); ?>


<script type="text/javascript">
    //<!CDATA[
        var bodyBgs = [];
        bodyBgs[0] = "<?php echo get(cslide_pic1);?>";
        bodyBgs[1] = "<?php echo get(cslide_pic2);?>";
        bodyBgs[2] = "<?php echo get(cslide_pic3);?>";
        bodyBgs[3] = "<?php echo get(cslide_pic4);?>";
        bodyBgs[4] = "<?php echo get(cslide_pic5);?>";
        var randomBgIndex = Math.round( Math.random() * 4 );
    //输出随机的背景图
        document.write('<style>.banner{background:url(' + bodyBgs[randomBgIndex] + ') no-repeat 50% bottom}</style>');
    //]]>
</script>

<section>
<div class="banner" style="height:<?php echo get('cslide_height');?>px;">
</div>
</section>