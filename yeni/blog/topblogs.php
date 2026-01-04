<?php
session_start();
$title = 'Top bloqlar';
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
} else echo '<a href="topblogs.php?period=week">Bu hefte</a> | ';

if($_period == 'month') {
	$ins_period = "AND `date` > '".$this_month."'";
	echo 'Bu ay | ';
}else echo '<a href="topblogs.php?period=month">Bu ay</a> | ';

if(!$ins_period){
	$ins_period = "";
	echo 'Cemi';
}else echo '<a href="topblogs.php?period=all">Cemi</a>';

echo '<br/><br/>';

if($_period != ''){
	$all_rows = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `status` = '1' ".$ins_period.";"), 0);
	if($all_rows > 50) $all_rows = 50;
} else $all_rows = 50;

$show_limit = 5;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT * FROM `blog_list` WHERE `status` = '1' ".$ins_period." ORDER BY `read` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$blogid = $row['id'];
	$uid = $row['uid'];
	$name = replaceLatin_E(stripslashes($row['name']));
	$body = $row['body'];
	$date = $row['date'];
	$image = $row['image'];
	
	$count_comms = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_com` WHERE `bid` = '".$blogid."';"), 0);
	
	echo '<div class="content">';
	if(!empty($image)) echo '<p style="margin-top:0px;">';
	if(!empty($image)) echo '<img src="thumbs/small/'.$image.'" alt="." style="float:left; padding-right:5px;" width="80" height="70" />';
	echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <a href="view_blog.php?id='.$blogid.'">'.$name.'</a><br/>';
	echo 'Tarix: '.date('d-m-Y H:i', $date).' <img src="/img/icons/com.png" alt="Şerhler" style="vertical-align:middle;"/> '.$count_comms.'<br/>';
	$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
	$u_login = mysql_result($u_query, 0);
	echo 'Müellif: '.$u_login.'<br/>';
	if(!empty($image)) echo '</p>';
	echo '</div>';
}

if($page > 1) echo "<a href=\"topblogs.php?id=".$_id."&amp;page=".($page - 1)."&amp;period=".$_period."\">« Evvelki</a> | ";
if($all_rows > $start + $show_limit) echo "<a href=\"topblogs.php?id=".$_id."&amp;page=".($page + 1)."&amp;period=".$_period."\">Növbeti »</a>";
if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"topblogs.php?id=".$_id."&amp;page=1&amp;period=".$_period."\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"topblogs.php?id=".$_id."&amp;page=".$i."&amp;period=".$_period."\">".$i."</a> ";
		}
		else{
			echo " ".$i." ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"topblogs.php?id=".$_id."&amp;page=".$i."&amp;period=".$_period."\">".$i."</a> ";
			}
			else{
				echo " ".$i." ";
			}
		}
		
	}
}
if($page <= $max - 5) echo '... ';

if($max > $interval){
	if($max != $page){
		echo " <a href=\"topblogs.php?id=".$_id."&amp;page=".$max."&amp;period=".$_period."\">".$max."</a> ";
	}
	else{
		echo " ".$max." ";
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
