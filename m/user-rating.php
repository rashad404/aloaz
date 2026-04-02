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
echo '<a href="user-rating-winner.php">Keçen ayın qalibleri</a><br /><br />';
echo monthName().' ayının istifadəçi reytinqinə start verildi. 1-ci yerin qalibinə "S-Moder", 2-ci yerin qalibinə "Moder", 3-cü yerin qalibinə "Vip" vəzifəsi hədiyyə olunacaq.<br/>';
echo 'Qaliblər '.date('t').' '.monthName().' saat 23:59 tarixində müəyyən olunacaq.<br/><br/>';  

echo '<table width="100%" cellpadding="2">';

$order_by = ' `rating` '; 

$show_limit = 10;
$all_rows = mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `rating`>0"));
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day`,`rating` FROM `aloaz_db`.`user` WHERE `rating` > '0' ORDER BY ".$order_by." DESC LIMIT ".$start.", ".$show_limit.";");

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
	else $img_file = 'https://m.alo.az/udata'.$uid_photo;
	
	if($num == 1) $num_bgcolor = '#f93904'; 
	else if($num == 2) $num_bgcolor = '#ed552c'; 
	else if($num == 3) $num_bgcolor = '#e3856b'; 
	else  $num_bgcolor = '#818181';
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; 
	echo '><td style="text-align: center; color: #fff; background: '.$num_bgcolor.';">'.$num.'</td><td><a href="profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:50px;" /></a></td>
	<td width="100%" style="line-height: 30px"><a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a>'; 
	echo '<br/>'; 

	echo '<span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;"><a href="?mod=voters&amp;id='.$uid_id.'">'.$uid_rating.' səs</span><br/>';
	echo '</td></tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="?period='.$_period.'&amp;page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="?period='.$_period.'&amp;page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="?period='.$_period.'&amp;page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="?period='.$_period.'&amp;page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

break;


case 'voters':

$_id = intval($_GET['id']);

$v_user = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$_id."';");
$v_user = mysql_result($v_user, 0, 0);

echo 'İştirakçı: <a href="profile.php?uid='.$_id.'">'.$v_user.'</a><br/><br/>';

if(isset($_POST['rating_submit'])){
	$rating_post = intval($_POST["rating"]);
	$from_id = $id;
	$to_id = $_id;

	if($rating_post<1){
		$error = $__lng['minium rating vermek'].'.<br/>';
	}
	if($rating_post>1000){
		$error = $__lng['maksimum rating vermek'].'.<br/>';
	}
	if($rating_post>$coins){
		$error = $__lng['hesabda bal yoxdur'].'.<br/>';
	}	

	if(!empty($error)){
		echo '<span style="color:red;">'.$__lng['sehv'].':</span> '.$error;
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break; 
	}		
	
	$update1 = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`=`coins`-".$rating_post." WHERE `id`='".$from_id."'");
	$update2 = mysql_query("UPDATE `aloaz_db`.`user` SET `rating`=`rating`+".$rating_post." WHERE `id`='".$to_id."'");
 
	$insert = mysql_query("INSERT INTO `aloaz_db`.`rating_logs` SET `user_id`='".$from_id."',`user_id2`='".$to_id."',`coins`='".$rating_post."',`rating`='".$rating_post."',`date`='".date("Y-m-d H:i:s")."'");
	echo $send_login.' loqinine '.$rating_post.' ses verildi. Hesabınızdan '.$rating_post.' bal çıxıldı.<br/>';
}
else{
	echo ' <form action="" method="post">
		<select name="rating">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option value="40">40</option>
			<option value="50">50</option>
			<option value="100">100</option>
			<option value="200">200</option>
			<option value="500">500</option>
			<option value="1000">1000</option>
		</select>
		<input type="submit" name="rating_submit" value="Ses ver">
	</form>';
}

echo '<br/> Bu istifadəçiyə səs verənlər:<br/><br/>';

$query = mysql_query("SELECT `user_id`, SUM(`coins`) FROM `aloaz_db`.`rating_logs` WHERE `user_id2` = '".$_id."' AND `date` > '".date('Y-m-01 00:00:00')."' GROUP BY `user_id` ORDER BY SUM(`coins`) DESC");
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
	else $img_file = 'https://m.alo.az/udata'.$u_photo;
	
	echo $n.') <a href="/profile.php?uid='.$voter.'">'.$u_login.'</a> ('.$votes.' səs)<br/>';
}

break;


}

echo '</div>';
include 'inc/footer.php';
?>