<?
//error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'Alochat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="friends.php">'.$__lng['dostlar'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=friends">'.$__lng['giris'].'</a> | <a href="reg.php?loc=friends">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$_uid = checkData($_GET['uid']);

$mod = checkData($_GET['mod']);
switch($mod){
	case 'send':
	
	$send_confirm = intval($_GET['send_confirm']);
	if($send_confirm == 0){
		echo $__lng['dostluq gondermeye eminsiniz'].'<br/><br/>';
		echo $__lng['beli eminem'].'. <a href="frequest.php?mod=send&amp;uid='.$_uid.'&amp;send_confirm=1">'.$__lng['sorgu gonder'].'</a><br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	
	$checkFriendQuery = mysql_query("SELECT `uid` FROM `chat_friends` WHERE (`id` = '".$id."' AND `uid` = '".$_uid."') OR (`id` = '".$_uid."' AND `uid` = '".$id."');");
	if(mysql_num_rows($checkFriendQuery) > 0){
		echo $__lng['dost sorgusu gondermisiniz'].'<br/><br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}

	mysql_query("INSERT INTO `chat_friends` SET `id` = '".$id."', `uid` = '".$_uid."', `ok1` = '1', `time` = '".time()."';");
	if(mysql_affected_rows()>0){
		echo $__lng['dost sorgusu gonderildi'].'<br/><br/>';
		echo $__lng['dostlugun tesdiqlenmesini gozleyin'].'<br/>';
	}
	break;
	
	
	case 'confirm':
	$checkFriendQuery = mysql_query("SELECT `uid` FROM `chat_friends` WHERE `id` = '".$_uid."' AND `uid` = '".$id."';");
	if(mysql_num_rows($checkFriendQuery) == 0){
		echo $__lng['dostluq sorgusu tapilmadi'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}

	mysql_query("UPDATE `chat_friends` SET `ok2` = '1' WHERE `id` = '".$_uid."' AND `uid` = '".$id."';");
	if(mysql_affected_rows()>0){
		echo $__lng['dostluq qebul olundu'].'<br/>';
	}
	break;
	
	
	case 'del':
	
	$del_confirm = intval($_GET['del_confirm']);
	if($del_confirm == 0){
		echo $__lng['dostluq legvinden sonra melumati'].'<br/>';
		echo $__lng['dost siyahisindan silinsinmi'].'<br/><br/>';
		echo $__lng['beli eminem'].'. <a href="frequest.php?mod=del&amp;uid='.$_uid.'&amp;del_confirm=1">'.$__lng['legv et'].'</a><br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	
	$checkFriendQuery = mysql_query("SELECT `uid` FROM `chat_friends` WHERE (`id` = '".$_uid."' AND `uid` = '".$id."') OR (`id` = '".$id."' AND `uid` = '".$_uid."');");
	if(mysql_num_rows($checkFriendQuery) == 0){
		echo $__lng['sorgu tapilmadi'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}

	mysql_query("DELETE FROM `chat_friends` WHERE (`id` = '".$_uid."' AND `uid` = '".$id."') OR (`id` = '".$id."' AND `uid` = '".$_uid."');");
	if(mysql_affected_rows()>0){
		echo $__lng['dost sorgusu silindi'].'<br/>';
	}
	break;
	
}

echo '</div>';
include 'inc/footer.php';

?>
