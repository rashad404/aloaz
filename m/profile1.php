<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=profile">'.$__lng['giris'].'</a> | <a href="reg.php?loc=profile">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];

$_uid = intval($_GET['uid']);
$query = mysql_query("SELECT * FROM `chat_users` WHERE `id` = '".$_uid."';");

if(mysql_num_rows($query) == 0){
	echo $__lng['istifadeci tapilmadi'].'<br/>';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}

$row = mysql_fetch_array($query);
$uid = $row['id'];
$u_login = $row['nickname'];
$u_name = $row['name'];
$u_sex = $row['sex'];
$u_birthday = $row['birthday'];
$u_photo = $row['photo'];
$u_status = stripslashes($row['status']);
$u_weight = $row['weight'];
$u_height = $row['height'];
$u_about = stripslashes($row['about']);
$u_time = $row['time'];
$u_created_at = $row['created_at'];
$u_post = $row['post'];
$u_post_run = $row['post_run'];
$u_post_day = $row['post_day'];
$u_friends = $row['friends'];

$u_regdate = date('Y-m-d', $u_created_at);

if($u_sex==0){
	$u_sex_l = 'K';
	$u_sex_img = 'man';
	$u_sex_txt = $__lng['kisi'];
}
else{
	$u_sex_l = 'Q';
	$u_sex_img = 'woman';
	$u_sex_txt = $__lng['qadin'];
}

$expPhoto = explode('|', $u_photo);
$photoName = $expPhoto[0];
$photoId = $expPhoto[1];

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="img/smiles/'.$value.'.png" alt="'.$key.'" />';
}

$u_about = str_replace(array_keys($smilesArray), array_values($smilesArray), $u_about);

if(empty($u_photo)) $img_file = '<img src="img/'.$u_sex_img.'.gif" alt="man" style="border: 1px solid #d7d7d7" />';
else $img_file = '<a href="photos/details.php?photo_id='.$photoId.'"><img src="photos/files/thumbs/small/'.$u_sex.'/'.$photoName.'" alt="man" style="border: 1px solid #d7d7d7" /></a>';
//else $img_file = '<a href="photos/details.php?photo_id='.$photoId.'"><img src="photos/preview.php?photo_id='.$photoId.'" alt="man" style="border: 1px solid #d7d7d7" /></a>';

$_rid = intval($_GET['rid']);

$checkFriendQuery = mysql_query("SELECT `uid` FROM `chat_friends` WHERE (`id` = '".$id."' AND `uid` = '".$_uid."') OR (`id` = '".$_uid."' AND `uid` = '".$id."');");
if(mysql_num_rows($checkFriendQuery) == 0) $checkFriend = false; else $checkFriend = true;

if($_rid > 0){
	echo '<br/>'.$u_login.' '.$__lng['otaga mesaj'].':<br/>';
	echo '<form action="room/msgs.php?rid='.$_rid.'" method="post">';
	echo '<a href="/smiles.php"><img src="/img/smiles/1f603.png" alt="Smile" /></a> <input type="text" name="message" /> ';
	echo '<select name="type">';
	echo '<option value="0">'.$__lng['umumi'].'</option>';
	echo '<option value="1">'.$__lng['gizli'].'</option>';
	echo '</select> ';
	echo '<input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /><br/>';
	echo '<input type="hidden" name="action" value="send" />';
	echo '<input type="hidden" name="to" value="'.$uid.'" />';
	echo "</form><br/>";
}else{

	if($uid != $id){
		if($u_friends==0 && !$checkFriend){
			echo $__lng['yalniz dostlardan msj q e'].'.<br/><br/>';
		}
		else{
			echo '<form action="messages.php?mod=messaging&amp;uid='.$uid.'&amp;back='.$_back.'" method="post">';
			echo '<a href="smiles.php"><img src="img/smiles/1f603.png" alt="Smile" /></a> <input type="text" name="message" /> ';
			echo '<input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /> <a href="attach/sendmessage.php?uid='.$uid.'"><img src="img/camera.png" alt="attach" /></a><br/>';
			echo '<input type="hidden" name="action" value="send" />';
			echo "</form>";
			echo '<div style="padding:5px 0 0 25px;"><a href="messages.php?mod=messaging&amp;uid='.$uid.'">Söhbet arxivi</a></div>';
			echo '<hr style="border: none;height: 1px;color: #E3E2E2;background-color: #E3E2E2;"/>';
		}
	}
}

echo '<table width="100%" cellpadding="2">';
echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td>'.$img_file.'</td>
<td width="100%">'.$u_login.''; 
echo '</td></tr>';
echo '</table>';

echo '
<table cellspacing="0" cellpadding="0" style="margin-top:10px;">
	<tr>
		<td class="info_params">'.$__lng['ad'].'</td>
		<td class="info_value">'.$u_name.'</td>
	</tr>
	<tr>
		<td class="info_params">'.$__lng['cins'].'</td>
		<td class="info_value">'.$u_sex_txt.'</td>
	</tr>
	<tr>
		<td class="info_params">'.$__lng['tevellud'].'</td>
		<td class="info_value">'.$u_birthday.'</td>
	</tr>';
if($u_weight > 20){
	echo '
	<tr>
		<td class="info_params">'.$__lng['cheki'].'</td>
		<td class="info_value">'.$u_weight.'</td>
	</tr>';
}
if($u_height > 100){
	echo '
	<tr>
		<td class="info_params">'.$__lng['boy'].'</td>
		<td class="info_value">'.$u_height.'</td>
	</tr>';
}
echo '
	<tr>
		<td class="info_params">'.$__lng['status'].'</td>
		<td class="info_value">'.$u_status.'</td>
	</tr>
	<tr>
		<td class="info_params">'.$__lng['haqqinda'].'</td>
		<td class="info_value">'.$u_about.'</td>
	</tr>';
	$roomQuery = mysql_query("SELECT `id`, `name` FROM `room` WHERE `uid` = '".$_uid."'");
	if(mysql_num_rows($roomQuery) > 0){
		$roomRow = mysql_fetch_array($roomQuery);
	echo '<tr>
		<td class="info_params">'.$__lng['Otagi'].'</td>
		<td class="info_value"><img src="img/room.png" alt="." /> <a href="room/msgs.php?rid='.$roomRow['id'].'">'.$roomRow['name'].'</a></td>
	</tr>';
	}
	if($u_post_run == 0){
		echo '<tr>
		<td class="info_params">'.$__lng['postlar'].'</td>
		<td class="info_value">'.$__lng['cemi'].': '.$u_post.' / '.$__lng['bugun'].': '.$u_post_day.'</td>
		</tr>';
	}
	
	echo '<tr>
		<td class="info_params">'.$__lng['qeyd oldugu tarix'].'</td>
		<td class="info_value">'.$u_regdate.'</td>
	</tr>
	<tr>
		<td class="info_params">'.$__lng['qeyd olma gunu'].'</td>
		<td class="info_value">'.round((time()-$u_created_at)/86400).' gün </td>
	</tr>
	<tr>
		<td class="info_params">'.$__lng['sonuncu defe daxil olub'].'</td>
		<td class="info_value">'.($u_time>time() ? '<span style="color:green;">'.$__lng['onlayn'].'</span>' : date('Y-m-d H:i', $u_time-600)).'</td>
	</tr>
</table>
';

if($uid != $id){
	echo '<br/><a class="button" href="messages.php?mod=messaging&amp;uid='.$uid.'">'.$__lng['mesaj yaz'].'</a><br/>';

	if(!$checkFriend) echo '<a class="button" href="frequest.php?mod=send&amp;uid='.$uid.'">'.$__lng['dostluq teklif et'].'</a><br/>'; else echo '<a class="button" href="frequest.php?mod=del&amp;uid='.$uid.'">'.$__lng['dostluq legvi'].'</a><br/>';

	if(!$checkFriend){
		$checkBlockQuery = mysql_query("SELECT `uid` FROM `chat_blocks` WHERE `id` = '".$id."' AND `uid` = '".$_uid."';");
		if(mysql_num_rows($checkBlockQuery) == 0) echo '<a class="button" href="block.php?mod=request&amp;uid='.$uid.'">'.$__lng['qadaga qoy'].'</a><br/>'; else echo '<a class="button" href="block.php?mod=del&amp;uid='.$uid.'">'.$__lng['qadaga legvi'].'</a><br/>';
	}
	
	$checkVisitorsQuery = mysql_query("SELECT `id` FROM `chat_visitors` WHERE `visitor_id` = '".$id."' AND `uid` = '".$_uid."'");
	if(mysql_num_rows($checkVisitorsQuery) > 0){
		mysql_query("UPDATE `chat_visitors` SET `time` = '".time()."', `count` = `count` + 1 WHERE `visitor_id` = '".$id."' AND `uid` = '".$_uid."'");
	}
	else{
		mysql_query("INSERT INTO `chat_visitors` SET `visitor` = '".$login."', `visitor_id` = '".$id."', `uid` = '".$_uid."', `time` = '".time()."'");
	}
	
	echo '<br/><a href="report.php?uid='.$uid.'"><img src="img/abuse.png" alt="." /> '.$__lng['shikayet et'].'</a><br/>';
}
else{
	echo '<br/><a class="button" href="profile_edit.php">'.$__lng['profilini deyis'].'</a><br/>';
}

//SHARES

if(!$checkFriend && $uid != $id) $ins_permission = " AND `permission` != '1'";
$shareQuery = mysql_query("SELECT `id`, `user_id` as uid, `text`, `attach`, `permission`,`like_count` as `likes`,`time` as `date` FROM aloaz_db.`share` WHERE `user_id` = '".$_uid."' ".$ins_permission." ORDER BY `date` DESC LIMIT 3;");

if(mysql_num_rows($shareQuery)>0){
	echo '<br/>'.$__lng['son paylasdiqlari'].':<br/>';
	while($row = mysql_fetch_array($shareQuery)){
		$shareId = $row['id'];
		$uid = $row['uid'];
		$text = stripslashes($row['text']);
		$date = $row['date'];
		$attach = $row['attach'];
		$permission = $row['permission'];
		$share_likes = $row['likes'];
		
		if($permission == 1) $permissionImg = '<img src="/img/share_friends_gray.gif" alt="'.$__lng['yalniz dostlarla paylasib'].'" />'; else $permissionImg = '<img src="/img/share_public_gray.gif" alt="'.$__lng['hamiyla paylasib'].'" />';
		
		if(date('d-m-Y', $date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $date);
		else if(date('d-m-Y', $date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $date);
		else $date_str = date('d-m-Y H:i', $date);
		
		if(empty($attach)) $strLentValue = 200; else $strLentValue = 80;
		if(strlen($text) > $strLentValue){
			$s = substr($text, 0, $strLentValue);
			$text = replaceLatin_E(substr($s, 0, strrpos($s, ' '))).' ...';
		}
		if(empty($attach))$text = str_replace("\n", '<br/>', $text);
		
		$count_comms = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`share_comment` WHERE `sid` = '".$shareId."';"), 0);
		 
		echo '<div class="content">';

		if(!empty($attach)) echo '<a href="share/view.php?id='.$shareId.'"><img src="https://m.alo.az/udata/images/share/resized/'.date('Ym',$date).'/'.$attach.'" alt="." style="float:left; padding-right:5px; max-height: 86px" width="90" /></a>';
		
		$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
		$u_login = mysql_result($u_query, 0);
		echo '<a href="/profile.php?uid='.$uid.'">'.$u_login.'</a> '.$__lng['paylasdi'].'<br/>';
		echo '<small>'.$date_str.'</small> '.$permissionImg.'<br/>';
		echo ''.$text.'<br/>';
		echo '<img src="/img/comment.png" alt="'.$__lng['sherhler'].'" style="vertical-align:middle;"/> '.$count_comms.' <img src="/img/like.png" alt="'.$__lng['beyen'].'" style="vertical-align:middle;"/> '.$share_likes.' <a href="share/view.php?id='.$shareId.'">'.$__lng['etrafli'].'</a><br/>';
		echo '</div>';
	}
	echo '<br/><a href="share/index.php?uid='.$_uid.'">'.$__lng['butun paylasdiqlari'].'</a><br/>';
}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
echo '</div>';
include 'inc/footer.php';
?>
