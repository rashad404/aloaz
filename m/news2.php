<?
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['sayt xeberleri'];
include 'inc/header.php';

echo '<div class="mnav">AloChat » <a href="news.php">'.$title.'</a></div>';
echo '<div class="layer">';

$admin_status = 0;
$checkAuth = checkAuth('`id`, `admin_status`');
if($checkAuth != 'error'){
	$userrow = mysql_fetch_array($checkAuth);
	$admin_status = $userrow['admin_status'];
}

switch($_GET['mod']){

default:

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`news`");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 8;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);

$query = mysql_query("SELECT * FROM `aloaz_db`.`news` ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$news_id = $row['id'];
	$news_title = $row['title'];
	$news_body = $row['body'];
	$news_time = $row['time'];
	
	echo '<span style="font-size: 10px">'.date('d-m-Y H:i', $news_time).'</span><br/>';
	echo '<a href="news.php?mod=read&amp;id='.$news_id.'">'.$news_title.'</a><br/><br/>';
}

if($page > 1) echo '<a id="pageButon" href ="news.php?page='.($page-1).'">&lt;</a> ';
if($page < $max) echo '<a id="pageButon" href ="news.php?page='.($page+1).'">&gt;</a>';

if($page > 1 || $page < $max) echo '<br/>';

break;


case 'read':



$_news_id = intval($_GET['id']);

$query = mysql_query("SELECT * FROM `aloaz_db`.`news` WHERE `id` = '".$_news_id."';");

if(mysql_num_rows($query) == 0){
	echo $__lng['xeber tapilmadi'].'<br/>';
	break;
}
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($admin_status == 1){
	$_del = intval($_GET['del']);
	$_commentid = intval($_GET['commentid']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="news2.php?mod=read&id='.$_news_id.'&amp;page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="news2.php?mod=read&id='.$_news_id.'&amp;commentid='.$_commentid.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		mysql_query("DELETE FROM aloaz_db.`news_comment` WHERE `id` = '".$_commentid."' AND `news_id` = '".$_news_id."' LIMIT 1;");
	}
}

$row = mysql_fetch_array($query);
$news_id = $row['id'];
$news_title = $row['title'];
$news_body = nl2br($row['body']);
$news_time = $row['time'];

echo '<span style="font-size: 10px">'.date('d-m-Y H:i', $news_time).'</span><br/>';
echo '<span style="background-color: #ebebeb">'.$news_title.'</span><br/><br/>';
echo ''.$news_body.'<br/>';

// yusif start
$file_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`news_comment` WHERE `news_id` = '".$_news_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 5;

if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
echo '<img src="/img/comment.png" alt="Şerhler" style="vertical-align:middle;"> '.$all_rows;
echo ' <a href="addnewscomment.php?id='.$_news_id.'">Şerh yaz</a><br /><br />';

$query = mysql_query("SELECT `id`, `comment`, `user_id`, `time` FROM aloaz_db.`news_comment` WHERE `news_id` = '".$_news_id."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo $__lng['sherh yazilmayib'].'<br/>';
}
while($row = mysql_fetch_array($query)){
	$comment_id = $row['id'];
	$comment = $row['comment'];
	$comment_uid = $row['user_id'];
	$comment_date = $row['time'];
	$comment = str_replace(array_keys($smilesArray), array_values($smilesArray), $comment);
 
	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$comment_uid."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$u_photo;
	
	if(date('d-m-Y', $comment_date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $comment_date);
	else if(date('d-m-Y', $comment_date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $comment_date);
	else $date_str = date('d-m-Y H:i', $comment_date);
	
	echo '<a href="/profile.php?uid='.$comment_uid.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
	if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="news2.php?mod=read&id='.$_news_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small><br/>';
	echo $comment.'<br/><br/>';
}

echo '<br/><div class="pageNav">';

if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

if($page > 1) echo '<a href ="?mod=read&id='.$_news_id.'&amp;page='.($page-1).'">&lt;</a> ';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"?mod=read&id=".$_news_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"?mod=read&id=".$_news_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " <span>".$i."</span> ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"?mod=read&id=".$_news_id."&amp;page=".$i."\">".$i."</a> ";
			}
			else{
				echo " <span>".$i."</span> ";
			}
		}
		
	}
}
if($page <= $max - 5) echo '... ';

if($max > $interval){
	if($max != $page){
		echo " <a href=\"?mod=read&id=".$_news_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " <span>".$max."</span> ";
	}
}

if($page < $max) echo '<a id="pageButon" href ="?mod=read&id='.$_news_id.'&amp;page='.($page+1).'">&gt;</a> ';
echo '</div><br/>';
//yusif end

if($_news_id == 5 || $_news_id == 22){
	echo '<br/><b>AloChat Android tetbiqini yükle:</b><br/><br/>';
	echo 'AloChat-ın inkişafı üçün işleri daima davam etdiririk.<br/>
	Qarşılaşdığınız sehvler, irad ve tekliflerle bağlı info@alo.az e-mail ünvanına yazmağınızı xahiş edirik.<br/><br/>';
	echo '<a href="https://play.google.com/store/apps/details?id=az.alo.chat"><img src="img/googleplay.gif" alt="Google Play" /></a><br/><br/>';
}

break;

}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include 'inc/footer.php';
?>
