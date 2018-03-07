<?php

if (!defined('ROOT'))
exit('Can\'t Access !');

class install_act extends act
{

function init()
{
header('Cache-control: private, must-revalidate');
if (self::installed())
exit('系统已安装！若要再安装，请删除文件 /install/locked ! ');
//set_time_limit(0);
}

function index_action()
{
$this->view->pdo = extension_loaded('PDO');
$this->view->sqlite = extension_loaded('pdo_sqlite');
$this->view->mysql_pass = false;
if (front::get('step') == 2 && isset(front::$post['dosubmit'])) {
if (front::$post['sqlite_name'] != '' && front::$post['sqlite_name'] != 'cmseasy') {
$filename = ROOT . '/data/#' . front::$post['sqlite_name'] . '.db';
$sfile = ROOT . '/data/cmseasy.db';
$rs = copy($sfile, $filename);
if ($rs) {
config::modify(array('database' => front::$post['sqlite_name']));
front::redirect(url('install/index/step/3',true));
} else {
exit('安装数据库出错');
}
}else{
alerterror('数据库名不能是空和cmseasy');
}
}
if (front::get('step') == 3 && isset(front::$post['dosubmit'])) {
config::modify(array('cookie_password' => sha1(get_hash())));
$_PHP_SELF = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : (isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['ORIG_PATH_INFO']);
$_ROOTPATH = str_replace("\\", "/", dirname($_PHP_SELF));
$_ROOTPATH = strlen($_ROOTPATH) > 1 ? $_ROOTPATH . "/" : "/";
$_site_url = 'http://' . $_SERVER['HTTP_HOST'] . $_ROOTPATH;
config::modify(array('site_url' => $_site_url));
config::modify(array('install_admin' => front::post('admin_username')));
if (!front::post('admin_password') || !front::post('admin_username') || front::post('admin_password') <> front::post('admin_password2')) {
$this->view->adminerror = true;
}else {
$this->prepare_sqlite();
return;
}
}
$this->render();
}

private function prepare_sqlite()
{
$db = new user();
$db->rec_update(array('username'=>front::post('admin_username'),'password'=>md5(front::post('admin_password'))),array('username'=>'admin'));
@unlink(ROOT . '/install/index.php');
front::redirect(url::create('install/success'));
}

function view_action()
{
$this->render();
}

function success_action()
{
$this->render();
file_put_contents(ROOT . '/install/locked', 'install-locked !');
@unlink(ROOT . '/install/index.php');
}


}