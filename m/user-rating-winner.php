<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="user-rating.php">İstifadəçi reytinqi</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `coins`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=toprating">'.$__lng['giris'].'</a> | <a href="reg.php?loc=toprating">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$coins = $userrow['coins'];

$mod = $_GET['mod'];
switch($mod){

default:

$last = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`rating_stats` ORDER BY `id` DESC"));
if(!$last){
	echo "Qalibler hele yoxdu";
	break;
}

$winner_users = [];
$winner_users[1] = ["user_id" => $last["n1_user"],"rating" => $last["n1_rating"]]; 
$winner_users[2] = ["user_id" => $last["n2_user"],"rating" => $last["n2_rating"]]; 
$winner_users[3] = ["user_id" => $last["n3_user"],"rating" => $last["n3_rating"]]; 
$winner_users[4] = ["user_id" => $last["n4_user"],"rating" => $last["n4_rating"]]; 
$winner_users[5] = ["user_id" => $last["n5_user"],"rating" => $last["n5_rating"]];
$month = date("n",strtotime($last['date']))-1;

 
echo monthName($month).' ayının istifadəçi reytinqi. 1-ci yerin qalibinə "S-Moder", 2-ci yerin qalibinə "Moder", 3-cü yerin qalibinə "Vip" vəzifəsi hədiyyə olundu.<br/>';


echo '<table width="100%" cellpadding="2">';
 


$num = 0;

foreach($winner_users as $rating){
	$num++;
	$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day`,`rating` FROM `aloaz_db`.`user` WHERE `id` = '".$rating["user_id"]."' ;");
	
	$row = mysql_fetch_array($query);
	$uid_id = $row['id']; 
	$uid_sex = $row['sex'];
	$uid_login = $row['nickname'];
	$uid_name = $row['full_name'];
	$uid_photo = $row['profile_photo'];
	$uid_status = $row['last_post'];
	$uid_post = $row['msg_count'];
	$uid_rating = $row["rating"];
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

	if(empty($uid_photo)) $img_file = 'img/'.$uid_sex_img.'.gif';
	else $img_file = 'udata'.$uid_photo;
	
	if($num == 1) $num_bgcolor = '#f93904'; 
	else if($num == 2) $num_bgcolor = '#ed552c'; 
	else if($num == 3) $num_bgcolor = '#e3856b'; 
	else  $num_bgcolor = '#818181';
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; 
	echo '><td style="text-align: center; color: #fff; background: '.$num_bgcolor.';">'.$num.'</td><td><a href="profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:50px;" /></a></td>
	<td width="100%" style="line-height: 30px"><a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a>'; 
	echo '<br/>'; 

	echo '<span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;"><a href="?mod=voters&amp;id='.$uid_id.'">'.$rating["rating"].' səs</span><br/>';
	echo '</td></tr>';
}
echo '</table>';


break;


case 'voters':

$_id = intval($_GET['id']);

$v_user = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$_id."';");
$v_user = mysql_result($v_user, 0, 0);

echo 'İştirakçı: <a href="profile.php?uid='.$_id.'">'.$v_user.'</a><br/><br/>';

 
$m = date('n') - 1;
echo '<br/> Bu istifadəçiyə '.monthName($m).' ayı erzinde səs verənlər:<br/><br/>';
$month = date('m');
$last_month = $month - 1;

$begin_date = date("Y-$last_month-01 00:00:00");
$end_date = date("Y-$month-01 00:01:00");
$query = mysql_query("SELECT `user_id`, SUM(`coins`) FROM `aloaz_db`.`rating_logs` WHERE `user_id2` = '".$_id."' AND `date` > '".$begin_date."' AND `date` < '".$end_date."' GROUP BY `user_id` ORDER BY SUM(`coins`) DESC");
$n = 0;
while($row = mysql_fetch_array($query)){
	$voter = $row['user_id'];
	$votes = $row['SUM(`coins`)'];
	
	$n++;
	
	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$voter."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'http://alochat.com'.$u_photo;
	
	echo $n.') <a href="/profile.php?uid='.$voter.'">'.$u_login.'</a> ('.$votes.' səs)<br/>';
}

break;


}

echo '</div>';
include 'inc/footer.php';
?>