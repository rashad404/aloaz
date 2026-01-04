<?
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['sayt xeberleri'];
include 'inc/header.php';

echo '<div class="mnav">AloChat » <a href="news.php">'.$title.'</a></div>';
echo '<div class="layer">';

switch($_GET['mod']){

default:

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `news`");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 8;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);

$query = mysql_query("SELECT * FROM `news` ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
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

$query = mysql_query("SELECT * FROM `news` WHERE `id` = '".$_news_id."';");

if(mysql_num_rows($query) == 0){
	echo $__lng['xeber tapilmadi'].'<br/>';
	break;
}

$row = mysql_fetch_array($query);
$news_id = $row['id'];
$news_title = $row['title'];
$news_body = nl2br($row['body']);
$news_time = $row['time'];

echo '<span style="font-size: 10px">'.date('d-m-Y H:i', $news_time).'</span><br/>';
echo '<span style="background-color: #ebebeb">'.$news_title.'</span><br/><br/>';
echo ''.$news_body.'<br/>';

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
