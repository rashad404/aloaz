<?php
	include 'inc/config.php';
	$query = mysql_query("SELECT `id` FROM admin_alochat.`user` LIMIT 1");
	$array = mysql_fetch_array($query);
	$user_id = $array['id'];
	echo $user_id;
?>