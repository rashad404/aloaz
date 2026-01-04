<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php'; 

$ip = $_SERVER["REMOTE_ADDR"];
if($ip!='217.64.27.46' and $ip != '176.32.32.21' and $ip!='185.22.155.185' and $ip!='91.242.23.139'){
	echo "404 tapilmadi"; exit;
}

$resetActivity = mysql_query("UPDATE `aloaz_db`.`user` SET `monthly_activity`=0 WHERE `monthly_activity`>0");
$activityIsset = mysql_fetch_assoc(mysql_query('SELECT id FROM `aloaz_db`.`cron` WHERE `cron`="monthly_activity"'));
if($activityIsset){
	mysql_query("UPDATE `aloaz_db`.`cron` SET `date`='".date("Y-m-d")."',`datetime`='".date("Y-m-d H:i:s")."' WHERE `cron`='monthly_activity'");
	echo "update";
}else{
	mysql_query("INSERT INTO `aloaz_db`.`cron` SET cron='monthly_activity',date='".date("Y-m-d")."',`datetime`='".date("Y-m-d H:i:s")."'");
	echo "insert";
}
 
?>