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

$checkAuth = checkAuth('`id`, `nickname`, `post_run`, `msg_count_day`, `coins`,`mysmile`,`room_refresh`,`user_status`');
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
$user_status = $userrow["user_status"];

if($_rid == 5 && $point < 100){
	echo 'VIP otağına daxil ola bilməyiniz üçün minimum 100 balınız olmalıdır.<br/>';
	echo 'Otağa daxil olduqda bal çıxılımır.<br/><br/>';
	echo '<a href="/buy.php">+ Bal almaq</a>';
	echo '</div>';
	include '../inc/footer.php';
	exit;
}

if($_rid == 9 && $point < 500){
	echo 'Super VIP otağına daxil ola bilməyiniz üçün minimum 500 balınız olmalıdır.<br/>';
	echo 'Otağa daxil olduqda bal çıxılımır.<br/><br/>';
	echo '<a href="/buy.php">+ Bal almaq</a>';
	echo '</div>';
	include '../inc/footer.php';
	exit;
}

if($roomType == 1){
	updateOnline();
}
else{
	$subsQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room_subs` WHERE `rid` = '".$_rid."' AND `uid` = '".$id."' AND `status` = 1");
	if(mysql_num_rows($subsQuery) > 0) $roomAccess = 1; else $roomAccess = 0;
	if($roomView==0) $roomAccess =1;

	if($roomUid != $id && (($roomView == 1 && $roomAccess==0) || (isset($_POST['message']) && $roomAccess==0))) echo ''; else updateOnline();
}



$countRoomOnline = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `place` = '".$_rid."' AND `last_activity` > '".(time()-600)."'"), 0);

mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$countRoomOnline."', `refresh` = '".time()."' WHERE `id` = '".$_rid."' LIMIT 1");

echo '<p align="center"><b>'.$roomName.'</b> otağı <img src="/img/online.png" alt="on" /><a href="subs.php?rid='.$_rid.'">'.$countRoomOnline.'</a><br/>';

$where_user = "(`to` = 0 OR `to` = '".$id."' OR `uid` = '".$id."')" ;
$gizli_message = false;
$gizli_umumi_txt = 'Ümumi / <a href="msgs.php?rid='.$_rid.'&amp;gizli=1">Gizli</a>';
if(isset($_GET["gizli"]) and intval($_GET["gizli"])==1){
	$gizli_message = true;
	$where_user = "(`to` = '".$id."' OR (`uid` = '".$id."' and `to`>0))" ; 
	$gizli_umumi_txt = '<a href="msgs.php?rid='.$_rid.'">Ümumi</a> / Gizli';
}

echo 'Yazılar: '.$gizli_umumi_txt.'<br/>';

if($roomType==0){
	echo $__lng['otagin admini'].': <a href="../profile.php?uid='.$roomUid.'">'.$roomAdmin.'</a><br/>';

if($roomUid == $id){
	$countNewSubs = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `room_subs` WHERE `rid` = '".$_rid."' AND `status` = '0'"), 0);
	if($countNewSubs > 0) echo '<a href="manage.php?mod=subs&amp;status=0"><b>'.$countNewSubs.'</b></a> '.$__lng['tesdiq gozleyen var'].'.<br/>';
}
$room_delete_status = false;
if($roomUid != $id && (($roomView == 1 && $roomAccess==0) || (isset($_POST['message']) && $roomAccess==0)) && $user_status!=10){
	echo '<br/>'.$__lng['otaga uzv olmalisiniz pulsuzdur'].'<br/><br/>';
	echo '<a href="request.php?rid='.$_rid.'">'.$__lng['otaga uzv sorgusu gonder'].'</a><br/><br/>';
	echo $__lng['admin tesdiqledikden sonra'].'<br/><br/>';
	echo '</div></p>';
	include '../inc/footer.php';
	exit;
}
}else{
	if($user_status>0) 	{
		$room_delete_status = true; 
		$_del = intval($_GET['del']);
		$_commentid = intval($_GET['commentid']);

		if($_del == 1){
			echo '<div class="notif" align="center">';
			echo $__lng['silmeye eminsiniz'].'<br/>';
			echo '<form name="form" method="post" action="../team-panel.php?mod=room" style="text-align:center;">';
			echo '<input type="hidden" name="room_id" value="'.$_rid.'">';
			echo '<input type="submit" name="submit" value="Otaqdakı mesajları sil" style="text-align:center">';
			echo '</form>';
			echo '</div>';
		}
	}
}
echo '</p>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) echo '<a href="../messages.php?mod=unread"><img src="/img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni mesajin var'].'<br/><br/>';

echo '<form action="msgs.php?rid='.$_rid.'" method="post" name="room-form">';
echo '<table style="max-width: 400px;"><tr>';
echo '<td style="width: 1%"><a href="/smiles.php"><img src="/img/smiles/1f603.png" alt="Smile" /></a></td>';
echo '<td style="width: 87%; text-align: left"><input type="text" style="width: 98%" name="message" /></td>';
echo '<td style="width: 1%; text-align: left"><input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /></td>';
echo '<td style="width: 1%; text-align: left"><a class="button" href="msgs.php?rid='.$_rid.'&amp;gizli='.intval($_GET["gizli"]).'">'.$__lng['yenile'].'</a></td>';
echo '</tr></table>';
echo '<input type="hidden" name="action" value="send" />';
echo "</form><br/>";
// <a class="button" href="'.$gizli_message_url.'">'.$gizli_message_button.'</a>
$_message = trim(mysql_escape_string($_POST['message']));
$_to = intval($_POST['to']);
$_type = intval($_POST['type']);

$error = false;

if(strlen($_message) > 0 && !empty($_message) && $_message != ''){
	if($_to > 0){
		$toQuery = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = ".$_to.";");
		$toLogin = mysql_result($toQuery, 0);
	}
	if(!empty($toLogin)) $_message = $toLogin.', '.$_message;
	if($_type > 0 && $_to > 0) $ins_to = ", `to` = '".$_to."'";
	
			
	$checkDupQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room_msgs` WHERE `message` = '".$_message."' AND `time` > '".(time()-10)."' AND `rid` = '".$_rid."' LIMIT 1");
	if(mysql_num_rows($checkDupQuery)>0){
		echo '<span style="color: red">- '.$__lng['tekrar mesaj sehvi'].'</span><br/><br/>';
	}
	else{
		if($mysmile==1 and strpos($_message,".my.")!==false){
			$smile = mysql_fetch_assoc(mysql_query("SELECT `smile` FROM `aloaz_db`.`smiles` WHERE `user_id`='".$id."' ORDER BY `id` DESC LIMIT 1"));
			if($smile){
				$_message = str_replace(".my.",$smile["smile"],$_message);
			}	
		}
		//Check limit and BAN
		if($post_day > 200){
			$checkLimitQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room_msgs` WHERE `uid` = '".$id."' AND `time` > '".(time()-60)."'");
			if(mysql_num_rows($checkLimitQuery)>18){
				//ban code
				$block_time = 10800;
				$block_reason = 3;
				$block_description = 'Spam etdiyiniz üçün avtomatik olaraq ban olunursunuz.';
				mysql_query("INSERT INTO `aloaz_db`.`blocks` SET `from_id` = '1', `user_id` = '".$id."', `time`='".$block_time."',`blocked_time` = '".time()."',`reason`='".$block_reason."',`description`='".$block_description."';");
				mysql_query("UPDATE `aloaz_db`.`user` SET `block_time`='".$block_time."',`block_begin_time`=0 WHERE id='".$id."' LIMIT 1");
				$error = true;
			}
		}
		
		if(preg_match_all("/[.]st[0-9]{1,3}[.]/i", $_message, $preg_stcodes)){
			
			if(strlen($_message)<1){
				echo "Boş olmaz".'<br/><br/>';
				$error = true;
			}
			$cnt_stcodes = count($preg_stcodes[0]);
			if($cnt_stcodes > 3){
				echo $__lng['mesajda cox stiker limiti'].'<br/><br/>';
				$error = true;
			}
			elseif($point < $cnt_stcodes){
				echo $__lng['stiker kodu askarlandi'].'<br/>';
				echo $__lng['ballariniz'].': <b>'.$point.'</b> <a href="pointserv.php">+ '.$__lng['bal almaq'].'</a><br/><br/>';
				$error = true;
			}
			else{
				mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins` - ".$cnt_stcodes." WHERE `id` = '".$id."' LIMIT 1;");
				if(mysql_affected_rows() > 0){
					//$sticker_msg = 'Söhbet otağında '.$cnt_stcodes.' stiker gönderdiyinize göre hesabınızdan '.$cnt_stcodes.' bal çıxıldı. Teşekkür edirik!';
					//mysql_query("INSERT INTO `chat_messages` SET `from` = '1', `to` = '".$id."', `from_nick` = 'AloChat', `message` = '".$sticker_msg."', `time` = '".time()."'");
				}
			}
		}
		if($post_day > 2000){
			echo $__lng['gunluk mesaj limitini kecmisiniz'].'<br/><br/>';
			$error = true;
		}
		if(!$error){
						
			mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = '".$login."', `message` = '".$_message."', `uid` = '".$id."', `rid` = '".$_rid."', `time` = '".time()."' ".$ins_to."");
			if(mysql_affected_rows() > 0 && $post_run == 0){
 				mysql_query("UPDATE `aloaz_db`.`user` SET `msg_count` = `msg_count` + 1, `msg_count_day` = `msg_count_day` + 1 WHERE `id` = '".$id."' LIMIT 1");
			}
		}
	}
}
$block_query = mysql_query("SELECT `block_to` FROM `aloaz_db`.`user_block` WHERE `block_from`='".$id."'");
if(mysql_num_rows($block_query)>0){
	$blocked_users = [];
 	while($block_row = mysql_fetch_assoc($block_query)){
		$blocked_users[] =  $block_row["block_to"];
	} 
	$block_users = implode(",",$blocked_users);
	$block_users = "(".$block_users.")";
	$not_user_where = 'AND `uid` NOT IN '.$block_users;
}


$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`room_msgs` WHERE `rid` = '".$_rid."' AND $where_user $not_user_where");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);


 
foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'" alt="'.$key.'" />';
}
foreach ($stickersArray as $key => $value) {
	$stickersArray[$key] = '<img src="/img/stickers/'.$value.'.gif" alt="'.$key.'" />';
}



$query = mysql_query("SELECT * FROM `aloaz_db`.`room_msgs` WHERE `rid` = '".$_rid."' AND $where_user $not_user_where ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	if($gizli_message==true){
		echo 'Gizli mesaj yoxdur';
	}else{
		echo $__lng['msj yoxdur ilk sen yaz'].'<br/><br/>';
	}
	
}
else{
	while($row = mysql_fetch_array($query)){
		$msg_id = $row['id'];
		$msg_login = $row['login'];
		$msg_message = $row['message'];
		$msg_uid = $row['uid'];
		$msg_to = $row['to'];
		$msg_time = $row['time'];
		
		$msg_message = htmlspecialchars($msg_message);
		$msg_message = stripslashes($msg_message);	
		
		$msg_message = str_replace(array_keys($smilesArray), array_values($smilesArray), $msg_message);
		$msg_message = str_replace(array_keys($stickersArray), array_values($stickersArray), $msg_message);
		
		$rnickname = mysql_result(mysql_query("SELECT `rnickname` FROM `aloaz_db`.`user` WHERE `id` = '".$msg_uid."';"), 0);
		if($rnickname != ""){
			$msg_login = '<img src="../rn/tmp/'.$rnickname.'" style="vertical-align:middle" alt="'.$msg_login.'"/>';
		}
		
		if($msg_uid==1){
			$msg_message = '<b>'.$msg_message.'</b>';
		}
		
		if(date('d-m-Y', $msg_time) == date('d-m-Y')) $msg_date = ''.date('H:i', $msg_time); else $msg_date = date('d-m-Y H:i', $msg_time);
		if($msg_to != 0) $g = '<b>[G!]</b>'; else $g = '';
		echo '<div style="margin-bottom:8px">'.$g.' <a href="../profile.php?uid='.$msg_uid.'&amp;rid='.$_rid.'">'.$msg_login.'</a>: '.$msg_message.'<br/><span style="font-size: x-small;">'.$msg_date.'</span></div>';
	}
 
	if($room_delete_status){	

		
		if($del!=1){
			echo '<a href="msgs.php?rid='.$_rid.'&del=1">Mesajlari sil</a>';
		} 
	
		
	}
	
	echo '<br/><div class="pageNav">';
	if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

	if($page > 1) echo '<a href ="?rid='.$_rid.'&amp;page='.($page-1).'">&lt;</a> ';

	$interval = 5;
	$max = ceil($all_rows/$show_limit);
	if($page > $interval) echo " <a href=\"?rid=".$_rid."&amp;page=1\">1</a> ... ";

	for($i=1; $i<=$max; $i++){
		if($page <= $interval && $i <=$interval){
			if($i != $page){
				echo " <a href=\"?rid=".$_rid."&amp;page=".$i."\">".$i."</a> ";
			}
			else{
				echo ' <span>'.$i.'</span> ';
			}
		}
		else{
			if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
				if($i != $page){
					echo " <a href=\"?rid=".$_rid."&amp;page=".$i."\">".$i."</a> ";
				}
				else{
					echo ' <span>'.$i.'</span> ';
				}
			}
			
		}
	}
	if($page <= $max - 5) echo '... ';

	if($max > $interval){
		if($max != $page){
			echo " <a href=\"?rid=".$_rid."&amp;page=".$max."\">".$max."</a> ";
		}
		else{
			echo " <span>".$max."</span> ";
		}
	}

	if($page < $max) echo '<a id="pageButon" href ="?rid='.$_rid.'&amp;page='.($page+1).'">&gt;</a> ';

	echo '</div><br/>';
	
	
}

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
