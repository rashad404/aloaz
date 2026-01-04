<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php'; 
$time = time() - (30*24*3600);

$remove_conversation_reply = mysql_query("DELETE FROM `aloaz_db`.`conversation_reply` WHERE `time`<'".$time."' AND `read` = 1");
if($remove_conversation_reply){
	echo "conversation_reply silindi";
}else{
	echo "error!";
}


$remove_conversation = mysql_query("DELETE FROM `aloaz_db`.`conversation` WHERE `last_time`<'".$time."'");
if($remove_conversation){
	echo "conversation silindi";
}else{
	echo "error!";
}


?>