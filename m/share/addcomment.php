<?php
session_start();

$title = $__lng['sherhler'];
include $_SERVER['DOCUMENT_ROOT'].'/inc/func_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/params.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/lang/pack.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

echo '<div class="mnav"><a href="/main.php">AloChat</a> » <a href="comments.php?id='.$_id.'">'.$__lng['sherhler'].'</a></div>';
echo '<div class="layer">';

$checkQuery = mysql_query("SELECT `id`,`user_id` FROM aloaz_db.`share` WHERE `id` = '".$_id."';");
if(mysql_num_rows($checkQuery) == 0){
	echo 'Unknown share<br/>';
	echo '</div>';
	include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
	exit;
}
$share = mysql_fetch_assoc($checkQuery);
$checkAuth = checkAuth('`id`, `msg_count`,`nickname`,`mysmile`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=share">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=share">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$uid = $userrow['id'];
$nickname = $userrow['nickname'];
$posts = $userrow['msg_count'];
$mysmile = $userrow['mysmile'];

$checkBlockQuery = mysql_query("SELECT `block_from` FROM `aloaz_db`.`user_block` WHERE `block_from` = '".$share["user_id"]."' AND `block_to` = '".$uid."';");
if(mysql_num_rows($checkBlockQuery) > 0){
	echo '<b>'.$__lng['qadagaya gore serh yaza bilmezsiniz'].'</b><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}

if(!isset($_POST['secnumber'])){
	$secnumber = rand(1000, 9999); 
	$_SESSION['secnumber'] = $secnumber;
	
	echo '* '.$__lng['qeyri etik reklam yazilar olmaz'].'<br/><br/>';
	
	echo '<form method="post">';
	echo $__lng['sherh'].':<br/>';
	echo "<input type=\"text\" name=\"comment\"/><br/>";
	echo '<input type="submit" value="'.$__lng['elave et'].'">';
	echo '<input type="hidden" name="id" value="'.$_id.'">';
	echo '<input type="hidden" name="secnumber" value="'.$secnumber.'">';
	echo '</form>';
}
else{
	$comment = trim(htmlspecialchars(mysql_escape_string($_POST['comment'])));
	$comment = str_replace('$', '$$', $comment);
	
	$_id = intval($_POST['id']);
	$secnumber = intval($_POST['secnumber']);
	
	$error = '';
	
	if($secnumber != $_SESSION['secnumber']) $error .= $__lng['ardicil elave olmaz'].'<br/>';
	if(empty($comment)) $error .= $__lng['sherh yazilmayib'].'<br/>';

	if(!empty($error)){
		echo $error.'<br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a></div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	
	if($mysmile==1 and strpos($comment,".my.")!==false){
		$smile = mysql_fetch_assoc(mysql_query("SELECT `smile` FROM `aloaz_db`.`smiles` WHERE `user_id`='".$uid."' ORDER BY `id` DESC LIMIT 1"));
		if($smile){
			$comment = str_replace(".my.",$smile["smile"],$comment);
		}	
	}
	
	
	mysql_query("INSERT INTO aloaz_db.`share_comment` SET `uid` = '".$uid."', `sid` = '".$_id."', `comment` = '".$comment."', `time` = '".time()."'");
	if(mysql_affected_rows() > 0){
		mysql_query("UPDATE aloaz_db.`share` SET `comment_count` = `comment_count` + 1 WHERE `id` = '".$_id."' LIMIT 1");
		if($share["user_id"]!=$uid){
			setNotification($share["user_id"],$paramsArray["NOT_SHARE_COMMENT"],time(),$uid,$nickname,0,$_id);
		}		
		echo $__lng['muveffeqiyyetle elave olundu'].'<br/>';
	}
	else{
		echo 'Error<br/>';
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
