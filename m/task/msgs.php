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

$_tid = intval($_GET['tid']);

$taskQuery = mysql_query("SELECT `name`, `uid`,`type`,`del_time`,`create_time`,`status` FROM `aloaz_db`.`task` WHERE `id` = '".$_tid."'");
if(mysql_num_rows($taskQuery) == 0){
	echo 'Müraciet tapılmadı.<br/>';
	echo '</div>';
	include '../inc/footer.php';
	exit;
}
$taskRow = mysql_fetch_array($taskQuery);
$taskName = $taskRow['name']; 
$taskUid = $taskRow['uid']; 
$taskType = $taskRow['type'];
$taskDelTime = $taskRow['del_time'];
$taskCreateTime = $taskRow['create_time'];
$taskStatus = $taskRow['status'];

$taskMsgsCount = mysql_num_rows(mysql_query("SELECT id FROM `aloaz_db`.`task_msgs` WHERE `tid`='".$_tid."'"));
echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php"> Müraciet </a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`, `post_run`, `msg_count_day`, `coins`,`mysmile`,`user_status`');
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
$mysmile = $userrow["mysmile"];
$user_status = $userrow["user_status"];

$adminlogin = false;
if($id==1129446 or $id==1129447){
	$adminlogin = true;
	$login = 'Administrator'; 
}

if($user_status!=10 and $adminlogin ==false){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}


echo '<p align="center"><b>'.$taskName.'</b> <span style="color: green"> - '.$taskMsgsCount.' mesaj</span></p><br/>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);
if($count_unread > 0) echo '<a href="../messages.php?mod=unread"><img src="/img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni mesajin var'].'<br/><br/>';

echo '<form action="msgs.php?tid='.$_tid.'" method="post">';
echo ' <input type="text" name="message" /> ';
echo '<input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /> <a class="button" href="msgs.php?tid='.$_tid.'">'.$__lng['yenile'].'</a><br/>';
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
				}
			}
		} 
		if(!$error){
			if($mysmile==1 and strpos($_message,".my.")!==false){
				$smile = mysql_fetch_assoc(mysql_query("SELECT `smile` FROM `aloaz_db`.`smiles` WHERE `user_id`='".$id."' ORDER BY `id` DESC LIMIT 1"));
				if($smile){
					$_message = str_replace(".my.",$smile["smile"],$_message);
				}	
			}
			
			mysql_query("INSERT INTO `aloaz_db`.`task_msgs` SET `login` = '".$login."', `message` = '".$_message."', `uid` = '".$id."', `tid` = '".$_tid."', `time` = '".time()."' ".$ins_to."");
			 
		}
	}
} 

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`task_msgs` WHERE `tid` = '".$_tid."'");
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



$query = mysql_query("SELECT * FROM `aloaz_db`.`task_msgs` WHERE `tid` = '".$_tid."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo 'Mesaj yoxdur <br/><br/>';
}
else{
	while($row = mysql_fetch_array($query)){
		$msg_id = $row['id'];
		$msg_login = $row['login'];
		$msg_message = $row['message'];
		$msg_uid = $row['uid']; 
		$msg_time = $row['time'];
		
		$msg_message = htmlspecialchars($msg_message);
		$msg_message = stripslashes($msg_message);	
		
		
		$msg_message = str_replace(array_keys($smilesArray), array_values($smilesArray), $msg_message);
		$msg_message = str_replace(array_keys($stickersArray), array_values($stickersArray), $msg_message);
		
	 
		
		if(date('d-m-Y', $msg_time) == date('d-m-Y')) $msg_date = ''.date('H:i', $msg_time); else $msg_date = date('d-m-Y H:i', $msg_time);
		if($msg_to != 0) $g = '<b>[G!]</b>'; else $g = '';
		echo '<div style="margin-bottom:8px">'.$g.'<b>'.$msg_login.'</b>: '.$msg_message.'<br/><span style="font-size: x-small;">'.$msg_date.'</span></div>';
	}

	echo '<br/>';
	if($page > 1) echo '<a id="pageButon" href ="?rid='.$_rid.'&amp;page='.($page-1).'">&lt;</a> ';
	if($page < $max) echo '<a id="pageButon" href ="?rid='.$_rid.'&amp;page='.($page+1).'">&gt;</a>';
	if($page > 1 || $page < $max) echo '<br/><br/>';
}

echo '</div>';
include '../inc/footer.php';
?>
