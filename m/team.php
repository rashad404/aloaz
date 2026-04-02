<?
error_reporting(0);
session_start();

$__posttopoint = 300;

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$checkAuth = checkAuth('`id`, `nickname`, `coins`, `point`, `profile_photo`, `msg_count`, `country_id`,`user_status`,`unseen`,`invisible`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=pointserv">'.$__lng['giris'].'</a> | <a href="reg.php?loc=pointserv">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$point = $userrow['coins'];
$xal = $userrow['point'];
$photo = $userrow['profile_photo'];
$post = $userrow['msg_count'];
$user_status = $userrow['user_status'];
$user_unseen = $userrow["unseen"];
$user_invisible = $userrow["invisible"];
$country = 'az';//$userrow['country_id'];

// if(intval($user_status) == 0){
	// displayError('Bu səhifəyə daxil olmağa icazəniz yoxdur. Yalnız vəzifəlilər daxil ola bilər.<br/><br/>'.
	// '<a href="pointserv.php?mod=vipuser">Vəzifə al</a>', 0);
// }
 
 $point_discount = '';
 $user_status_value = '';
if($user_status == 1) 
{
	$point_discount = '10%';
	$user_status_value = $__lng["user_status_1"];
}
elseif($user_status == 2) 
{
	$point_discount = '15%';
	$user_status_value = $__lng["user_status_2"];
}
elseif($user_status == 3)
{
	$point_discount = '20%';
	$user_status_value = $__lng["user_status_3"];
}

$user_status_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user_status` WHERE `user_id`='".$id."' and `end_time`>'".time()."' ORDER BY ID DESC LIMIT 1"));

$expPhoto = explode('|', $photo);
$photoId = $expPhoto[1];


echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Vəzifəli istifadəçilər</div>';
echo '<div class="layer">';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 10");
echo '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Admin</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = htmlspecialchars($vip_row['last_post']);
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';


$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 3");
echo '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>'.$__lng['user_status_3'].'</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = htmlspecialchars($vip_row['last_post']);
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 2");
echo '<img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>'.$__lng['user_status_2'].'</b> ('.mysql_num_rows($vip_query).'): <br/>';
echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = htmlspecialchars($vip_row['last_post']);
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 1");
echo '<img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>'.$__lng['user_status_1'].'</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = htmlspecialchars($vip_row['last_post']);
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';

echo '<br/><a href="pointserv.php?mod=team">Vəzifə almaq</a><br/>';


echo '</div>';
include 'inc/footer.php';
?>
