<?php
error_reporting(0);
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

//mysql_query("DELETE FROM aloaz_db.`conversation` WHERE `last_time` < '".(time()-3600*24*30*3)."'");
//mysql_query("DELETE FROM aloaz_db.`conversation_reply` WHERE `time` < '".(time()-3600*24*30*3)."'");

//mysql_query("DELETE FROM aloaz_db.`notification` WHERE `time` < '".(time()-3600*24*30*1)."'");
mysql_query("DELETE FROM aloaz_db.`viktorina` WHERE `time` < '".(time()-3600*24*30*1)."'");

?>
