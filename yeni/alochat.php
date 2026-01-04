<?
ob_start();
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> </div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `password`,`nickname`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
	exit;
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$a_user = mysql_fetch_assoc(mysql_query("select id,nickname,password from `admin_alochat`.`user` where id='".$id."'"));
 if($a_user and $a_user["nickname"]==$userrow["nickname"] and $a_user["password"]==$userrow["password"]){

	$user_log = mysql_fetch_assoc(mysql_query("select id from `admin_alochat`.`user_logs` where user_id='".$id."' and login_status=0 and login_time=0"));
	if($user_log){

		$rand = rand("1000","9999");
		mysql_query("UPDATE `admin_alochat`.`user_logs` SET rand ='".$rand."' where id='".$user_log["id"]."'");
        $code = md5(md5($rand."aloaz123".$userrow["id"].$userrow["nickname"].$userrow["password"].$userrow["id"]."alochat456".$rand));
		$link = "http://alochat.com/site/login-alo/".$id."?sc=".$code;
		//echo $link;
		header("Location:".$link);  exit;
	}else{
		echo "saas"; 
		$rand = rand("1000","9999");
		$insert = mysql_query("INSERT INTO `admin_alochat`.`user_logs` SET `user_id`='".$id."',`rand`='".$rand."',`login_status`=0,`login_time`=0");
		if($insert){
			$code = md5(md5($rand."aloaz123".$userrow["id"].$userrow["nickname"].$userrow["password"].$userrow["id"]."alochat456".$rand));
			echo $link = "http://alochat.com/site/login-alo/".$id."?sc=".$code;
			header("Location:".$link); exit;
		}
	}
 }else {
	echo "uygun deyil1";
}
?>