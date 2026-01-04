<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php'; 
$hour12 = mysql_fetch_assoc(mysql_query('SELECT id FROM `aloaz_db`.`cron` WHERE `cron`="hour12" and `date`="'.date("Y-m-d").'"'));
if($hour12){
	echo "var";
}else{
	if(date('H') == '00' && intval(date('i')) < 30){	
		updateXal();
		updatePosts();
	
		$hour12isset = mysql_fetch_assoc(mysql_query('SELECT id FROM `aloaz_db`.`cron` WHERE `cron`="hour12"'));
		if($hour12isset){
			mysql_query("UPDATE `aloaz_db`.`cron` SET `date`='".date("Y-m-d")."' WHERE `cron`='hour12'");
			echo "update";
		}else{
			mysql_query("INSERT INTO `aloaz_db`.`cron` SET cron='hour12',date='".date("Y-m-d")."'");
			echo "insert";
		}
	}else{
		echo "Icaze yoxdur <br />  Sizin IP -> ".$_SERVER["REMOTE_ADDR"];
	}
	
} 

?>