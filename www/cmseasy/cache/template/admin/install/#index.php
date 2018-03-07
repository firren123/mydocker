<?php defined('ROOT') or exit('Can\'t Access !'); ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta name="Generator" content="CmsEasy 6_0_0_20180228_UTF8" />
    <meta charset="utf-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title>CmsEasy 企业网站管理系统安装</title>
    <script type="text/javascript" src="<?php echo $skin_path;?>/js/jquery-1.8.3.min.js"></script>
    <link rel="shortcut icon" href="<?php echo $base_url;?>/favicon.ico" type="image/x-icon"/>
    <!-- 调用样式表 -->
    <link type="text/css" rel="stylesheet" media="all" href="<?php echo $skin_path;?>/css/install.css"/>
</head>
<body>


<div id="header">
    <div class="box">
        <img src="<?php echo $skin_path;?>/images/logo.png" class="logo"/>
    </div>
    <div class="top_right">
        <ul>
            <li><span>您好<strong>！</strong></li>
            <li><a href="http://www.cmseasy.cn" target="_blank">官方网站</a> | <a href="http://www.cmseasy.cn/service_1.html" target="_blank">商业授权</a> | <a href="http://www.cmseasy.org" target="_blank">问题交流</a> | <a href="http://www.cmseasy.cn/chm/" target="_blank">在线教程？</a></li>
        </ul>
    </div>
</div>

<div id="nav">
    欢迎使用 CmsEasy！
</div>

<div class="box">
    <div class="blank10"></div>
    <div class="blank30"></div>
    <div class="go"></div>
    <div class="blank30"></div>
    <div class="blank10"></div>
    <form name="form1" method="post" action="<?php echo uri(); ?>">
        <?php if (!get('step')) $this->render('install/license.php'); else { ?>
            <input type="hidden" value="1" id="license_pass" name="license_pass"/>


            <?php
            $pass = true;
            if (PHP_VERSION < 5) $pass = false;
            if (isset($adminerror)) $pass = false;
            ?>
            <div class="table_box">

                    <!--start-->
                    <?php if(front::$get['step']==1) { ?>
                    <style type="text/css">
                        .go {
                            background: url(<?php echo $skin_path;?>/images/go_2.gif) center top no-repeat;
                        }
                    </style>
                <table border="0" cellspacing="1" cellpadding="4" id="table" width="100%">
                    <tbody>
                    <tr bgcolor="Silver">
                        <th>项目</th>
                        <th>推荐版本</th>
                        <th>当前版本</th>
                        <th>是否通过</th>
                    </tr>
                    <tr>
                        <td>PHP版本</td>
                        <td>5.2以上</td>
                        <td><?php echo PHP_VERSION; ?></td>
                        <td><?php echo helper::yes(PHP_VERSION >= 5.0); ?></td>
                    </tr>
                    <tr>
                        <td>数据库版本</td>
                        <td colspan="3" align="left">SQLite 3.0以上</td>
                    </tr>
                    </tbody>

                    <tbody>
                    <tr>
                        <th width="25%">目录</th>
                        <th width="25%">可写</th>
                        <th width="25%">目录</th>
                        <th width="25%">可写</th>
                    </tr>
                    <tr>
                        <td><?php echo 'cache'; ?></td>
                        <td><?php echo helper::yes(front::file_mode_info(ROOT . '/cache') >= 2); ?></td>
                        <td><?php echo 'config'; ?></td>
                        <td><?php echo helper::yes(front::file_mode_info(ROOT . '/config') >= 2); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo 'data'; ?></td>
                        <td><?php echo helper::yes(front::file_mode_info(ROOT . '/data') >= 2); ?></td>
                        <td><?php echo 'install'; ?></td>
                        <td><?php echo helper::yes(front::file_mode_info(ROOT . '/install') >= 2); ?></td>
                    </tr>
                    <tr>
                        <td><?php echo 'template'; ?></td>
                        <td><?php echo helper::yes(front::file_mode_info(ROOT . '/template') >= 2); ?></td>
                        <td><?php echo 'upload'; ?></td>
                        <td><?php echo helper::yes(front::file_mode_info(ROOT . '/upload') >= 2); ?></td>
                    </tr>
                    </tbody>
                </table>

                <div class="blank20"></div>

                <center>
                    <input type="button" onclick="window.location.href='<?php echo url('install/index', true); ?>';"
                           class="btn_b" value=" 上一步 " style="margin-right:20px;"/>
                    <input type="button"
                           onclick="window.location.href='<?php echo url('install/index/step/2', true); ?>';"
                           class="btn_a" value=" 下一步 "/>
                </center>


                <?php } elseif (front::$get['step']==2) { ?>
                <table border="0" cellspacing="1" cellpadding="4" id="table" width="100%">
                <tbody>
                <tr>
                    <th colspan="5"><strong>数据库设置</strong></th>
                </tr>
                <tr>
                    <td class="left">扩展支持</td>
                    <td colspan="4" align="left">PDO <?php echo $pdo ? '支持' : '不支持'; ?> sqlite <?php echo $sqlite ? '支持' : '不支持'; ?></td>
                </tr>
                <tr>
                    <td class="left">数据库文件名</td>
                    <td colspan="4"
                        align="left"><?php echo form::input('sqlite_name', config::get('database', 'database'), $input_disable); ?>
                        <span style="color:red;">&nbsp;*&nbsp;为防止下载，请不要使用cmseasy这个名字</span></td>
                </tr>
                </tbody>

                </table>

                <div class="blank20"></div>

                <center>
                    <input type="button" onclick="window.location.href='<?php echo url('install/index/step/1', true); ?>';" class="btn_b" value=" 上一步 " style="margin-right:20px;"/>
                    <input type="submit" name="dosubmit" class="btn_a" value=" 保存数据库信息 "/>
                    </center>
                        <?php } elseif (front::$get['step']==3) { ?>
                        <style type="text/css">
                            .go {
                                background: url(<?php echo $skin_path;?>/images/go_3.gif) center top no-repeat;
                            }
                        </style>
                        <!--end-->
                        <?php if(isset($adminerror)) { ?>
                        <script>alert('请设置好管理帐号! ');</script>
                        <?php } ?>

                    <table border="0" cellspacing="1" cellpadding="4" id="table" width="100%">
                        <tbody>
                        <tr>
                            <th colspan="2">

                                管理帐号设置

                            </th>
                        </tr>

                        <tr>
                            <td align="right">管理员</td>
                            <td align="left"><?php echo form::input('admin_username', get('admin_username') ? get('admin_username') : ''); ?></td>
                        </tr>
                        <tr>
                            <td align="right">密码</td>
                            <td align="left"><?php echo form::password('admin_password', get('admin_password') ? get('admin_password') : ''); ?></td>
                        </tr>
                        <tr>
                            <td align="right">重复密码</td>
                            <td align="left"><?php echo form::password('admin_password2', get('admin_password2') ? get('admin_password2') : ''); ?></td>
                        </tr>
                        </tbody>
                    </table>
                <center>
                        <input type="hidden" name="install" value="1"/>
                        <input type="submit" id="installbutton" name="dosubmit" value=" 安装 " class="btn_a"
                               onclick="changebutton();if(!confirm('你确实要把数据安装在数据库 [ '+this.form.database.value+' ] 吗？')) return false;"/>
                </center>
                        <div class="blank20"></div>
                        <?php } ?>
                        <div class="blank10"></div>
            </div>
        <?php } ?>
    </form>


    <div class="clear"></div>
</div>
</div>
</div>
<div class="right_bottom">
</div>
</div>

<div id="footer">
</div>
</div>
<div class="clear"></div>
</div>

<div class="blank30"></div>
<div class="copy">
    <?php echo getCopyRight();?>
</div>
</div>

<div class="blank30"></div>

<script language="javascript" type="text/javascript">
    function changebutton() {
        document.getElementById('installbutton').value = "安装中...";
    }
</script>
<script language='JavaScript'>
    //去掉虚线框
    function bluring() {
        if (event.srcElement.tagName == "A" || event.srcElement.tagName == "IMG") document.body.focus();
    }

    document.onfocusin = bluring;
</script>


</body>
</html>