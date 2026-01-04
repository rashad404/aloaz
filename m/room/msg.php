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

$roomQuery = mysql_query("SELECT `name`, `view`, `uid`, `login`,`type` FROM `aloaz_db`.`room` WHERE `id` = '".$_rid."' and `id`!=10");
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

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`, `post_run`, `msg_count_day`, `coins`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$post_run = $userrow['post_run'];
$post_day = $userrow['msg_count_day'];
$point = $userrow['coins'];


if($_rid == 5 && $point < 100){
	echo 'VIP otağına daxil ola bilməyiniz üçün minimum 100 balınız olmalıdır.<br/>';
	echo 'Otağa daxil olduqda bal çıxılımır.<br/><br/>';
	echo '<a href="/buy.php">+ Bal almaq</a>';
	echo '</div>';
	include '../inc/footer.php';
	exit;
}

$subsQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room_subs` WHERE `rid` = '".$_rid."' AND `uid` = '".$id."' AND `status` = 1");
if(mysql_num_rows($subsQuery) > 0) $roomAccess = 1; else $roomAccess = 0;
if($roomView==0) $roomAccess =1;

if($roomUid != $id && (($roomView == 1 && $roomAccess==0) || (isset($_POST['message']) && $roomAccess==0))) echo ''; else updateOnline();

$countRoomOnline = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `place` = '".$_rid."' AND `last_activity` > '".(time()-600)."'"), 0);

mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$countRoomOnline."', `refresh` = '".time()."' WHERE `id` = '".$_rid."' LIMIT 1");

echo '<p align="center"><b>'.$roomName.'</b> otağı <img src="/img/online.png" alt="on" />'.$countRoomOnline.'<br/>';

echo '</p>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) echo '<a href="../messages.php?mod=unread"><img src="/img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni mesajin var'].'<br/><br/>';

echo '<form action="msg.php?rid='.$_rid.'" method="post">';
echo '<a href="/smiles.php"><img src="/img/smiles/1f603.png" alt="Smile" /></a> <input type="text" name="message" /> ';
echo '<input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /> <a class="button" href="msg.php?rid='.$_rid.'">'.$__lng['yenile'].'</a><br/>';
echo '<input type="hidden" name="action" value="send" />';
echo "</form><br/>";

$_message = trim(mysql_escape_string($_POST['message']));
$_to = intval($_POST['to']);
$_type = intval($_POST['type']);

$error = false;

if(strlen($_message) > 0){
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
		if(preg_match_all("/[.]st[0-9]{1,3}[.]/i", $_message, $preg_stcodes)){
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
		if($post_day > 3000){
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

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`room_msgs` WHERE `rid` = '".$_rid."' AND (`to` = 0 OR `to` = '".$id."' OR `uid` = '".$id."') $not_user_where");
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



$query = mysql_query("SELECT * FROM `aloaz_db`.`room_msgs` WHERE `rid` = '".$_rid."' AND (`to` = 0 OR `to` = '".$id."' OR `uid` = '".$id."') $not_user_where ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo $__lng['msj yoxdur ilk sen yaz'].'<br/><br/>';
}
else{
	while($row = mysql_fetch_array($query)){
		$msg_id = $row['id'];
		$msg_login = $row['login'];
		$msg_message = $row['message'];
		$msg_uid = $row['uid'];
		$msg_to = $row['to'];
		$msg_time = $row['time'];
		
		$msg_message = stripslashes($msg_message);
		$msg_message = str_replace(array_keys($smilesArray), array_values($smilesArray), $msg_message);
		$msg_message = str_replace(array_keys($stickersArray), array_values($stickersArray), $msg_message);
		
		if(date('d-m-Y', $msg_time) == date('d-m-Y')) $msg_date = ''.date('H:i', $msg_time); else $msg_date = date('d-m-Y H:i', $msg_time);
		
		if($msg_to != 0) $g = '<b>[G!]</b>'; else $g = '';
		echo '<div style="margin-bottom:8px">'.$g.' <a href="../profile.php?uid='.$msg_uid.'&amp;rid='.$_rid.'">'.$msg_login.'</a>: '.$msg_message.'<br/><span style="font-size: x-small;">'.$msg_date.'</span></div>';
	}

	echo '<br/>';
	if($page > 1) echo '<a id="pageButon" href ="?rid='.$_rid.'&amp;page='.($page-1).'">&lt;</a> ';
	if($page < $max) echo '<a id="pageButon" href ="?rid='.$_rid.'&amp;page='.($page+1).'">&gt;</a>';
	if($page > 1 || $page < $max) echo '<br/><br/>';
}

echo '</div>';
include '../inc/footer.php';
?>
