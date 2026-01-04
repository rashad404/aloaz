<?
error_reporting(0);
session_start();


include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/csrf_func.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/stickers.php';
include 'inc/lang/pack.php';


$title = 'AloChat';
include 'inc/header.php';

$_back = checkData($_GET['back']);

if($_back == 'friends') $friendsNav = ' - <a href="friends.php">'.$__lng['dostlar'].'</a>';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> '.$friendsNav.'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`, `phone`, `coins`, `only_friend`, `post_run`, `msg_count_day`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=messages">'.$__lng['giris'].'</a> | <a href="reg.php?loc=messages">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$phone = $userrow['phone'];
$point = $userrow['coins'];
$friend_sett = $userrow['only_friend'];
$post_run = $userrow['post_run'];
$post_day = $userrow['msg_count_day'];

checkPhoneBan($phone);
deleteOldMessages();
updateOnline();

if(isset($_GET['mod'])) $mod = $_GET['mod']; else $mod = "";

switch($mod){




case 'messaging':

$toid = intval($_GET['uid']);
$message = htmlspecialchars(mysql_escape_string(trim($_POST['message'])));

$_del = intval($_GET['del']);
$_mid = intval($_GET['mid']);

$to_sql = mysql_query("SELECT `nickname`, `only_friend`, `no_dating` FROM `aloaz_db`.`user` WHERE `id` = '".$toid."';");

if(mysql_affected_rows() == 0){
	echo $__lng['istifadeci tapilmadi'].'<br/>';
	break;
}
else{
	mysql_query("UPDATE aloaz_db.`conversation_reply` SET `read` = 1, `read_time` = '".time()."' WHERE `user_id_to` = '".$id."' AND `user_id` = '".$toid."';");
	
	$user_data = mysql_fetch_array($to_sql);
	$uid_friends = $user_data['only_friend'];
	$uid_dating = $user_data['no_dating'];
	$to_nick = $user_data['nickname'];
	$to_love = $user_data['love'];
}

if($_del > 0 && $_mid > 0){
	$query = mysql_query("SELECT `user_id`, `user_id_to`,`deleted_by` FROM aloaz_db.`conversation_reply` WHERE `id` = '".$_mid."';");
	if(mysql_num_rows($query) == 0){
		echo $__lng['mesaj movcud deyil'].'<br/>';
		break;
	}

	$msg_row = mysql_fetch_array($query);
	$from = $msg_row['user_id'];
	$to = $msg_row['user_id_to'];
	$deleted_by = $msg_row['deleted_by'];

	if($to != $id && $from != $id){
		echo "<br/>";
		break;
	}
	
	if($deleted_by>0 && $deleted_by!=$id){
		mysql_query("DELETE FROM aloaz_db.`conversation_reply` WHERE `id` = '".$_mid."';");
	}else{
		$del_update = mysql_query("UPDATE aloaz_db.`conversation_reply` SET `deleted_by` = '".$id."' WHERE `id` = '".$_mid."';");
	}
}

if(isset($_POST['action']) && mysql_num_rows($to_sql) > 0 && !empty($message) && isTokenValid()){
if($post_day > 3000){
	echo "<b>".$__lng['gunluk mesaj limitini kecmisiniz'].".</b><br/>\n";
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}
if(strlen($message)>2000){
	echo $__lng['mesaj uzun yazilib'].'.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}
if(empty($message)){
	echo $__lng['mesaj yazilmayib'].'.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}

if($_POST['action'] != 'sendattach'){
	$q = mysql_query("SELECT `id` FROM aloaz_db.`conversation_reply` WHERE `reply` = '".$message."' AND `user_id_to` = '".$toid."' AND `user_id` = '".$id."' AND `time` > '".(time()-300)."';");
	if(mysql_num_rows($q) > 0){
	echo $__lng['tekrar mesaj olmaz'].'.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
	}
}

$checkFriendQuery = mysql_query("SELECT `ok` FROM `aloaz_db`.`user_friend` WHERE (`user_1` = '".$toid."' AND `user_2` = '".$id."') OR (`user_2` = '".$toid."' AND `user_1` = '".$id."');");
$checkFriendRow = mysql_fetch_array($checkFriendQuery);
$ok1 = $checkFriendRow['ok'];
$ok2 = $checkFriendRow['ok'];

if($uid_friends == 1 && mysql_num_rows($checkFriendQuery) == 0){
	echo "<b>".$__lng['dostlardan mesaj qebul edir']."</b><br/>"; 
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}

if($uid_friends == 1 && ($ok1 != 1 || $ok2 != 1)){
	echo "<b>".$__lng['dostluq sorgusu gonderilib']."</b><br/>".$__lng['dostluq qebulundan sonra']."<br/>";
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}

if($friend_sett == 1){
	if(mysql_num_rows($checkFriendQuery) == 0){
		$friendReplyWarn = $__lng['diqqet'].'! <a href="settings.php">'.$__lng['aletler'].'</a> - '.$__lng['dostluqda yoxdu yaza bilmez'].' <a href="frequest.php?mod=send&amp;uid='.$toid.'">'.$__lng['dost sorgusu gonder'].'</a>.';
	}
	else{
		if($ok1 != 1 || $ok2 != 1){
			$friendReplyWarn = $__lng['dost tesdiqlenmese cvb'];
		}
	}
}
 
$checkBlockQuery = mysql_query("SELECT `block_from` FROM `aloaz_db`.`user_block` WHERE `block_from` = '".$toid."' AND `block_to` = '".$id."';");
if(mysql_num_rows($checkBlockQuery) > 0){
	echo '<b>'.$__lng['qadagaya gore msj gonderilmedi'].'</b><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}

if(preg_match_all("/[.]st[0-9]{1,3}[.]/i", $message, $preg_stcodes)){
	$cnt_stcodes = count($preg_stcodes[0]);
	if($cnt_stcodes > 3){
		echo $__lng['mesajda cox stiker limiti'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	elseif($point < $cnt_stcodes){
		echo '<b>'.$__lng['stiker kodu askarlandi'].'</b><br/>';
		echo 'Ballarınız: <b>'.$point.'</b> <a href="pointserv.php">+ '.$__lng['bal almaq'].'</a><br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	else{
		mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins` - ".$cnt_stcodes." WHERE `id` = '".$id."' LIMIT 1;");
		if(mysql_affected_rows() > 0){
			//$sticker_msg = 'Mesajla '.$cnt_stcodes.' stiker gönderdiyinize göre hesabınızdan '.$cnt_stcodes.' bal çıxıldı. Teşekkür edirik!';
			//mysql_query("INSERT INTO `chat_messages` SET `user_id` = '1', `user_id_to` = '".$id."', `from_nick` = 'AloChat', `reply` = '".$sticker_msg."', `time` = '".time()."'");
		}
	}
}

if($_POST['action'] == 'sendattach'){
	$ext_array = array("jpg", "jpeg", "gif", "png", "3gp", "mp4", "avi", "mp3", "wav", "amr");

	$attach_fsize = filesize($_FILES["attach_file"]["tmp_name"]);
	$file_name = @$_FILES["attach_file"]["name"];
	$exts = split("[/\\.]", strtolower($file_name)); 
	$n = count($exts)-1; 
	$attach_ext = $exts[$n];
	
	$check_attach_limit = mysql_query("SELECT `id` FROM aloaz_db.`conversation_reply` WHERE `user_id` = '".$id."' AND `attach` != '' AND `time` > '".strtotime(date('Y-m-d 00:00:00'))."';");
	if($file_name == "") $error .= $__lng['fayl secilmeyib']."<br/>";
	if(!in_array(strtolower($attach_ext), $ext_array)) $error .= $__lng['icaze verilmeyen format'].'<br/>';
	if($attach_fsize > 1024*1024*10) $error .= $__lng['file size gore limit max'].'<br/>';
	if($attach_fsize < 1) $error .= $__lng['file size gore limit min'].'<br/>';
	if(mysql_num_rows($check_attach_limit) > 10 && $point < 1) $error = $__lng['fayl gondermek limiti bitib'].'<br/>'.$__lng['hesaba bal yuklemelisiniz'].'<br/>';
	
	if($error != ''){
		echo $__lng['fayl gondererken sehvler'].':<br/>';
		echo $error.'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		echo '</div>';
		include 'inc/footer.php';
		exit();
	}
	
	$store_name = "".substr(md5(time().$file_name.substr("abcdefghijklmnopqrstuvwxyz", 0, rand(1, 26))), 0, 16).".".$attach_ext;
	if(move_uploaded_file($_FILES[attach_file][tmp_name], 'attach/data/temp/'.$store_name.'')){
		$ins_attach = " , `attach` = '".$store_name."'";
		if(mysql_num_rows($check_attach_limit) > 10 && $point > 0) 
			mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins` - 1 WHERE `id` = '".$id."' LIMIT 1;"); // fee
		$message = $__lng['yukle'].':';
	}
}

$conversation_sql = mysql_query("SELECT id FROM aloaz_db.`conversation` WHERE (`user_one`='".$id."' and `user_two`='".$toid."') or (`user_one`='".$toid."' and `user_two`='".$id."')");
if(mysql_num_rows($conversation_sql) == 0){
	$insert_conversation = mysql_query("INSERT INTO aloaz_db.`conversation` SET `user_one`='".$id."',`user_two`='".$toid."',`last_time`='".time()."',`last_reply`='".$message."'");
	$conversation_id = mysql_insert_id();
}else {
	$conversation = mysql_fetch_assoc($conversation_sql);
	$conversation_id = $conversation["id"];
	mysql_query("UPDATE aloaz_db.`conversation` SET `last_time`='".time()."',`last_reply`='".$message."' WHERE `id`='".$conversation_id."'");
}
$insert = mysql_query("INSERT INTO aloaz_db.`conversation_reply` SET `conversation_id`='".$conversation_id."',`user_id` = '".$id."', `user_id_to` = '".$toid."', `from_nick` = '".$login."', `reply` = '".$message."', `time` = '".time()."' ".$ins_attach.";");

if(!$insert){
	echo $__lng['msg gonderile bilmedi'].'<br/>';
}
else{
	if($post_run == 0){
		mysql_query("UPDATE `aloaz_db`.`user` SET `msg_count` = `msg_count` + 1, `msg_count_day` = `msg_count_day` + 1 WHERE `id` = '".$id."' LIMIT 1");
	}
	if(mysql_num_rows($checkFriendQuery) > 0){
		mysql_query("UPDATE `aloaz_db`.`user_friend` SET `ok_time` = '".time()."' WHERE (`user_1` = '".$toid."' AND `user_2` = '".$id."') OR (`user_2` = '".$toid."' AND `user_1` = '".$id."') LIMIT 1;");
	}
}

}

echo '<p align="center">';
if($toid == 1) echo $__lng['alochat terefinden mesaj']; else  echo '<a href="profile.php?uid='.$toid.'">'.$to_nick.'</a><br/>';
echo '</p>';

echo $friendReplyWarn.'<br/>';

$new_messages = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$new_messages = mysql_result($new_messages, 0);

//if($new_messages > 0) echo '<a href="message.php?mod=unread&amp;id='.$id.'&amp;password='.$password.'">'.$new_messages.'</a> oxunmamış mesajınız var<br/>';

if($toid != 1){
	$checkFriendQuery = mysql_query("SELECT `user_1` FROM `aloaz_db`.`user_friend` WHERE (`user_1` = '".$toid."' AND `user_2` = '".$id."') OR (`user_2` = '".$toid."' AND `user_1` = '".$id."');");
	if($uid_friends == 1 && mysql_num_rows($checkFriendQuery) == 0){
		echo $__lng['yalniz dostlardan msj q e'].'.<br/><br/>';
	}
	else{
	echo '<form action="messages_n04.php?mod=messaging&amp;uid='.$toid.'&amp;back='.$_back.'" method="post">';
	echo '<a href="smiles.php"><img src="img/smiles/1f603.png" alt="Smile" /></a> <input type="text" name="message" /> ';
	echo '<input type="hidden" name="csrf_token" value="'.makeToken().'">';
	echo '<input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /> <a href="attach/sendmessage.php?uid='.$toid.'"><img src="img/camera.png" alt="attach" /></a><br/>';
	echo '<input type="hidden" name="action" value="send" />';
	echo "</form><br/>";
	}
}

echo '<a class="button" href="messages.php?mod=messaging&amp;uid='.$toid.'&amp;refresh='.rand(1111, 9999).'">'.$__lng['yenile'].'</a>  ';

if($_del != 0) echo $__lng['sil']; else echo '<a class="button" href="messages.php?mod=messaging&amp;del=1&amp;uid='.$toid.'&amp;refresh='.rand(1111, 9999).'&amp;back='.$_back.'">'.$__lng['sil'].'</a>';

echo '<br/><br/>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) echo '<a href="messages.php?mod=unread&amp;back='.$_back.'"><img src="img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni msj var'].'<br/><br/>';

$row_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE ((`user_id` = '".$id."' AND `user_id_to` = '".$toid."' AND `deleted_by` != '".$id."') OR (`user_id` = '".$toid."' AND `user_id_to` = '".$id."' AND `deleted_by` != '".$id."'));");
$all_rows = mysql_result($row_count, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `reply`, `time`, `user_id`, `from_nick`, `user_id_to`, `read`, `attach` FROM aloaz_db.`conversation_reply` WHERE ((`user_id` = '".$id."' AND `user_id_to` = '".$toid."' AND `deleted_by` != '".$id."') OR (`user_id` = '".$toid."' AND `user_id_to` = '".$id."' AND `deleted_by` != '".$id."')) ORDER BY `id` DESC LIMIT ".$start.", ".$show_limit.";");

if(mysql_num_rows($query) == 0){
	echo $__lng['msj yoxdur'].'<br/>';
}

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="img/smiles/'.$value.'.png" alt="'.$key.'" />';
}
foreach ($stickersArray as $key => $value) {
	$stickersArray[$key] = '<img src="img/stickers/'.$value.'.gif" alt="'.$key.'" />';
}

echo '<div style="max-width:450px;">';
while($row = mysql_fetch_array($query)){
	$lid = $row['id'];
	$message = $row['reply'];
	$date = $row['time'];
	$from = $row['user_id'];
	$from_nick = $row['from_nick'];
	$to = $row['user_id_to'];
	$read = $row['read'];
	$attach = $row['attach'];   

	$message = stripslashes($message);
	$message = str_replace(array_keys($smilesArray), array_values($smilesArray), $message);
	$message = str_replace(array_keys($stickersArray), array_values($stickersArray), $message);

	if($from == $id){
		echo '<div style="padding:0 0 10px 0;color: #212121;">';
	}else{
		echo '<div style="margin-bottom:5px;padding:0 0 5px 0;color: #212121;background-color:#F2F2F2;">';
	}
	
	if($_del == 1) echo '<a href="messages.php?mod=messaging&amp;uid='.$toid.'&amp;del='.$_del.'&amp;mid='.$lid.'&amp;back='.$_back.'">'.$__lng['sil'].'</a> ';
	if($from == $toid){
		// $nick_print = '<span style="font-size:16px;font-weight:bold;color:#0D47A1;">'.$from_nick.'</span>';
		$nick_print = '<a href="profile.php?uid='.$toid.'">'.$from_nick.'</a>';
	}else{
		$nick_print = '<a href="profile.php?uid='.$id.'">'.$from_nick.'</a>';
	}
	if(date("d-m-Y", $date) == date("d-m-Y")) $print_date = date("H:i", $date);
	else $print_date = date("d-m-Y H:i", $date);
	echo ''.$nick_print.' <span style="color:gray;font-size:12px;">'.$print_date.'';
	if($read == 0) echo ' ('.$__lng['oxunmayib'].') ';
	echo '</span><br/>';
	if(empty($attach)){
		echo '<span style="padding:5px 0 0 3px;">'.$message.'</span><br/>';
	}
	echo '<span style="font-size: x-small;">';
	if(!empty($attach)){
		$exts = split("[/\\.]", strtolower($attach)); 
		$n = count($exts)-1; 
		$attach_ext = $exts[$n];
		$attach_fsize = filesize('attach/data/temp/'.$attach.'');
		if($attach_ext == 'jpg' || $attach_ext == 'jpeg' || $attach_ext == 'gif' ||  $attach_ext == 'png') echo ' <a href="attach/data/temp/'.$attach.'"><img src="attach/image.php?id='.base64_encode($attach).'" alt="'.$attach_ext.'" /></a><br/>'.formatSizeUnits($attach_fsize);
		else echo ' <a href="attach/data/temp/'.$attach.'"><img src="img/filetypes/'.$attach_ext.'.png" alt="'.$attach_ext.'" /></a><br/>['.formatSizeUnits($attach_fsize).']';
		echo '<br/>';
	}
	echo ' ';
	
	echo '</span></div>';
}

?>
</div>
<div style="clear: both;"></div>
<?

if($toid == 1) echo '<br/>'.$__lng['alochat xidmeti gonderib'].'<br/>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="messages.php?mod=messaging&amp;del='.$_del.'&amp;uid='.$toid.'&amp;page='.($page - 1).'&amp;back='.$_back.'">&lt;</a> ';

if($page > $interval) echo " <a href=\"messages.php?mod=messaging&amp;del=".$_del."&amp;uid=".$toid."&amp;page=1&amp;back=".$_back."\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"messages.php?mod=messaging&amp;del=".$_del."&amp;uid=".$toid."&amp;page=".$i."&amp;back=".$_back."\">".$i."</a> ";
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"messages.php?mod=messaging&amp;del=".$_del."&amp;uid=".$toid."&amp;page=".$i."&amp;back=".$_back."\">".$i."</a> ";
			}
			else{
				echo ' <span id="pageButon_off">'.$i.'</span> ';
			}
		}
		
	}
}
if($page <= $max - $interval) echo '... ';

if($max > $interval){
	if($max != $page){
		echo " <a href=\"messages.php?mod=messaging&amp;del=".$_del."&amp;uid=".$toid."&amp;page=".$max."&amp;back=".$_back."\">".$max."</a> ";
	}
	else{
		echo ' <span id="pageButon_off">'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="messages.php?mod=messaging&amp;del='.$_del.'&amp;uid='.$toid.'&amp;page='.($page + 1).'&amp;back='.$_back.'">&gt;</a> ';
echo '</div><br/>';

echo '<a href="messages_del.php?uid='.$toid.'">'.$__lng['mesajlari sil'].'</a><br/>';

break;





case 'unread':

if(isset($_GET['delete'])){
	$uid = intval($_GET['uid']);
	$q = mysql_query("DELETE FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `user_id` = '".$uid."' AND `read` = '0';");
	if(mysql_affected_rows() > 0){
		echo $__lng['msj silindi'].'<br/>';
	}
	else{
		echo $__lng['msj tapilmadi'].'<br/>';
	}
}

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

echo '<a href="messages.php?mod=unread&amp;refresh='.rand(1111, 9999).'&amp;back='.$_back.'">'.$__lng['yenile'].'</a><br/>';
echo "<a href=\"messages.php?mod=recent\">".$__lng['msj tarixcesi']."</a><br/><br/>";

if($count_unread == 0){
	echo $__lng['yeni msj yoxdur'].'<br/>';
}
else{
	echo $__lng['oxunmamis mesajiniz var'].'<br/><br/>';
	
	$all_rows = mysql_num_rows(mysql_query("SELECT `id` FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0 GROUP BY `user_id`"));
	
	$show_limit = 10;
	if(isset($_GET['page'])) $page = $_GET['page'];
	else $page = 1;
	if($page < 1) $page = 1;
	if($page > $all_rows) $page = 1;
	$start = ($page-1)*$show_limit;
	
	$unread_query = mysql_query("SELECT `from_nick`, `user_id`, COUNT(`user_id`), `time` FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0 GROUP BY `user_id` ORDER BY time DESC LIMIT ".$start.", ".$show_limit."");
	while($unread_row = mysql_fetch_array($unread_query)){
		$from_nick = $unread_row['from_nick'];
		$from = $unread_row['user_id'];
		$time = $unread_row['time'];
		$c_unread = $unread_row['COUNT(`user_id`)'];
		
		if(date("d-m-Y", $time) == date("d-m-Y")) $print_date = date("H:i", $time);
		else $print_date = date("d-m-Y H:i", $time);
		
		//$count_unread_q = mysql_query("SELECT COUNT(`id`) FROM `chat_messages` WHERE `user_id` = '".$from."' AND `user_id_to` = '".$id."' AND `read` = '0' ;");
		//$count_unread = mysql_result($count_unread_q, 0);
		echo '<b>'.$from_nick.'</b> ('.$print_date.')<br/><a href="messages.php?mod=messaging&amp;uid='.$from.'&amp;back='.$_back.'">['.$__lng['oxu'].' ('.$c_unread.')]</a> ';
		echo '[<a href="messages.php?mod=unread&amp;delete&amp;uid='.$from.'">'.$__lng['sil'].'</a>]';
		echo '<br/><br/>';
	}
	
	if($page > 1) echo "<a href=\"messages.php?mod=unread&amp;page=".($page - 1)."&amp;back=".$_back."\">".htmlspecialchars("<<<")."</a> | ";
	if($all_rows > $start + $show_limit) echo "<a href=\"messages.php?mod=unread&amp;page=".($page + 1)."&amp;back=".$_back."\">".htmlspecialchars(">>>")."</a>";
	if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';
	
	echo '<br/>'.$__lng['hefteden cox msjlar silinir'].'<br/>';
	echo '<br/><a href="messages.php?mod=clear">'.$__lng['butun yeni msjlari sil'].'</a><br/>';
}

break;


case 'recent':

function multi_array_key_exists( $needle, $haystack ) {
     foreach ( $haystack as $key => $value ) : 
         if ( $needle == $key ) 
             return true;
         if ( is_array( $value ) ) : 
              if ( multi_array_key_exists( $needle, $value ) == true ) 
                 return true; 
              else 
                  continue;
         endif;
     endforeach;
     return false;
}

$recentQuery = mysql_query("SELECT `user_id`, `user_id_to`, `time`, `read` FROM aloaz_db.`conversation_reply` WHERE (`user_id` = '".$id."' OR `user_id_to` = '".$id."') AND `deleted_by`!='".$id."' ORDER BY `time` DESC LIMIT 2000;");
while($recentRow = mysql_fetch_array($recentQuery)){
	$from = $recentRow['user_id'];
	$to = $recentRow['user_id_to'];
	$time = $recentRow['time'];
	$read = $recentRow['read'];
	
	if($to != $id) $recentNickId = $to;
	if($from != $id) $recentNickId = $from;
	if($to == $id && $read == 0) $recentRead = 0; else $recentRead = 1;
	if(!multi_array_key_exists($recentNickId, $recentArray)){
		$recentArray[date('Y-m-d',$time)][$recentNickId]['time'] = $time;
		$recentArray[date('Y-m-d',$time)][$recentNickId]['read'] = $recentRead;
	}
}
$countArray = count($recentArray);
$page = intval($_GET['page']);
$showPerPage = 3;
$maxPage = (int)($countArray/$showPerPage);

if($page > 0)$start = $page * $showPerPage;

$recentArray = array_slice($recentArray, $start, $showPerPage, true);

echo $__lng['sonuncu yazisdiqlarim'].':<br/>';

foreach($recentArray as $date => $value){
	echo '<br/>'.$date.'<br/>';
	foreach($recentArray[$date] as $rId => $value){
		$rTime = $recentArray[$date][$rId]['time'];
		$rRead = $recentArray[$date][$rId]['read'];
		$rNick = mysql_result(mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = '".$rId."';"), 0);
		
		if($rRead == 0) $rNick_ = '<a href="messages.php?mod=messaging&amp;uid='.$rId.'&amp;back=msgsrecent"><b>'.$rNick.'</b></a> [<a href="messages_del.php?uid='.$rId.'">x</a>]'; else $rNick_ ='<a href="messages.php?mod=messaging&amp;uid='.$rId.'&amp;back=msgsrecent">'.$rNick.'</a> [<a href="messages_del.php?uid='.$rId.'">x</a>]';
		if($rNick == '') echo ''.date("H:i", $rTime).' Nick silinib<br/>'; else echo ''.date("H:i", $rTime).' '.$rNick_.'<br/>';
	}
}
echo '<br/>';

if($page > 0) echo '<a href="messages.php?mod=recent&amp;page='.($page-1).'&amp;back='.$_back.'">'.$__lng['evvelki'].'</a> | ';
if($maxPage > $page) echo '<a href="messages.php?mod=recent&amp;page='.($page+1).'&amp;back='.$_back.'">'.$__lng['novbeti'].'</a>';

echo '<br/>';
echo '';

break;


case 'conv':

if(isset($_GET['delete'])){
	$uid = intval($_GET['uid']);
	$q = mysql_query("DELETE FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `user_id` = '".$uid."' AND `read` = '0';");
	if(mysql_affected_rows() > 0){
		echo $__lng['msj silindi'].'<br/>';
	}
	else{
		echo $__lng['msj tapilmadi'].'<br/>';
	}
}

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' OR `user_id` = '".$id."'");
$count_unread = mysql_result($unread_count, 0);

echo '<a href="messages.php?mod=conv&amp;refresh='.rand(1111, 9999).'&amp;back='.$_back.'">'.$__lng['yenile'].'</a><br/><br/>';

if($count_unread == 0){
	echo $__lng['yeni msj yoxdur'].'<br/>';
}
else{
	echo $__lng['oxunmamis mesajiniz var'].'<br/>';
	
	$all_rows = mysql_num_rows(mysql_query("SELECT `id` FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' OR `user_id` = '".$id."' GROUP BY `user_id`"));
	//echo $count_allunread.'<br/>';
	
	$show_limit = 10;
	if(isset($_GET['page'])) $page = $_GET['page'];
	else $page = 1;
	if($page < 1) $page = 1;
	if($page > $all_rows) $page = 1;
	$start = ($page-1)*$show_limit;
	
	$unread_query = mysql_query("SELECT `from_nick`, `user_id`, COUNT(`user_id`), `time` FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0 GROUP BY `user_id` ORDER BY time DESC LIMIT ".$start.", ".$show_limit."");
	while($unread_row = mysql_fetch_array($unread_query)){
		$from_nick = $unread_row['from_nick'];
		$from = $unread_row['user_id'];
		$time = $unread_row['time'];
		$c_unread = $unread_row['COUNT(`user_id`)'];
		
		if(date("d-m-Y", $time) == date("d-m-Y")) $print_date = date("H:i", $time);
		else $print_date = date("d-m-Y H:i", $time);
		 
		echo ''.$from_nick.' ('.$print_date.') <a href="messages.php?mod=messaging&amp;uid='.$from.'&amp;back='.$_back.'">['.$__lng['oxu'].' ('.$c_unread.')]</a> ';
		echo '[<a href="messages.php?mod=conv&amp;delete&amp;uid='.$from.'&amp;back='.$_back.'">'.$__lng['sil'].'</a>]';
		echo '<br/>';
	}
	
	if($page > 1) echo "<a href=\"messages.php?mod=conv&amp;page=".($page - 1)."\">".htmlspecialchars("<<<")."</a> | ";
	if($all_rows > $start + $show_limit) echo "<a href=\"messages.php?mod=conv&amp;page=".($page + 1)."\">".htmlspecialchars(">>>")."</a>";
	if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';
	
	echo '<br/>'.$__lng['hefteden cox msjlar silinir'].'<br/>';
	echo '<br/><a href="messages.php?mod=clear">'.$__lng['butun yeni msjlari sil'].'</a><br/>';
}

break;


case 'clear':
if($_GET['confirm'] == 'ok'){
	mysql_query("DELETE FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = '0';");
	if(mysql_affected_rows() > 0){
		echo $__lng['oxunmamis msjlar silindi'].'!<br/>';
	}
	else{
		echo $__lng['oxunmamis msj yoxdur'].'<br/>';
	}
}
else{
	echo $__lng['oxunmamis msj silmek eminsiz'].'<br/>';
	echo '- <a href="messages.php?mod=clear&amp;confirm=ok">'.$__lng['beli'].'</a><br/>';
	echo '- <a href="messages.php?mod=unread">'.$__lng['xeyr'].'</a><br/>';
}
break;

}

echo '</div>';
include 'inc/footer.php';
?>