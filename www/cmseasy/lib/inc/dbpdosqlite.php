<?php

class dbpdosqlite
{

public $connection_id = null;
public $pconnect = 0;
public $shutdown_queries = array();
public $queries = array();
public $query_id = "";
public $query_count = 0;
public $record_row = array();
public $failed = 0;
public $halt = "";
public $sql = '';
public $sqlite = null;
public $resType = array('assoc' => MYSQLI_ASSOC, 'num' => MYSQLI_NUM, 'both' => MYSQLI_BOTH);
public static $me;
public $islink = false;
public $primary_key = '';

public static function getInstance($dbname)
{
if (!self::$me) {
$class = new dbpdosqlite();
self::$me = $class;
}
self::$me->islink = self::$me->connect($dbname);
return self::$me;
}

public function connect($dbname)
{
$filename = realpath('data/#' . $dbname . '.db');
//var_dump($filename);
try {
$this->sqlite = new PDO("sqlite:$filename");
} catch (PDOException $e) {
echo $e->getMessage();
return false;
}
//var_dump($this->sqlite);
return true;
}




function query($sql)
{
//echo $sql.'---';
$res = $this->sqlite->exec($sql);
//var_dump($res);
if (false === $res) {
var_dump($this->sqlite->errorInfo());
$this->halt("查询失败:\n$sql");
}
return $res;
}

function query_unbuffered($sql = "")
{
return $this->query($sql, 'mysql_unbuffered_query');
}

function fetch_array($res, $type = 'assoc')
{
return $res->fetch_array($this->resType[$type]);
}

function shutdown_query($query_id = "")
{
$this->shutdown_queries[] = $query_id;
}

function affected_rows()
{
return @mysql_affected_rows($this->connection_id);
}

function num_rows($res)
{
return $res->num_rows;
}

function get_errno()
{
$this->errno = $this->mysqli->errno;
return $this->errno;
}

function get_error()
{
$this->error = $this->mysqli->error;
return $this->error;
}

function insert_id()
{
$sql = "select last_insert_rowid() AS lastid";
$row = $this->rec_query_one($sql);
return $row['lastid'];
}

function query_count()
{
return $this->query_count;
}

function free_result($res)
{
$res->free_result();
}

function close_db()
{
if ($this->connection_id) {
//return @mysql_close($this->connection_id);
}
}

function get_table_names()
{
global $db_config;
$result = mysql_list_tables($db_config["database"]);
$num_tables = @mysql_numrows($result);
for ($i = 0; $i < $num_tables; $i++) {
$tables[] = mysql_tablename($result, $i);
}
mysql_free_result($result);
return $tables;
}

function halt($the_error = "", $sql = '')
{
//return;
if (!config::get('isdebug'))
return;
$message = $the_error . "<br/>" . $sql . "\r\n";
$message .= $this->get_errno() . "<br/>\r\n";
$message .= $this->get_error() . "<br/>\r\n";
exit($message);
}

function __destruct()
{
$this->shutdown_queries = array();
$this->close_db();
}

function sql_select($tbname, $where = "", $limit = 0, $fields = "*", $order = '')
{
$sql = "SELECT " . $fields . " FROM `" . $tbname . "` " . ($where ? " WHERE " . $where : "") . " ORDER BY " . $order . ($limit ? " limit " . $limit : "");
//echo $sql."<br>";
return $sql;
}

function sql_insert($tbname, $row)
{
$sqlfield = '';
$sqlvalue = '';
foreach ($row as $key => $value) {
if (in_array($key, explode(',', $this->getcolslist()))) {
$value = $value;
$sqlfield .= $key . ",";
$sqlvalue .= "'" . $value . "',";
}
}
return "INSERT INTO `" . $tbname . "`(" . substr($sqlfield, 0, -1) . ") VALUES (" . substr($sqlvalue, 0, -1) . ")";
}

function sql_update($tbname, $row, $where)
{
//var_dump($row);
$sqlud = '';
if (is_string($row))
$sqlud = $row . ' ';
else
foreach ($row as $key => $value) {
if (in_array($key, explode(',', $this->getcolslist()))) {
$value = $value;
/*if (preg_match('/^\[(.*)\]$/',$value,$match))
$sqlud .= "`$key`"."= '".$match[1]."',";
else*/
if ($value === "")
$sqlud .= "`$key`= NULL, ";
else
$sqlud .= "`$key`" . "= '" . $value . "',";
}
}
$sqlud = rtrim($sqlud);
$sqlud = rtrim($sqlud, ',');
$this->condition($where);
$sql = "UPDATE `" . $tbname . "` SET " . $sqlud . " WHERE " . $where;
//echo $sql;
return $sql;
}

function sql_replace($tbname, $row)
{
$sqlud = '';
if (is_string($row))
$sqlud = $row . ' ';
else
foreach ($row as $key => $value) {
if (in_array($key, explode(',', $this->getcolslist()))) {
$value = $value;
$sqlud .= $key . "= '" . $value . "',";
}
}
return "REPLACE INTO `" . $tbname . "` SET " . substr($sqlud, 0, -1);
}

function sql_delete($tbname, $where)
{
$this->condition($where);
return "DELETE FROM `" . $tbname . "` WHERE " . $where;
}

function rec_insert($row)
{
$tbname = $this->name;
$sql = $this->sql_insert($tbname, $row);
return $this->query_unbuffered($sql);
}

function rec_update($row, $where)
{
$tbname = $this->name;
$sql = $this->sql_update($tbname, $row, $where);
//echo $sql."<br>";exit;
return $this->query_unbuffered($sql);
}

function rec_replace($row)
{
$tbname = $this->name;
$sql = $this->sql_replace($tbname, $row);
//echo $sql."\n";
return $this->query_unbuffered($sql);
}

function rec_delete($where)
{
$tbname = $this->name;
$sql = $this->sql_delete($tbname, $where);
//echo $sql;exit;
return $this->query_unbuffered($sql);
}

function rec_select($where = "", $limit = 0, $fields = "*", $order = '')
{
$tbname = $this->name;
$sql = $this->sql_select($tbname, $where, $limit, $fields, $order);
//echo $sql."<br>";
$res = $this->rec_query($sql);
return $res;
}

function rec_select_one($where, $fields = "*", $order = "id")
{
$tbname = $this->name;
$sql = $this->sql_select($tbname, $where, 1, $fields, $order);
//echo $sql."<br>";
return $this->rec_query_one($sql);
}

function rec_query($sql)
{
$sth = $this->sqlite->prepare($sql);
$result = false;
if($sth){
$sth->execute();
$result = $sth->fetchAll(PDO::FETCH_ASSOC);
}else{
//
}

return $result;
}

function rec_query_one($sql)
{
$sth = $this->sqlite->prepare($sql);
$result = false;
if ($sth) {
$sth->execute();
$result = $sth->fetch(PDO::FETCH_ASSOC);
}else{
//if(config::get('isdebug')) echo $sql;
}
return $result;
}

function rec_count($where = "")
{
$tbname = $this->name;
if (preg_match('/_category$/', $tbname))
$sql = "SELECT count(catid) as rec_sum FROM `" . $tbname . "` " . ($where ? " WHERE " . $where : "");
else
$sql = "SELECT count(1) as rec_sum FROM `" . $tbname . "` " . ($where ? " WHERE " . $where : "");
//echo $sql;//exit;
$row = $this->rec_query_one($sql);
return $row["rec_sum"];
}

function getrows($condition = '', $limit = 1, $order = '1 desc', $cols = '*')
{
$this->condition($condition);
$this->record_count = $this->rec_count($condition);
$res = $this->rec_select($condition, $limit, '*', $order);
return $res;
}

function getrows1($condition = '', $limit = 1, $order = '1 desc', $cols = '*')
{
$this->condition($condition);
$this->record_count = $this->rec_count($condition);
return $this->rec_select($condition, $limit, '*', $order, '');
}

function getrow($condition, $order = '1 desc', $cols = '*')
{
$this->condition($condition);
//var_dump($condition);
return $this->rec_select_one($condition, '*', $order);
}

function condition(&$condition)
{
if (isset($condition) && is_array($condition)) {
$_condition = array();
foreach ($condition as $key => $value) {
//$value=str_replace("'","\'",$value);
$key = htmlspecialchars($key, ENT_QUOTES);
if (preg_match('/(if|select|ascii|from|sleep)/i', $value)) {
//echo $condition;
exit('sql inject');
}
if (preg_match('/(if|select|ascii|from|sleep)/i', $key)) {
//echo $condition;
exit('sql inject');
}
$_condition[] = "`$key`='$value'";
}
$condition = implode(' and ', $_condition);
} else if (is_numeric($condition)) {
if (preg_match('/(if|select|ascii|from|sleep)/i', $condition)) {
//echo $condition;
exit('sql inject');
}
$this->getFields();
$condition = "`$this->primary_key`='$condition'";
} else if (true === $condition) {
$condition = 'true';
} else {
//echo $condition." __ ";
if (preg_match('/(if|select|ascii|from|sleep)/i', $condition)) {
//echo $condition;
exit('sql inject');
}
}

if (get_class($this) == 'archive') {
if (!front::get('deletestate')) {
if ($condition)
$condition .= ' and (state IS NULL or state<>\'-1\') ';
else
$condition = 'state IS NULL or state<>\'-1\' ';
} else {
if ($condition)
$condition .= ' and state=\'-1\' ';
else
$condition = ' state=\'-1\' ';
}
}
}

function getFields()
{
//static $fields;
//if (!isset($fields)) {
$fields = array();
//$query = $this->query('Describe ' . $this->name);
//var_dump($this->name);
//exit;
//var_dump($this->_form);
$res = $this->rec_query("PRAGMA table_info(" . $this->name . ")");
//var_dump($res);exit;
//var_dump($res);exit;
$_field = array();
$t_name = str_replace(config::get('database','prefix'),'',$this->name);
//var_dump(@setting::$var[$t_name]);
foreach ($res as $field) {

//$_type = preg_match('/([\w\x7f-\xff]+)(\(([\w\x7f-\xff]+)\))?/i', $field['type'], $result);
$_field['name'] = $field['name'];
$type = @setting::$var[$t_name][$_field['name']]['type'];
$_field['type'] = $type ? $type : $field['type'];
$_field['len'] = 0;
$_field['primary_key'] = $field['pk'] == "1";
$_field['notnull'] = $field['notnull'] == '1';
$_field['selecttype'] = isset($this->_form[$_field['name']]['selecttype']) ? $this->_form[$_field['name']]['selecttype'] : '';
$_field['select'] = isset($this->_form[$_field['name']]['select']) ? $this->_form[$_field['name']]['select'] : '';
$_field['tips'] = isset($this->_form[$_field['name']]['tips']) ? $this->_form[$_field['name']]['tips'] : '';
$fields[$field['name']] = $_field;
if ($field['pk'] == '1') {
$this->primary_key = $field['name'];
//$primary_key = $this->primary_key;
}
}
//}
//$this->primary_key = $primary_key;
//var_dump($fields);
return $fields;
}

function getFiledsList()
{
$list = '';
foreach ($this->getFields() as $field) $list .= $field['name'] . ' ';
return $list;
}

function getcolslist()
{
$list = array();
foreach ($this->getFields() as $field) $list[] = $field['name'];
return implode(',', $list);
}

function getcols($act = '')
{
return implode(',', array_slice(array_keys($this->getFields()), 0, 2));
}

function mycols()
{
$_cols = array_keys($this->getFields());
$cols = '';
foreach ($_cols as $col) {
if (preg_match('/my_/', $col))
$cols .= ',' . $col;
}
return $cols;
}

function get_form()
{
}

final function getname()
{
return $this->name;
}

function rec_query_one1($sql)
{
$rs = mysql_query($sql);
$row = mysql_fetch_array($rs);
$this->free_result($rs);
return $row;
}

function _rec_query1($sql)
{
$rs = mysql_query($sql);
$rs_num = mysql_num_rows($rs);
$rows = array();
for ($i = 0; $i < $rs_num; $i++) {
$rows[] = mysql_fetch_array($rs);
}
return $rows;
}

function _rec_select1($tbname, $where = "", $limit = 0, $fields = "*", $order = '')
{
$sql = $this->sql_select($tbname, $where, $limit, $fields, $orde);
return $this->_rec_query1($sql);
}

function verison()
{
return str_replace("undefined","",$this->sqlite->getAttribute(PDO::ATTR_SERVER_VERSION));

//var_dump($row);
/*$sql = "select sqlite_version() as ver";
$row = $this->rec_query_one($sql);
var_dump($row);*/
//var_dump(PDO::ATTR_SERVER_VERSION);
//return mysql_get_server_info($this->connection_id);
}
}