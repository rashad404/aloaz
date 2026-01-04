<?php
session_start();
$title = 'Populyar bloqcular';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="index.php">Bloq</a> » '.$title.'</a></div>';
echo '<div class="layer">';

$_period = checkData($_GET['period']);

$this_week = strtotime('last monday', strtotime('tomorrow'));
$this_month = strtotime(date('Y-m-01 00:00:00'));

if($_period == 'week'){
	//echo date('d-m-Y H:i', $this_week);
	$ins_period = " AND `date` > '".$this_week."'";
	echo 'Bu hefte | ';
} else echo '<a href="topusers.php?period=week">Bu hefte</a> | ';

if($_period == 'month') {
	$ins_period = "AND `date` > '".$this_month."'";
	echo 'Bu ay | ';
}else echo '<a href="topusers.php?period=month">Bu ay</a> | ';

if(!$ins_period){
	$ins_period = "";
	echo 'Cemi';
}else echo '<a href="topusers.php?period=all">Cemi</a>';

echo '<br/><br/>';

$top_query = mysql_query("SELECT `uid`, COUNT(`uid`) FROM `blog_list` WHERE `status` = '1' ".$ins_period." GROUP BY `uid` ORDER BY COUNT(`uid`) DESC LIMIT 20");

$i = 1;
while($top_row = mysql_fetch_array($top_query)){
	$top_uid = $top_row['uid'];
	$top_count = $top_row['COUNT(`uid`)'];
	
	$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$top_uid."';");
	$u_login = mysql_result($u_query, 0);
	
	echo '<b>'.$i.'</b>. '.$u_login.' <a href="blogs.php?id='.$top_uid.'">('.$top_count.')</a><br/>';
	$i++;
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
