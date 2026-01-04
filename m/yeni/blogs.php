<?php
$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$_id."';");
$u_login = mysql_result($u_query, 0);

echo '<div class="mnav"><a href="index.php">Bloq</a> » '.$u_login.'</div>';
echo '<div class="layer">';

$file_count = mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `uid` = '".$_id."';");
$all_rows = mysql_result($file_count, 0);

if($all_rows == 0){
	echo 'Bu istifadeçi bloq yazmayıb.<br/><br/>
	<a href="http://m.alo.az/blog/add.php?">Bloq yaz</a><br/>
	<a href="http://m.alo.az/blog/index.php?">Bloq esas sehife</a><br/><br/>
	<a href="javascript:history.back(1)">« Geri</a>';
}

$show_limit = 5;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT * FROM `blog_list` WHERE `uid` = '".$_id."' ORDER BY `date` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$blogid = $row['id'];
	$uid = $row['uid'];
	$name = replaceLatin_E(stripslashes($row['name']));
	$body = $row['body'];
	$date = $row['date'];
	$catid = $row['catid'];
	$image = $row['image'];
	
	$str_search  = array('big.az', 'wap.', 'b i g', 'b.i.g', 'b_i_g', 'b-i-g', 'b*i*g', 'b,i,g', 'bebek.az', 'wen.ru');
	$str_replace = array('.');
	$name = str_ireplace($str_search, $str_replace, $name);
	
	$count_comms = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_com` WHERE `bid` = '".$blogid."';"), 0);
	
	if(!empty($image)) echo '<p style="margin-top:0px;">';
	echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <a href="view_blog.php?id='.$blogid.'">'.$name.'</a><br/>';
	if(!empty($image)) echo '<img src="thumbs/small/'.$image.'" alt="." style="float:left; margin:2px" /> ';
	echo 'Tarix: '.date('d-m-Y H:i', $date).' <img src="/img/icons/com.png" alt="Şerhler" style="vertical-align:middle;"/> '.$count_comms.'<br/>';
	$catquery = mysql_query("SELECT `name` FROM `blog_cat` WHERE `id` = '".$catid."';");
	$catname = mysql_result($catquery, 0);
	echo 'Bölme: '.$catname.'<br/><br/>';
	if(!empty($image)) echo '</p>';
}
echo mysql_error();
if($page > 1) echo "<a href=\"blogs.php?id=".$_id."&amp;page=".($page - 1)."\">« Evvelki</a> | ";
if($all_rows > $start + $show_limit) echo "<a href=\"blogs.php?id=".$_id."&amp;page=".($page + 1)."\">Növbeti »</a>";
if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"blogs.php?id=".$_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"blogs.php?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " ".$i." ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"blogs.php?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
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
		echo " <a href=\"blogs.php?id=".$_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " ".$max." ";
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
