<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php'; 
$time = time() - (7*24*3600);

$remove_notification = mysql_query("DELETE FROM `aloaz_db`.`viktorina` WHERE `time`<'".$time."'");
if($remove_notification){
	echo "silindi";
}else{
	echo "error!";
}


?>