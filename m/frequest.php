<?
//error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
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
$login = $userrow["nickname"];

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
	
 	$checkFriendQuery = mysql_query("SELECT `user_2` FROM `aloaz_db`.`user_friend` WHERE (`user_1` = '".$id."' AND `user_2` = '".$_uid."') OR (`user_1` = '".$_uid."' AND `user_2` = '".$id."');");
	if(mysql_num_rows($checkFriendQuery) > 0){
		echo $__lng['dost sorgusu gondermisiniz'].'<br/><br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}

 	mysql_query("INSERT INTO `aloaz_db`.`user_friend` SET `user_1` = '".$id."', `user_2` = '".$_uid."', `ok` = '0', `seen`=0,`ok_time`=0,`request_time` = '".time()."';");
	if(mysql_affected_rows()>0){
		setNotification($_uid,$paramsArray["NOT_USER_FRIEND"],time(),$id,$login,0,0);

		echo $__lng['dost sorgusu gonderildi'].'<br/><br/>';
		echo $__lng['dostlugun tesdiqlenmesini gozleyin'].'<br/>';
	}
	break;
	
	
	case 'confirm':
	$checkFriendQuery = mysql_query("SELECT `user_2` FROM `aloaz_db`.`user_friend` WHERE `user_1` = '".$_uid."' AND `user_2` = '".$id."';");
	if(mysql_num_rows($checkFriendQuery) == 0){
		echo $__lng['dostluq sorgusu tapilmadi'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}

 	mysql_query("UPDATE `aloaz_db`.`user_friend` SET `ok` = '1' WHERE `user_1` = '".$_uid."' AND `user_2` = '".$id."';");
	if(mysql_affected_rows()>0){
		setNotification($_uid,$paramsArray["NOT_USER_FRIEND_REQUEST_CONFIRM"],time(),$id,$login,0,0);

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
	
 	$checkFriendQuery = mysql_query("SELECT `user_2` FROM `aloaz_db`.`user_friend` WHERE (`user_1` = '".$_uid."' AND `user_2` = '".$id."') OR (`user_1` = '".$id."' AND `user_2` = '".$_uid."');");
	if(mysql_num_rows($checkFriendQuery) == 0){
		echo $__lng['sorgu tapilmadi'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}

 	mysql_query("DELETE FROM `aloaz_db`.`user_friend` WHERE (`user_1` = '".$_uid."' AND `user_2` = '".$id."') OR (`user_1` = '".$id."' AND `user_2` = '".$_uid."');");
	if(mysql_affected_rows()>0){
		setNotification($_uid,$paramsArray["NOT_USER_FRIEND_REQUEST_REMOVE"],time(),$id,$login,0,0);
		echo $__lng['dost sorgusu silindi'].'<br/>';
	}
	break;
	
}

echo '</div>';
include 'inc/footer.php';

?>
