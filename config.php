<?php
include_once("adminarea/mysql2mysqli.php");

date_default_timezone_set('Asia/Kolkata');
mysql_connect('localhost','oxyusr','oxy@321-#');
mysql_select_db('oxytra');
session_start();

$baseurl="http://localhost:90";
$sql="SELECT * FROM setting_tbl";
$result=mysql_query($sql);
$row_top=mysql_fetch_array($result,MYSQL_ASSOC);
date_default_timezone_set("Asia/Kolkata");
?>

