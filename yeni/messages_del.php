<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$_uid = intval($_GET['uid']);
$_del = checkData($_GET['del']);
$_back = checkData($_GET['back']);

if($_GET['del'] == 0 && $_back == 'recent'){
	header('location: http://m.alo.az/messages.php?mod=recent');
	exit;
}

$title = $__lng['mesajlar'];
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">AloChat</a> » <a href="messages.php?mod=recent">'.$title.'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `status`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=messages">'.$__lng['giris'].'</a> | <a href="reg.php?loc=messages">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$uidQuery = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$_uid."';");
if(mysql_num_rows($uidQuery) == 0){
	echo $__lng['msj tapilmadi'];
	echo '</div>';
	include 'inc/footer.php';
	exit;
}
else{
	$uidRow = mysql_fetch_array($uidQuery);
	$uidLogin = $uidRow['nickname'];
}

if($_del > 0 && $_uid > 0){
	$updateQueryTo = mysql_query("UPDATE `admin_alochat`.`conversation_reply` SET `deleted_by` = '".$id."' WHERE (`user_id` = '".$id."' AND `user_id_to` = '".$_uid."') OR (`user_id_to` = '".$id."' AND `user_id` = '".$_uid."');");
 	
	if($updateQueryTo){
		echo $__lng['msj silindi'].'<br/>';
	}
	else{
		echo 'DB Delete Error<br/>';
	}
}
else{
	echo $__lng['loqinle olan msj sil eminsen'].'<br/><br/>';
	echo '<a href="messages_del.php?uid='.$_uid.'&amp;del=1&amp;back='.$_back.'">'.$__lng['beli'].'</a> - <a href="messages_del.php?uid='.$_uid.'&amp;del=0&amp;back=recent">'.$__lng['xeyr'].'</a><br/>';
}

echo '</div>';
include 'inc/footer.php';

?>
