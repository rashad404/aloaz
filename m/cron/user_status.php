<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php'; 

 $sql = mysql_query("select id,user_status from `aloaz_db`.`user` where user_status>0 limit 100");
while($row = mysql_fetch_assoc($sql)){
	$id = $row["id"];
	$user_status = $row['user_status'];
	$status_query = mysql_query("SELECT id FROM `aloaz_db`.`user_status` WHERE `end_time`<'".time()."' and `user_id`='".$id."' and `ended`=0");
	if(mysql_num_rows($status_query)>0){
		$status_row = mysql_fetch_assoc($status_query);
		mysql_query("UPDATE `aloaz_db`.`user_status` SET `ended`=1 WHERE `id`='".$status_row["id"]."'");
		mysql_query("UPDATE `aloaz_db`.`user` SET `user_status`=0,`invisible`=0,`unseen`=0 WHERE id='".$id."' LIMIT 1");
		echo $i.')'.$id."<br />";
	}	
}
?>