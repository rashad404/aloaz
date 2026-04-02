<?
error_reporting(0);
session_start();

$__posttopoint = 300;

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
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
$admin_status = 0;
if(intval($user_status) == 0){
	displayError('Bu səhifəyə daxil olmağa icazəniz yoxdur. Yalnız vəzifəlilər daxil ola bilər.<br/><br/>'.
	'<a href="pointserv.php?mod=vipuser">Vəzifə al</a>', 0);
}
 
 $point_discount = '';
 $user_status_value = '';
if($user_status == 1) 
{
	$point_discount = '10%';
	$user_status_value = $__lng["user_status_1"];
	$__elan_limit = 1;
}
elseif($user_status == 2) 
{
	$point_discount = '15%';
	$user_status_value = $__lng["user_status_2"];
	$__elan_limit = 2;
}
elseif($user_status == 3)
{
	$point_discount = '20%';
	$user_status_value = $__lng["user_status_3"];
	$__elan_limit = 3;
}
elseif($user_status == 10)
{
	$admin_status = 1;
	$point_discount = '20%';
	$user_status_value = $__lng["user_status_10"];
	$__elan_limit = 10;
}

if($id==1129446){
	$admin_status=1;
}

$user_status_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user_status` WHERE `user_id`='".$id."' and `end_time`>'".time()."' ORDER BY ID DESC LIMIT 1"));

$expPhoto = explode('|', $photo);
$photoId = $expPhoto[1];

$mod = $_GET['mod'];

switch($mod){

default:

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$user_status_value.' səhifəsi </div>';
echo '<div class="layer">';

echo $__lng['Sizin status'].': '.$user_status_value.'<br/>';
echo $__lng['bitme tarixi'].': '.date("d-m-Y H:i",$user_status_row["end_time"]).'<br/><br/>';
echo '<a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].' ('.$point_discount." ".$__lng["bonus"].')</a><br/><br/>';

echo '<form action="http://m.alo.az/ban.php?mod=request" method="post">
Loqin:<br/>
<input type="text" name="b_login" value="" /><br/>
<input type="submit" name="submit" value="Ban et" />
</form><br/>';
echo '- <a href="team-panel.php?mod=room">'.$__lng["otaq mesajlari silmek"].'</a><br/>';
echo '- <a href="team-panel.php?mod=elan">Elan elave etmek</a><br/>';
echo '- <a href="ban.php">'.$__lng['ban olanlar'].'</a><br/>';
echo '- <a href="team-panel.php?mod=unseen">'.$__lng['profile baxanda qeyde alinmasin'].'</a><br/>';
echo '- <a href="team-panel.php?mod=invisible">'.$__lng['online gorunmemek'].'</a><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'list':

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Vəzifəli istifadəçilər</div>';
echo '<div class="layer">';


$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 3");
echo '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Boss</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
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
echo '<img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Moder</b> ('.mysql_num_rows($vip_query).'): <br/>';
echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
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
echo '<img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Vip</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
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

echo '<br/><a href="pointserv.php?mod=vipuser">Vəzifə almaq</a><br/>';

break;

 

case 'unseen';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['profile baxanda qeyde alinmasin'].'</div>';
echo '<div class="layer">';
	if(intval($user_status) == 0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}


if($_POST['submit'] == ''){
	echo $__lng['profile baxanda qeyd olunmamaq haqqinda'].'<br/>'; 
	echo $__lng['xidmetin deyeri'].': '.$__lng['vip istifadeciler ucun pulsuz'].'<br/>';
 
	echo '<br/>';
	
	echo '<form name="form" method="post" action="team-panel.php?mod=unseen">';

	if($user_unseen == 1){
		//echo $__lng["bu xidmet sizde aktivdir. deaktiv ucun tesdiqleyin"];
		echo 'Xidmət aktivdir.<br/><br/>';
		echo '<input type="submit" name="submit" value="Deaktiv et" /><br/>';
	}else{
		//echo $__lng["bu xidmeti aktiv etmek ucun emeliyyati tesdiqleyin"];
		echo 'Xidmət deaktivdir.<br/><br/>';
		echo '<input type="submit" name="submit" value="Aktiv et" /><br/>';
	}

	
	
	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{
	if($user_unseen == 1)
	{
		$new_unseen_status = 0;
		$text_unseen = $__lng['profile girende gorunmemek xidmeti deaktivlesdirdiz'];
	}
	else{
		$new_unseen_status = 1;
		$text_unseen = $__lng['profile girende gorunmemek xidmeti aktivlesdirdiz'];
	}
	
	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `unseen` = '".$new_unseen_status."' WHERE `id` = '".$id."' LIMIT 1;");

	if($update){
		if(mysql_affected_rows()>0) echo $text_unseen.'<br/>'; else echo 'Databse error [1126]<br/>';
	}
	else{
		echo 'Databse error [1127]<br/>';
	}
}

break;

case 'invisible';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['online gorunmemek'].'</div>';
echo '<div class="layer">';
	if(intval($user_status) == 0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}

if($_POST['submit'] == ''){
	echo $__lng['onlinede gorsenmemek haqqinda'].'<br/>'; 
	echo $__lng['xidmetin deyeri'].': '.$__lng['vip istifadeciler ucun pulsuz'].'<br/>';
 
	echo '<br/>';
	
	echo '<form name="form" method="post" action="team-panel.php?mod=invisible">';
	
	if($user_invisible == 1){
		//echo $__lng["bu xidmet sizde aktivdir. deaktiv ucun tesdiqleyin"];
		echo 'Xidmət aktivdir.<br/><br/>';
		echo '<input type="submit" name="submit" value="Deaktiv et" /><br/>';
	}else{
		//echo $__lng["bu xidmeti aktiv etmek ucun emeliyyati tesdiqleyin"];
		echo 'Xidmət deaktivdir.<br/><br/>';
		echo '<input type="submit" name="submit" value="Aktiv et" /><br/>';
	}

	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{

	if($user_invisible == 1){
		$new_invisible_status = 0;
		$text_invisible = $__lng['onlinede gorunmemek xidmeti deaktivlesdirdiz'];
	}
	else{
		$new_invisible_status = 1;
		$text_invisible = $__lng['onlinede gorunmemek xidmeti aktivlesdirdiz'];
	}

	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `invisible` = '".$new_invisible_status."' WHERE `id` = '".$id."' LIMIT 1;");

	if($update){
		if(mysql_affected_rows()>0) echo $text_invisible.'<br/>'; else echo 'Databse error [1126]<br/>';
	}
	else{
		echo 'Databse error [1127]<br/>';
	}
}

break;

 

case 'room';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng["otaq mesajlari silmek"].'</div>';
echo '<div class="layer">';
	if(intval($user_status) == 0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}

if($_POST['submit'] == ''){
	echo $__lng['otaq mesajlarini silmek haqqinda'].'<br/>'; 
  
	echo '<br/>';
	
	echo '<form name="form" method="post" action="team-panel.php?mod=room">';
		echo $__lng["otaq sec"].':<br/>';
		echo '<select name="room_id">';
			$rooms_query = mysql_query("SELECT `id`,`name` FROM `aloaz_db`.`room` WHERE `type`=1 and `id`!=10"); 
			while($room = mysql_fetch_assoc($rooms_query)){
				echo '<option value='.$room["id"].'>'.$room["name"].'</option>';
			}
		echo '</select> <br/><br/>';
		echo '<input type="submit" name="submit" value="Mesajları sil" /><br/>';

	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{
	$_room_id = intval($_POST["room_id"]);
	$room = mysql_fetch_assoc(mysql_query("SELECT `id`,`name`,`type` FROM `aloaz_db`.`room` WHERE `id`='".$_room_id."' and `type`=1"));
	if($room["type"]==1){
		if($admin_status==1){
			$delete = mysql_query("DELETE FROM `aloaz_db`.`room_msgs` WHERE `rid`='".$_room_id."'");
			$_message = 'Admin terefinden otaqdakı mesajlar silinmişdir.';
			mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = 'Alochat', `message` = '".$_message."', `uid` = '1', `rid` = '".$_room_id."', `time` = '".time()."'");
			$textLog = $login.' niki  '.$room["name"].' otaqdakı mesajlari silmişdir.';
			mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`nickname`='".$login."',`to_id`='".$_room_id."',`text`='".$textLog."',`date`='".date("Y-m-d H:i:s")."'");
			if($delete) echo 'Silindi <br/>'; else echo 'Databse error [1126]<br/>';
		}else{
			if($room["id"]!=10 and $room["type"]==1){
				$_message = 'Otaqdakı yazılar 1 deqiqeden sonra '.$login.' terefinden silinecek.';
				mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = 'Alochat', `message` = '".$_message."', `uid` = '1', `rid` = '".$_room_id."', `time` = '".time()."'");
				$del_time = time() + 60;
				mysql_query("UPDATE `aloaz_db`.`room` SET `del_time`='".$del_time."',`del_nickname`='".$login."' WHERE `id`='".$_room_id."' LIMIT 1");
				$textLog = $login.' niki  '.$room["name"].' otaqdakı mesajlari silmişdir.';
				mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`nickname`='".$login."',`to_id`='".$_room_id."',`text`='".$textLog."',`date`='".date("Y-m-d H:i:s")."'");
				echo 'Silindi <br/>'; 
			}else{
				echo "Bu otağı sile bilmezsiniz";
			}
			
		}	

	}else{
		echo 'Otaq tapılmadı';
	}
	  
}

break;


case 'elan':

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="team-panel.php?mod=elan">Elan elave et</a></div>';
echo '<div class="layer">';

if($_POST['submit'] == ''){
	echo '<form name="form" method="post" action="team-panel.php?mod=elan">';
	echo 'Başlıq:<br/>';
	echo '<input type="text" name="title" /><br/>';

	echo 'Elan:<br/>';
	echo '<input type="text" name="body" /><br/>';

	echo '<input type="submit" name="submit" value="Elave et" /><br/>';
	echo '</form>';
}
else{
	$_title = trim(mysql_real_escape_string($_POST["title"]));
	$_body = trim(mysql_real_escape_string($_POST["body"]));
	
	if(strlen($_title) < 3) $error = 'Başlıq minimum 3 herfden ibaret olmalıdır<br/>';
	if(strlen($_body) < 10) $error = 'Elan minimum 10 herfden ibaret olmalıdır<br/>';
	
	if(empty($error)){
		$checkDupQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`elan` WHERE `title` = '".$_title."' AND `body` = '".$_body."' AND `uid` = '".$id."' AND `time` > '".(time()-3600)."'");
		if(mysql_num_rows($checkDupQuery) > 0) $error = 'Bu elan elave olunmuşdur. Tekrar elan. Yeni elan üçün başlıq ve metni deyişin.<br/>';
		
		$countElan = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`elan` WHERE `uid` = '".$id."' AND `time` > '".strtotime('today')."'"), 0);
		if($countElan >= $__elan_limit) $error = $user_status_value.' vezifesine malik istifadeçiler gün erzinde maksimum <b>'.$__elan_limit.'</b> elan yaza biler. Limiti keçmisiniz. Növbeti gün elan yaza bilersiniz.<br/>';
	}

	echo mysql_error($checkDupQuery);
	
	if(!empty($error)){
		echo $error;
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	
	mysql_query("INSERT INTO `aloaz_db`.`elan` SET `title` = '".$_title."', `body` = '".$_body."', `uid` = '".$id."', `time` = '".time()."'");
	if(mysql_affected_rows() > 0) echo 'Elan müveffeqiyyetle elave olundu.<br/>';
	echo mysql_error();
}


break;

}
if(!empty($mod)){
	//echo '<br/><a href="pointserv.php">'.$__lng['bal xidmetleri'].'</a><br/>';
}
echo '</div>';
include 'inc/footer.php';
?>
