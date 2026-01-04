<?php
include '../../inc/config.php';

$supp_id = $_POST['supp_id'];
$supp_answer = $_POST['supp_answer'];
if($supp_id)
{
	$supp_q = mysql_query("UPDATE `chat_support` SET `answer` = '".$supp_answer."' WHERE `id`='".$supp_id."' LIMIT 1");
	if($supp_q){
		echo 1;
	}
}


?>