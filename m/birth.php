<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$_period = checkData($_GET['period']);

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Ad günü olanlar</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('id');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=topposts">'.$__lng['giris'].'</a> | <a href="reg.php?loc=topposts">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

echo 'Ad günü olan istifadəçilərimizi təbrik edirik!<br/><br/>';

echo '<table width="100%" cellpadding="2">';

$all_rows = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `birthday` LIKE '_____".date('m-d')."'"), 0);

$show_limit = 10;

if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day` FROM `aloaz_db`.`user` WHERE `birthday` LIKE '_____".date('m-d')."' ORDER BY `id` ASC LIMIT ".$start.", ".$show_limit.";");

$num = $start;

while($row = mysql_fetch_array($query)){
	$num++;
	
	$uid_id = $row['id']; 
	$uid_sex = $row['sex'];
	$uid_login = $row['nickname'];
	$uid_name = $row['full_name'];
	$uid_photo = $row['profile_photo'];
	$uid_status = $row['last_post'];
	$uid_post = $row['msg_count'];
	$uid_post_day = $row['msg_count_day'];
	$uid_age = $row["age"];
	if(strlen($uid_status) > 50) $uid_status = substr($uid_status, 0, 50).'...';

	if($uid_sex==0){
		$uid_sex_='K';
		$uid_sex_img ='man';
	}
	else{
		$uid_sex_='Q';
		$uid_sex_img='woman';
	}

	//$uid_age = floor( (strtotime(date('Y-m-d')) - strtotime("$uid_birth_year-$uid_birth_month-$uid_birth_day")) / 31556926);

	if($num == 1) $num_bgcolor = '#f93904'; 
	else if($num == 2) $num_bgcolor = '#ed552c'; 
	else if($num == 3) $num_bgcolor = '#e3856b'; 
	else  $num_bgcolor = '#818181';
	
	if(empty($uid_photo)) $img_file = 'img/'.$uid_sex_img.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$uid_photo;
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td><a href="profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a></td>
	<td width="100%" style="line-height: 17px"><a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a> <span style="font-size:11px">('; 
	echo $uid_sex_.')<br/>'; 
	echo $uid_status.'</span><br/>';
	echo '<span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;">'.$uid_age.' yaş</span>';
	echo '</td></tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="birth.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="birth.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="birth.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="birth.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="birth.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="birth.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

echo '</div>';
include 'inc/footer.php';
?>