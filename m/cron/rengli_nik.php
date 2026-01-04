<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php'; 

$sql = mysql_query("select id,rnickname from `aloaz_db`.`user` where rnickname!='' limit 100");
while($row = mysql_fetch_assoc($sql)){
	$id = $row["id"]; 
	$rengli_query = mysql_query("SELECT id FROM `aloaz_db`.`rengli` WHERE `end_time`<'".time()."' and `user_id`='".$id."' and `ended`=0");
	if(mysql_num_rows($rengli_query)>0){
		$rengli_row = mysql_fetch_assoc($rengli_query);
		mysql_query("UPDATE `aloaz_db`.`rengli` SET `ended`=1 WHERE `id`='".$rengli_row["id"]."'");
		mysql_query("UPDATE `aloaz_db`.`user` SET `rnickname`='' WHERE id='".$id."' LIMIT 1");
		$rengli_nik = "rn/tmp/".$rengli_row["file"];  
		
		$unlink_rengli_nik = unlink($rengli_nik);  
		echo $i.')'.$id."<br />";
	}	
}
?>