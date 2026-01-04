<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/params.php';
include '../inc/smiles.php';
include '../inc/stickers.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

$_rid = 10;//intval($_GET['rid']); 

if(isset($_GET["mod"]) and trim($_GET["mod"])=='winner'){
	$begin_date = strtotime(date("d-m-Y 0:0"));
	$end_date = strtotime(date("d-m-Y 23:59"));

	$winnerQuery = mysql_query("SELECT `winner_id`,count(`id`) as c FROM `aloaz_db`.`viktorina` WHERE `end_time`>'".$begin_date."' and `end_time`<'".$end_date."' GROUP BY `winner_id`;");
	 while($row = mysql_fetch_assoc($winnerQuery)){
		echo $row["winner_id"]." - ".$row["c"]."<br />";
	}
}


$roomQuery = mysql_query("SELECT `name`, `view`, `uid`, `login` FROM `aloaz_db`.`room` WHERE `id` = '".$_rid."'");
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

$viktorina_query = mysql_query("SELECT * FROM `aloaz_db`.`viktorina` WHERE status=0 ORDER BY `id` DESC");

if(mysql_num_rows($viktorina_query)>0){
 	$viktorina = mysql_fetch_assoc($viktorina_query);
	
	if(($viktorina["help_time"]+$paramsArray["viktorina_help_time"])<time()){ 
		$help_count = $viktorina["help"]+1; 
		if($help_count<strlen($viktorina["answer"])-1){
			$letter = substr($viktorina["answer"],0,$help_count);
			$message_text = "Kömek ".($viktorina["help"]+1).":".$letter."...";
			mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = '".$roomAdmin."', `message` = '".$message_text."', `uid` = '".$roomUid."', `rid` = '".$_rid."', `time` = '".time()."'");
			mysql_query("UPDATE `aloaz_db`.`viktorina` SET `help`=`help`+1,`help_time`='".time()."' WHERE `id`='".$viktorina["id"]."'");
		}else{
			mysql_query("UPDATE `aloaz_db`.`viktorina` SET `status`=1,`end_time`='".time()."',`winner_id`='0' WHERE `id`='".$viktorina["id"]."'");
		}
		
	}
}else{
	$last_question = mysql_fetch_assoc(mysql_query("SELECT `question_id`,`end_time` FROM `aloaz_db`.`viktorina` WHERE status=1 ORDER BY `id` DESC"));
	$q_id = 0;
	if($last_question){
 		$q_id = $last_question["question_id"];
	} 
	
	if(($last_question["end_time"]+$paramsArray["viktorina_question_time"]<time()) or !$last_question){
		$question = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`chat_questions` WHERE `id`>'".$q_id."' LIMIT 1"));
		if(!$question){
			$q_id = 0;
			$question = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`chat_questions` WHERE `id`>'".$q_id."' LIMIT 1"));
		}
		
		$question_id = $question["id"];
		$question_text = $question["question"];
		$question_answer = $question["answer"];
		
		$question_text = $question_text.' <b>('.strlen($question_answer).' herf)</b>';
		mysql_query("INSERT INTO `aloaz_db`.`viktorina` SET `question_id`='".$question_id."',`answer`='".$question_answer."',`time`='".time()."',`help`=0,`help_time`='".time()."',`status`=0");
		$viktorina = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`viktorina` WHERE status=0 ORDER BY `id` DESC"));
		mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = '".$roomAdmin."', `message` = '".$question_text."', `uid` = '".$roomUid."', `rid` = '".$_rid."', `time` = '".time()."'");
	}	
}


echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`, `post_run`, `msg_count_day`, `coins`, `mysmile`,`question_rating`,`room_refresh`');
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
$mysmile = $userrow['mysmile'];
$question_rating = $userrow['question_rating'];
$roomUserRefreshTime = $userrow['room_refresh'];
updateOnline();

$countRoomOnline = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `place` = '".$_rid."' AND `last_activity` > '".(time()-600)."'"), 0);

mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$countRoomOnline."', `refresh` = '".time()."' WHERE `id` = '".$_rid."' LIMIT 1");

echo '<p align="center"><b>'.$roomName.'</b> '.$__lng['otagi'].' <img src="/img/online.png" alt="on" /><span style="color: green">'.$countRoomOnline.'</span><br/>';

echo '<a href="viktorina-top.php">Viktorina reytinqi</a><br/>';

echo '</p>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) echo '<a href="../messages.php?mod=unread"><img src="/img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni mesajin var'].'<br/><br/>';

echo '<form action="viktorina.php" method="post" name="room-form">';
echo '<a href="/smiles.php"><img src="/img/smiles/1f603.png" alt="Smile" /></a> <input type="text" name="message" /> ';
echo '<input type="submit" name="submit" value="'.$__lng['yaz'].'" class="submitButton" /> <a class="button" href="viktorina.php">'.$__lng['yenile'].'</a><br/>';
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
	
	$checkDupQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room_msgs` WHERE `message` = '".$_message."' AND `uid`='".$id."' AND `time` > '".(time()-10)."' AND `rid` = '".$_rid."' LIMIT 1");
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
		else{
			$checkLimit = mysql_result(mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`room_msgs` WHERE `uid` = '".$id."' AND `time` > '".(time()-60)."'"), 0);
			if($checkLimit > 20){
				$error = true;
				mysql_query("INSERT INTO `aloaz_db`.`blocks` SET `from_id` = '1', `user_id` = '".$id."', `time`='86400',`blocked_time` = '".time()."',`ip`='".$block_ip."',`ua`='".$block_ua."',`reason`='3',`description`='Spam (Avtomatik)';");
				mysql_query("UPDATE `aloaz_db`.`user` SET `block_time`='86400',`block_begin_time`=0 WHERE id='".$id."' LIMIT 1");
			}
		}
		if(!$error){
			
			mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = '".$login."', `message` = '".$_message."', `uid` = '".$id."', `rid` = '".$_rid."', `time` = '".time()."' ".$ins_to."");		
					
			
			if(mysql_affected_rows() > 0){
				if(strtolower($viktorina["answer"]) == strtolower(trim($_message))){				
					$q_msgs = 'Tebrikler <b>'.$login.'</b>! Cavab doğrudur. Doğru cavablarınızın sayı: '.($question_rating+1).'. Növbeti sual '.$paramsArray["viktorina_question_time"].' saniye sonra...';
					mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = '".$roomAdmin."', `message` = '".$q_msgs."', `uid` = '".$roomUid."', `rid` = '".
					$_rid."', `time` = '".time()."'");
					mysql_query("UPDATE `aloaz_db`.`viktorina` SET `status`=1,`end_time`='".time()."',`winner_id`='".$id."' WHERE `id`='".$viktorina["id"]."'");
					mysql_query("UPDATE `aloaz_db`.`user` SET `question_rating` = `question_rating` + 1 WHERE `id` = '".$id."' LIMIT 1");

				} 
				
				if($post_run == 0){
					mysql_query("UPDATE `aloaz_db`.`user` SET `msg_count` = `msg_count` + 1, `msg_count_day` = `msg_count_day` + 1 WHERE `id` = '".$id."' LIMIT 1");

				}	
				
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



$query = mysql_query("SELECT * FROM `aloaz_db`.`room_msgs` WHERE `rid` = '".$_rid."' AND (`to` = 0 OR `to` = '".$id."' OR `uid` = '".$id."') $not_user_where ORDER BY `id` DESC LIMIT ".$start.", ".$show_limit.";");
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
		
		$rnickname = mysql_result(mysql_query("SELECT `rnickname` FROM `aloaz_db`.`user` WHERE `id` = '".$msg_uid."';"), 0);
		if($rnickname != ""){
			$msg_login = '<img src="../rn/tmp/'.$rnickname.'" style="vertical-align:middle" alt="'.$msg_login.'"/>';
		}
		
		if($msg_to != 0) $g = '<b>[G!]</b>'; else $g = '';
		if($msg_uid == 1) $admin_icon = '#'; else $admin_icon = '';
		echo '<div style="margin-bottom:8px">'.$g.' '.$admin_icon.'<a href="../profile.php?uid='.$msg_uid.'&amp;rid='.$_rid.'">'.$msg_login.'</a>: '.$msg_message.'<br/><span style="font-size: 12px;">'.$msg_date.'</span></div>';
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
