<?
error_reporting(0);
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="viktorina.php">Viktorina otağı</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `coins`,`question_rating`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=toprating">'.$__lng['giris'].'</a> | <a href="reg.php?loc=toprating">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$coins = $userrow['coins'];
$question_rating = $userrow['question_rating'];

$mod = $_GET['mod'];
switch($mod){

default:

echo 'Ən çox düzgün cavab verən istifadəçilər: <br/>';
$_period = checkData($_GET['period']);
 if($_period == 'all'){
	$where = 'WHERE `status`=1';
	echo $__lng['cemi'].' / ';
	echo '<a href="viktorina-top.php">'.$__lng['bugun'].'</a><br/><br/>';
	
	echo '<table width="100%" cellpadding="2">';

 
$num = 0;

$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day`,`question_rating` FROM `aloaz_db`.`user` ORDER BY `question_rating` DESC LIMIT 20");
 if(mysql_num_rows($query) == 0){
	echo 'Qeyd olunan period üzrə iştirakçı yoxdur.<br/>';
}
while($row = mysql_fetch_array($query)){
	$num++; 
	$question_rating = $row["question_rating"];	
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

	if(empty($uid_photo)) $img_file = '/img/'.$uid_sex_img.'.gif';
	else $img_file = '../udata'.$uid_photo;
	
	if($num == 1) $num_bgcolor = '#f93904'; 
	else if($num == 2) $num_bgcolor = '#ed552c'; 
	else if($num == 3) $num_bgcolor = '#e3856b'; 
	else  $num_bgcolor = '#818181';
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; 
	echo '><td style="text-align: center; color: #fff; background: '.$num_bgcolor.';">'.$num.'</td><td><a href="/profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:50px;" /></a></td>
	<td width="100%" style="line-height: 30px"><a href="/profile.php?uid='.$uid_id.'">'.$uid_login.'</a>'; 
	echo '<br/>'; 

	echo '<span style="font-size:14px; font-weight: bold; color: green;">'.$question_rating.'</span> <span style="font-size:14px;">cavab</span><br/>';
	echo '</td></tr>';
}
echo '</table>';
	
}
else{	
	$begin_date = strtotime(date("d-m-Y 0:0"));
	$end_date = strtotime(date("d-m-Y 23:59"));	
	echo '<a href="viktorina-top.php?period=all">'.$__lng['cemi'].'</a> / ';
	echo $__lng['bugun'].'<br/><br/>';
	$where = "WHERE `end_time`>'".$begin_date."' and `end_time`<'".$end_date."' and `status`=1";
	
	echo '<table width="100%" cellpadding="2">';

 
$num = 0;

$winnerQuery = mysql_query("SELECT `winner_id`,count(`id`) as c FROM `aloaz_db`.`viktorina` ".$where." GROUP BY `winner_id` ORDER BY `c` DESC LIMIT 20;");
 if(mysql_num_rows($winnerQuery) == 0){
	echo 'Qeyd olunan period üzrə iştirakçı yoxdur.<br/>';
}
while($row = mysql_fetch_array($winnerQuery)){
	$num++;
	$winner_id = $row["winner_id"];
	$question_rating = $row["c"];
	
	$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day` FROM `aloaz_db`.`user` WHERE `id`='".$winner_id."' LIMIT 1");
	$row = mysql_fetch_assoc($query);
	
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

	if(empty($uid_photo)) $img_file = '/img/'.$uid_sex_img.'.gif';
	else $img_file = '../udata'.$uid_photo;
	
	if($num == 1) $num_bgcolor = '#f93904'; 
	else if($num == 2) $num_bgcolor = '#ed552c'; 
	else if($num == 3) $num_bgcolor = '#e3856b'; 
	else  $num_bgcolor = '#818181';
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; 
	echo '><td style="text-align: center; color: #fff; background: '.$num_bgcolor.';">'.$num.'</td><td><a href="/profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:50px;" /></a></td>
	<td width="100%" style="line-height: 30px"><a href="/profile.php?uid='.$uid_id.'">'.$uid_login.'</a>'; 
	echo '<br/>'; 

	echo '<span style="font-size:14px; font-weight: bold; color: green;">'.$question_rating.'</span> <span style="font-size:14px;">cavab</span><br/>';
	echo '</td></tr>';
}
echo '</table>';
}



break;

}

echo '</div>';
include '../inc/footer.php';
?>