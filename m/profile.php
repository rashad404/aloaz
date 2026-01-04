<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/smiles.php';
include 'inc/lang/pack.php';


$title = 'AloChat';
include 'inc/header.php';
foreach ($smilesArray as $key => $value) {
    $smilesArray[$key] = '<img src="/img/smiles/'.$value.'" alt="'.$key.'" />';
}
echo '<div class="mnav"><a href="main.php">'.$title.'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `unseen`, `nickname`,`user_status`, `coins`');
if($checkAuth == 'error'){
    displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
        '<a href="index.php?loc=profile">'.$__lng['giris'].'</a> | <a href="reg.php?loc=profile">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$profile_unseen = $userrow['unseen'];
$user_status = $userrow['user_status'];
$coins = $userrow['coins'];



$_uid = intval($_GET['uid']);
$query = mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `id` = '".$_uid."';");

if(mysql_num_rows($query) == 0){
	echo $__lng['istifadeci tapilmadi'].'<br/>';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}

$row = mysql_fetch_array($query);
$uid = $row['id'];
$u_login = $row['nickname'];
$u_name = $row['full_name'];
$u_sex = $row['sex'];
$u_birthday = $row['birthday'];
$u_photo = $row['profile_photo'];
$u_photo_id = $row['profile_photo_id'];
$u_status = stripslashes($row['last_post']);
$u_weight = $row['weight'];
$u_height = $row['height'];
$u_about = stripslashes($row['about']);
$u_time = $row['last_activity'];
$u_created_at = $row['created_at'];
$u_post = $row['msg_count'];
$u_post_run = $row['post_run'];
$u_post_day = $row['msg_count_day'];
$u_friends = $row['only_friend'];
$u_user_status = $row['user_status'];
$u_ua = $row['ua'];
$u_ip = $row['ip'];
$u_rating = $row['rating'];
$u_question_rating = $row['question_rating'];
$u_invisible = $row['invisible'];
$u_block_time = $row["block_time"];
$u_block_begin_time = $row["block_begin_time"];
if($uid == 1){
	echo 'Bu sistem istifadəçisidir. Avtomatik şəkildə sistem mesajlarını yazır.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}

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

$u_about = str_replace(array_keys($smilesArray), array_values($smilesArray), $u_about);
 
if(empty($u_photo)) $img_file = '<img src="img/'.$u_sex_img.'.gif" alt="man" style="border: 1px solid #d7d7d7" />';
else {
$resized_photo = str_replace('/thumbs/','/',$u_photo);
$img_file = '<a href="photos/view.php?photo_id='.$u_photo_id.'"><img src="udata'.$u_photo.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a>';
}

$_rid = intval($_GET['rid']);

 $checkFriendQuery = mysql_query("SELECT `user_2` FROM `aloaz_db`.`user_friend` WHERE (`user_1` = '".$id."' AND `user_2` = '".$_uid."') OR (`user_1` = '".$_uid."' AND `user_2` = '".$id."');");
if(mysql_num_rows($checkFriendQuery) == 0) $checkFriend = false; else $checkFriend = true;

if($_rid > 0){
	echo '<br/>'.$u_login.' '.$__lng['otaga mesaj'].':<br/>';
	if($_rid==10) $action_room = 'room/viktorina.php';
	else $action_room = "room/msgs.php?rid=".$_rid;
	echo '<form action='.$action_room.' method="post">';
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
		if($u_friends==1 && !$checkFriend){
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
// if($id == 1129446 or $id==1129447){	
	
	// if(isset($_POST['rating_submit'])){
		// $rating_post = intval($_POST["rating"]);
		// $from_id = $id;
		// $to_id = $uid;

		// if($rating_post<1){
			// $error = $__lng['minium rating vermek'].'.<br/>';
		// }
		// if($rating_post>1000){
			// $error = $__lng['maksimum rating vermek'].'.<br/>';
		// }
		// if($rating_post>$coins){
			// $error = $__lng['hesabda bal yoxdur'].'.<br/>';
		// }	
 
		// if(!empty($error)){
			// echo '<span style="color:red;">'.$__lng['sehv'].':</span> '.$error;
			// echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
			// break;
		// }		
		
		// $update1 = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`=`coins`-".$rating_post." WHERE `id`='".$from_id."'");
		// $update2 = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`=`coins`+".$rating_post.",`rating`=`rating`+".$rating_post." WHERE `id`='".$to_id."'");
	 
		// $insert = mysql_query("INSERT INTO `aloaz_db`.`rating_logs` SET `user_id`='".$from_id."',`user_id2`='".$to_id."',`coins`='".$rating_post."',`rating`='".$rating_post."',`date`='".date("Y-m-d H:i:s")."'");
		// echo 'Hesabınızdan '.$rating_post.' bal çıxıldı ve '.$send_login.' loginine '.$rating_post.' ses verildi.<br/>';
	// }else{	
	
		// echo ' <form action="" method="post">
			// <select name="rating">
				// <option value="1">1</option>
				// <option value="5">5</option>
				// <option value="10">10</option>
				// <option value="30">30</option>
				// <option value="50">50</option>
				// <option value="100">100</option>
				// <option value="200">200</option>
				// <option value="500">500</option>
				// <option value="1000">1000</option>
			// </select>
			// <input type="submit" name="rating_submit" value="Ses ver">
		// </form>';
	// }
// }

$photoCount = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user_image` WHERE `user_id`='".$uid."'");
$photoCount = mysql_result($photoCount, 0);

echo '<table width="100%" cellpadding="2">';
echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td>'.$img_file.'</td>
<td width="100%">'.$u_login.'<br/><a href="/photos?uid='.$uid.'">'.$photoCount.' Şekil</a>'; 
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
		<td class="info_value">'.htmlspecialchars($u_status).'</td>
	</tr>
	<tr>
		<td class="info_params">Saytdakı vəzifəsi</td>';
	if($u_user_status == 1){
		echo '<td class="info_value"><img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" alt="." /> '.$__lng['user_status_1'].'</td>';
	}
	else if($u_user_status == 2){
		echo '<td class="info_value"><img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" alt="." /> '.$__lng['user_status_2'].'</td>';
	}
	else if($u_user_status == 3){
		echo '<td class="info_value"><img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> '.$__lng['user_status_3'].'</td>';
	}
	else if($u_user_status == 10){
		echo '<td class="info_value"><b>Admin</b></td>';
	}
	else{
		echo '<td class="info_value">Yoxdur</td>';
	}
echo '	
	<tr>
		<td class="info_params">'.$__lng['haqqinda'].'</td>
		<td class="info_value">'.$u_about.'</td>
	</tr>';
	$roomQuery = mysql_query("SELECT `id`, `name` FROM `aloaz_db`.`room` WHERE `uid` = '".$_uid."'");
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
	if($u_question_rating > 0){
	echo '
	<tr>
		<td class="info_params">'.$__lng['viktorina cavab sayi'].'</td>
		<td class="info_value">'.$u_question_rating.' cavab</td>
	</tr>';
	}	
	echo '<tr>
		<td class="info_params">İstifadəçi reytinqi</td>
		<td class="info_value"><a href="user-rating.php?mod=voters&amp;id='.$uid.'">'.$u_rating.' səs</a></td>
		</tr>';
	
	echo '<tr>
		<td class="info_params">'.$__lng['qeyd oldugu tarix'].'</td>
		<td class="info_value">'.$u_regdate.' ('.round((time()-$u_created_at)/86400).' gün)</td>
	</tr> 
	<tr>
		<td class="info_params">'.$__lng['sonuncu defe daxil olub'].'</td>
		<td class="info_value">';
		if($uid==1111) echo "Admin";
		elseif($u_invisible == 1) echo "Gizli";
		else{
			echo ($u_time>time()-600 ? '<span style="color:green;">'.$__lng['onlayn'].'</span>' : date('Y-m-d H:i', $u_time));
		}
		if($u_block_time>0){
			$block_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`blocks` WHERE user_id='".$uid."' order by id desc limit 1"));
			$from_user_nickname = 'Alochat';
				if($block_row["from_id"] != 0){
					$from_user = mysql_fetch_assoc(mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` where id='".$block_row["from_id"]."' limit 1"));
					
					if($from_user["nickname"]!=""){
						$from_user_nickname = $from_user["nickname"];
					}
				}
				
				$ban_minutes = $block_row['time']/60;
			echo '<tr>
				<td class="info_params">Ban</td>
				<td class="info_value">Bu istifadeçi '.$from_user_nickname.' terefinden '.$ban_minutes.' deqiqelik ban olunub</td>
			</tr>'; 
		}
	echo '</td>
	</tr>
	';
	if($user_status>0){
		echo '
			<tr>
				<td class="info_params">IP</td>
				<td class="info_value">'.$u_ip.'</td>
			</tr>
			<tr>
				<td class="info_params">User Agent</td>
				<td class="info_value">'.$u_ua.'</td>
			</tr>';
	}
	echo '
</table>
';

if($uid != $id){
	echo '<br/><a class="button" href="messages.php?mod=messaging&amp;uid='.$uid.'">'.$__lng['mesaj yaz'].'</a><br /><br />';

	if(!$checkFriend) echo '<a class="button" href="frequest.php?mod=send&amp;uid='.$uid.'">'.$__lng['dostluq teklif et'].'</a><br/><br/>'; else echo '<a class="button" href="frequest.php?mod=del&amp;uid='.$uid.'">'.$__lng['dostluq legvi'].'</a><br /><br />';

	if(!$checkFriend){
		$checkBlockQuery = mysql_query("SELECT `block_to` FROM `aloaz_db`.`user_block` WHERE `block_from` = '".$id."' AND `block_to` = '".$_uid."';");
		if(mysql_num_rows($checkBlockQuery) == 0) echo '<a class="button" href="block.php?mod=request&amp;uid='.$uid.'">'.$__lng['qadaga qoy'].'</a><br/><br/>'; else echo '<a class="button" href="block.php?mod=del&amp;uid='.$uid.'">'.$__lng['qadaga legvi'].'</a><br /> <br />';
	}

    if($user_status>$u_user_status){
        echo '<a class="button" style="background-color:red" href="ban.php?mod=request&amp;uid='.$uid.'">'.$__lng['ban et'].'</a><br /><br />';
    }

	if($profile_unseen==0){
		$checkVisitorsQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`user_visit` WHERE `visit_from` = '".$id."' AND `visit_to` = '".$_uid."'");
		if(mysql_num_rows($checkVisitorsQuery) > 0){
			mysql_query("UPDATE `aloaz_db`.`user_visit` SET `time` = '".time()."', `count` = `count` + 1 WHERE `visit_from` = '".$id."' AND `visit_to` = '".$_uid."'");
		}
		else{
			mysql_query("INSERT INTO `aloaz_db`.`user_visit` SET  `visit_from` = '".$id."', `visit_to` = '".$_uid."', `time` = '".time()."'");
			//setNotification($_uid,$paramsArray["NOT_USER_VISIT"],time(),$id,$login,0,0);

		}
	}
	
	
	echo '<br/><a href="report.php?uid='.$uid.'"><img src="img/abuse.png" alt="." /> '.$__lng['shikayet et'].'</a><br/>';
}
else{
	echo '<br/><a class="button" href="profile_edit.php">'.$__lng['profilini deyis'].'</a><br/>';
}

//SHARES

if(!$checkFriend && $uid != $id) $ins_permission = " AND `permission` != '1'";
$shareQuery = mysql_query("SELECT `id`, `user_id` as uid, `text`,`video_frame`, `attach`, `permission`,`comment_count`,`like_count` as `likes`,`time` as `date` FROM aloaz_db.`share` WHERE `user_id` = '".$_uid."' ".$ins_permission." ORDER BY `date` DESC LIMIT 3;");

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
		$count_comms = $row['comment_count'];
		$video_frame = $row['video_frame']; 
		$count_comms = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`share_comment` WHERE `sid` = '".$shareId."';"), 0);

		
		if($permission == 1) $permissionImg = '<img src="/img/share_friends_gray.gif" alt="'.$__lng['yalniz dostlarla paylasib'].'" />'; else $permissionImg = '<img src="/img/share_public_gray.gif" alt="'.$__lng['hamiyla paylasib'].'" />';
		
		if($video_frame!="") $videoImg = ' <img src="/img/play-icon.png" alt="Video" />'; 
		else $videoImg = '';
		
		if(date('d-m-Y', $date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $date);
		else if(date('d-m-Y', $date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $date);
		else $date_str = date('d-m-Y H:i', $date);
		
		if(empty($attach)) $strLentValue = 200; else $strLentValue = 80;
		if(strlen($text) > $strLentValue){
			$s = substr($text, 0, $strLentValue);
			$text = replaceLatin_E(substr($s, 0, strrpos($s, ' '))).' ...';
		}
		if(empty($attach))$text = str_replace("\n", '<br/>', $text);
		
			
		$text = str_replace(array_keys($smilesArray), array_values($smilesArray), $text);

		echo '<div class="content">';
		if($video_frame!=""){
			if(!empty($attach))
				echo '<a class="thickbox play-button-link" href="share/view.php?id='.$shareId.'" rel="flowplayer" target="" style="z-index: 1; position: relative;float:left"> 
					<img border="0" alt="" src="udata/images/share/resized/'.date('Ym',$date).'/'.$attach.'"  style="float:left; padding-right:5px; max-height: 86px" width="90"/>
					<img class="play-button2" alt="" src="/img/play.png" />
				</a>';
		}else{
			if(!empty($attach)) echo '<a href="share/view.php?id='.$shareId.'"><img src="udata/images/share/resized/'.date('Ym',$date).'/'.$attach.'" alt="." style="float:left; padding-right:5px; max-height: 86px" width="90" /></a>';
		}		
		
		$u_query = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = '".$uid."';");
		$u_login = mysql_result($u_query, 0);
		echo '<a href="/profile.php?uid='.$uid.'">'.$u_login.'</a> '.$__lng['paylasdi'].'<br/>';
		echo '<small>'.$date_str.'</small> '.$permissionImg.$videoImg.'<br/>';
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
