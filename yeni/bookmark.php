<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$_auth = checkData($_GET['auth']);
	
if(intval($_GET['t']) < (time()-30)){

	$expAuth = explode("-", $_auth);
	$id = intval($expAuth[0]);
	$password = $expAuth[1];

	$userQuery = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$id."' AND `md5_pass` = '".$password."';");
	if(mysql_num_rows($userQuery)>0){
		$login = mysql_result($userQuery, 0, 'nickname');
		
		$_SESSION['login'] = checkData($login);
		$_SESSION['password'] = checkData($password);
		
		header('location: main.php?'.session_name().'='.session_id().'&ref=bookmark');
	}
	else{
		echo $__lng['sifre yanlisdir'].'.<br/>';
		header('location: index.php?ref=bookmark');
	}
	exit;
}

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav">'.$title.' » '.$__lng['bookmark'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=bookmark">'.$__lng['giris'].'</a> | <a href="reg.php?loc=bookmark">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

echo $__lng['sifre daxil etmemek ucun'].'<br/><br/>';
echo $__lng['yalniz bu sehife bookmark'].'<br/><br/>';
echo '<input type="text" name="bookmark" value="http://m.alo.az/bookmark.php?auth='.$_auth.'"/><br/><br/>';

echo '</div>';
include 'inc/footer.php';
?>
