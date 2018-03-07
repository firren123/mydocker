
<div class="alert alert-warning alert-danger" role="alert">
<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
<span class="glyphicon glyphicon-warning-sign"></span>	欢迎来到数据库备份页面。您可以对网站数据备份，数据将保存在<strong style="color:red;">data</strong>文件夹中。	
</div>

<div class="blank30"></div>

<ul class="nav nav-tabs" role="tablist">
<li class="active"><a href="{$base_url}/index.php?case=database&act=baker&admin_dir={get('admin_dir')}&site=default">备份数据库</a></li>
<!--<li><a href="{$base_url}/index.php?case=database&act=restore&admin_dir={get('admin_dir')}&site=default">还原数据库</a></li>-->
<li><a href="{$base_url}/index.php?case=adminlogs&act=manage&admin_dir={get('admin_dir')}&site=default">日志管理</a></li>
<li><a href="{$base_url}/index.php?case=database&act=str_replace&admin_dir={get('admin_dir')}&site=default">替换字符串</a></li>
<li><a href="{$base_url}/index.php?case=database&act=phpwebinsert&admin_dir={get('admin_dir')}&site=default">导入PHPweb数据</a></li>
<li><a href="{$base_url}/index.php?case=database&act=backAll&admin_dir={get('admin_dir')}&site=default">备份整站</a></li>
</ul>

<div class="blank30"></div>

<form name="listform" id="listform"  action="<?php echo uri();?>" method="post">
<div class="table-responsive">
<table class="table table-hover">
<thead>
<tr class="th">
          <th class="catname" align="left">备份名</th>
           <th class="catid" align="center">日期</th>
           <th class="catid" align="center">操作</th>
        </tr>
</thead>
<tbody>
        {loop $files $file}
        <?php
        if(preg_match('/^#cms\d+\.db$/i',$file)){
        ?>
      <tr>
           <td class="catname" align="left">{$file}</td>
          <td align="center" class="catid"><?php echo date( "Y-m-d H:i:s" ,  filemtime ( ROOT.'/data/'.$file ));?></td>
          <td align="center" class="catid"><a href="{url('database/dorestore/f/'.urlencode($file))}" class="btn btn-default">恢复</a></td>
        </tr>
        <?php
        }
        ?>
       {/loop}

      </tbody>
    </table>
</div>

<div class="blank30"></div>

<div class="blank30"></div>
<div class="line"></div>
<div class="blank30"></div>

<input type="submit" name="submit" value=" 点击备份 " class="btn btn-primary" />
</form>
