<?php
$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

$query = mysql_query("SELECT `name` FROM `blog_cat` WHERE `id` = '".$_id."';");
$catname = mysql_result($query, 0);

echo '<div class="mnav"><a href="index.php">Bloq</a> » '.$catname.'</div>';
echo '<div class="layer">';

$file_count = mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `status` = '1' AND `catid` = '".$_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 8;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$name_array = array();

$query = mysql_query("SELECT * FROM `blog_list` WHERE `status` = '1' AND `catid` = '".$_id."' ORDER BY `date` DESC LIMIT ".$start.", ".$show_limit.";");
$i = 1;
while($row = mysql_fetch_array($query)){
	$blogid = $row['id'];
	$uid = $row['uid'];
	$name = replaceLatin_E(stripslashes($row['name']));
	$body = $row['body'];
	$date = $row['date'];
	$image = $row['image'];
	
	$name_array[$i] = $name;
	if($name_array[$i-1] == $name){
		$s = substr($body, 0, 40);
		$name = replaceLatin_E(substr($s, 0, strrpos($s, ' '))).' ...';
	}
	
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
	$i++;
}

echo '<br/>';
if($page > 1) echo "<a href=\"cat.php?id=".$_id."&amp;page=".($page - 1)."\">« Evvelki</a> | ";
if($all_rows > $start + $show_limit) echo "<a href=\"cat.php?id=".$_id."&amp;page=".($page + 1)."\">Növbeti »</a>";
if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"cat.php?id=".$_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"cat.php?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " ".$i." ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"cat.php?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
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
		echo " <a href=\"cat.php?id=".$_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " ".$max." ";
	}
}

echo '<br/><br/><a href="add.php">Bloq yaz</a><br/>';
echo '<br/><a href="javascript:history.back(1)">« Geri</a>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
