<?php
$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include '/home/admin/domains/server-saytim.net/public_html/chat/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

$query = mysql_query("SELECT `name` FROM `blog_list` WHERE `id` = '".$_id."';");
$blogname = mysql_result($query, 0);

echo '<div class="mnav"><a href="index.php">Bloq</a> » Şerhler</div>';
echo '<div class="layer">';

echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <a href="view_blog.php?id='.$_id.'">'.$blogname.'</a><br/><br/>';

$file_count = mysql_query("SELECT COUNT(`id`) FROM `blog_com` WHERE `bid` = '".$_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 5;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `comment`, `uid`, `date` FROM `blog_com` WHERE `bid` = '".$_id."' ORDER BY `date` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo 'Şerh yazılmayıb<br/>';
}
while($row = mysql_fetch_array($query)){
	$id = $row['id'];
	$comment = $row['comment'];
	$uid = $row['uid'];
	$date = $row['date'];
	
	$u_query = mysql_query("SELECT `nickname`, `sex` FROM `chat_users` WHERE `id` = '".$uid."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	echo '<a href="/profile.php?uid='.$uid.'"><img src="/img/icons/'.$sex_icon.'.gif" alt="."></a> '.$u_login.' ('.date('d-m-Y H:i', $date).')<br/>';
	echo $comment.'<br/><br/>';
}

if($page > 1) echo "<a href=\"comments.php?id=".$_id."&amp;page=".($page - 1)."\">« Evvelki</a> | ";
if($all_rows > $start + $show_limit) echo "<a href=\"comments.php?id=".$_id."&amp;page=".($page + 1)."\">Növbeti »</a>";
if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"comments.php?id=".$_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"comments.php?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " ".$i." ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"comments.php?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
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
		echo " <a href=\"comments.php?id=".$_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " ".$max." ";
	}
}

echo '<br/><a href="addcomment.php?id='.$_id.'">Şerh yaz</a><br/>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
