<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php';  
$count = mysql_num_rows(mysql_query("SELECT id FROM `aloaz_db`.`room_msgs` WHERE rid=10"));
$limit  = $count - 1000;  
$remove_msgs = mysql_query("DELETE FROM `aloaz_db`.`room_msgs` WHERE `rid`=10 LIMIT ".$limit);
if($remove_msgs){
	echo "silindi";
}else{
	echo "error!";
}


?>