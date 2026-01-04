<?php 
/*****************************\
# API: Sim.az				  |
# Author: Yusubov Pərviz	  |
******************************/

include('../inc.php');
$link = connect_db();

	
	
$id = intval($_GET['id']);
$sql = mysql_query("SELECT `id`,`url` FROM `advertisers` WHERE `id` = '{$id}';");
if( $object = mysql_fetch_object($sql) ) {
	if( !$_SESSION['reklam-'.$object->id] ) {
		$_SESSION['reklam-'.$object->id] = true;
		mysql_query("UPDATE `advertisers` SET `clicks`=`clicks`+'1' WHERE `id` ='{$id}';");
	}
	echo '<META HTTP-EQUIV="Refresh" Content="0; URL='.$object->url.'"/>';
	die;
}