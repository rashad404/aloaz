<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$_period = checkData($_GET['period']);

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['top postlar'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('id');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=topposts">'.$__lng['giris'].'</a> | <a href="reg.php?loc=topposts">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

echo $__lng['top postu olanlar'].':<br/>';

if($_period == 'day'){
	$order_by = ' `post_day` ';
	
	echo '<a href="topposts.php">'.$__lng['cemi'].'</a> / ';
	echo $__lng['bugun'].'<br/><br/>';
}
else{
	$order_by = ' `post` ';
	echo $__lng['cemi'].' / ';
	echo '<a href="topposts.php?period=day">'.$__lng['bugun'].'</a><br/><br/>';
}

echo '<table width="100%" cellpadding="2">';

$show_limit = 7;
$all_rows = 50;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `nickname`, `sex`, `name`, `il`, `gun`, `ay`, `photo`, `status`, `post`, `post_day` FROM `chat_users` WHERE `post_run` = '0' ORDER BY ".$order_by." DESC LIMIT ".$start.", ".$show_limit.";");

while($row = mysql_fetch_array($query)){
	$uid_id = $row['id'];
	$uid_birth_day = $row['gun'];
	$uid_birth_month = $row['ay'];
	$uid_birth_year = $row['il'];
	$uid_sex = $row['sex'];
	$uid_login = $row['nickname'];
	$uid_name = $row['name'];
	$uid_photo = $row['photo'];
	$uid_status = $row['status'];
	$uid_post = $row['post'];
	$uid_post_day = $row['post_day'];
	
	if(strlen($uid_status) > 50) $uid_status = substr($uid_status, 0, 50).'...';
	
	$expPhoto = explode('|', $uid_photo);
	$photoName = $expPhoto[0];
	$photoId = $expPhoto[1];

	if($uid_sex==0){
		$uid_sex_='K';
		$uid_sex_img ='man';
	}
	else{
		$uid_sex_='Q';
		$uid_sex_img='woman';
	}

	$uid_age = floor( (strtotime(date('Y-m-d')) - strtotime("$uid_birth_year-$uid_birth_month-$uid_birth_day")) / 31556926);

	if(empty($uid_photo)) $img_file = 'img/'.$uid_sex_img.'.gif';
	else $img_file = 'photos/files/thumbs/small/'.$uid_sex.'/'.$photoName.'';
	//else $img_file = 'photos/preview.php?photo_id='.$photoId.'';
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td><a href="profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7" /></a></td>
	<td width="100%" style="line-height: 17px"><a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a> <span style="font-size:11px">('; 
	echo $uid_sex_.'/'; 
	echo ''.$uid_age.')<br/>'.$uid_status.'</span><br/>';
	if($_period != 'day') echo '<span style="font-size:11px;">'.$__lng['postlar'].':</span> <span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;">'.$uid_post.'</span>';
	else echo '<span style="font-size:11px;">'.$__lng['bugun postlar'].':</span> <span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;">'.$uid_post_day.'</span>';
	echo '</td></tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="topposts.php?period='.$_period.'&amp;page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="topposts.php?period='.$_period.'&amp;page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="topposts.php?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="topposts.php?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
			}
			else{
				echo ' <span>'.$i.'</span> ';
			}
		}
		
	}
}
if($page <= $max - $interval) echo '... ';

if($max > $interval){
	if($max != $page){
		echo ' <a href="topposts.php?period='.$_period.'&amp;page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="topposts.php?period='.$_period.'&amp;page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

echo '</div>';
include 'inc/footer.php';
?>