<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/smiles.php';
include '../inc/stickers.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

$_rid = intval($_GET['rid']);

$roomQuery = mysql_query("SELECT `name`, `view`, `uid`, `login`,`type`,`del_time`,`del_nickname` FROM `aloaz_db`.`room` WHERE `id` = '".$_rid."' and `id`!=10");
if(mysql_num_rows($roomQuery) == 0){
	echo $__lng['otaq tapilmadi'].'.<br/>';
	echo '</div>';
	include '../inc/footer.php';
	exit;
}
$rooRow = mysql_fetch_array($roomQuery);
$roomName = $rooRow['name'];
$roomView = $rooRow['view'];
$roomUid = $rooRow['uid'];
$roomAdmin = $rooRow['login'];
$roomType = $rooRow['type'];
$roomDelTime = $rooRow['del_time'];
$roomDelNickname = $rooRow['del_nickname'];


echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`, `post_run`, `msg_count_day`, `coins`,`mysmile`,`room_refresh`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}

if($roomDelTime>0 and $roomDelTime<=time()){
	$delete = mysql_query("DELETE FROM `aloaz_db`.`room_msgs` WHERE `rid`='".$_rid."'");
	$_message = 'Otaqdakı yazılar '.$roomDelNickname.' terefinden silindi.';
	mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = 'Alochat', `message` = '".$_message."', `uid` = '1', `rid` = '".$_rid."', `time` = '".time()."'");
	mysql_query("UPDATE `aloaz_db`.`room` SET `del_time`=0,`del_nickname`='' WHERE `id`='".$_rid."' LIMIT 1");
}		



$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$post_run = $userrow['post_run'];
$post_day = $userrow['msg_count_day'];
$point = $userrow['coins'];
$mysmile = $userrow["mysmile"];
$roomUserRefreshTime = $userrow['room_refresh'];


$countRoomOnline = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `place` = '".$_rid."' AND `last_activity` > '".(time()-600)."'"), 0);

mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$countRoomOnline."', `refresh` = '".time()."' WHERE `id` = '".$_rid."' LIMIT 1");

echo '<p align="center"><b><a href="msgs.php?rid='.$_rid.'">'.$roomName.'</a></b> otağı <img src="/img/online.png" alt="on" /><span style="color: green">'.$countRoomOnline.'</span><br/>';
  
if($roomType==0){
	echo $__lng['otagin admini'].': <a href="../profile.php?uid='.$roomUid.'">'.$roomAdmin.'</a><br/>';
}
echo '</p>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) echo '<a href="../messages.php?mod=unread"><img src="/img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni mesajin var'].'<br/><br/>';
 

	$t   = time()-600;
	$usersQuery = mysql_query("SELECT `nickname`,`id`,`sex`,`profile_photo`,`invisible`,`last_activity`,`last_post`,`age` FROM `aloaz_db`.`user` WHERE `place` = '".$_rid."' AND `last_activity` > '".$t."' ORDER BY `id` ASC");
	$roomOnline = mysql_num_rows($usersQuery);
	echo '<table width="100%" cellpadding="3" cellspacing="0">';
	while($usersRow = mysql_fetch_array($usersQuery)){
		$user_id = $usersRow['id'];
		$usersLogin = $usersRow['nickname'];
		$user_sex = $usersRow["sex"];
		$user_photo = $usersRow['profile_photo'];
		$user_invisible = $usersRow['invisible'];
		$user_time = $usersRow['last_activity']; 
		$user_status = $usersRow['last_post'];
		$user_age = $usersRow["age"];
		if($user_sex==0){
		$user_sex_ = 'K';
		$user_sex_img ='man';
		}
		else{
			$user_sex_ = 'Q';
			$user_sex_img='woman';
		}
		if($user_invisible==1){
		$lastOnline = '<span style="font-size:11px; color: green;">Gizli</span>';
		}else{
			if($user_time > (time()-600)) $lastOnline = '<span style="font-size:11px; color: green;">Online</span>'; else $lastOnline = '<span style="font-size:11px;">'.date('Y-m-d H:i',$user_time).'</span>';
		}
		
		if(strlen($user_status) > 50) $user_status = substr($user_status, 0, 50).'...';
		
		if(empty($user_photo)) $img_file = '../img/'.$user_sex_img.'.gif';
		else $img_file = 'http://alochat.com'.$user_photo;
		
		
		echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td width="1%"><a href="/profile.php?uid='.$user_id.'&amp;rid='.$_rid.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px; height:60px;" /></a></td>';
		echo '<td width="80%" style="line-height: 17px"><a href="/profile.php?uid='.$user_id.'&amp;rid='.$_rid.'">'.$usersLogin.'</a> <span style="font-size:11px">('.$user_sex_.'/'.$user_age.')<br/>
		'.$user_status; 
		
		
		echo '</span></td>';
		echo '</tr>';
		
		//echo '<a href="../profile.php?uid='.$usersRow["id"].'&amp;rid='.$_rid.'">'.$usersLogin.'</a><br />';	
	}
	echo '<table>';	

 

echo '</div>';
include '../inc/footer.php'; 
if(intval($roomUserRefreshTime)>0){
	$time_refresh = $roomUserRefreshTime*1000;
	echo '<script type="text/javascript">
  
setTimeout(function(){
	if(document.forms["room-form"]["message"].value == ""){
	 window.location.replace(window.location.href); 
	}  
   
}, '.$time_refresh.');
</script>';
}


?>
