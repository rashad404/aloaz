<?php
error_reporting(0);
session_start();
exit;

include '../inc/func.php';
include '../inc/functions.php';
include '../inc/config.php';

$title = 'AloChat';
include '../inc/header.php';
	
echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="messages_all.php">Yenile</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError('Yalnız qeydiyyatlı istifadeçiler bloq yarada bilerler.<br/>'.
	'Xahiş edirik istifadeçi adı ve şifrenizle sayta daxil olun.<br/><br/>'.
	'<a href="login.php?loc=blog">Giriş</a> | <a href="http://server-saytim.net/chat/registration.php?loc=blog">Qeydiyyat</a>', 2);

}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$md5_pass = $userrow['md5_pass'];
$name = $userrow['name'];
$birth_day = $userrow['gun'];
$birth_month = $userrow['ay'];
$birth_year = $userrow['il'];
$weight = $userrow['mobile'];
$height = $userrow['site'];
$sex = $userrow['sex'];
$about = $userrow['about'];
$friends = $userrow['friends'];
$dating = $userrow['dating'];

switch($_GET['mod']){

default:
if(isset($_GET['uid'])){
	$uid = intval($_GET['uid']);
	$mysql_where = " WHERE `from`='".$uid."' ";
}
$query = mysql_query("SELECT * FROM `chat_messages` ".$mysql_where." ORDER BY `time` DESC LIMIT 100;");

if(mysql_num_rows($query) == 0){
	echo 'Müraciet tapılmadı<br/>';
	break;
}

while($row = mysql_fetch_array($query)){
	$supp_id = $row['id'];
	$from = $row['from'];
	$to = $row['to'];
	$nick_q = mysql_fetch_array(mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id`='".$to."'"));
	$to_nick = $nick_q[0];
	$from_nick = $row['from_nick'];
	$message = $row['message'];
	$time = $row['time'];

	
	echo '<div class="support">';
	echo '<span style="font-size:11px">'.date('d-m-Y H:i', $time).'</span> | ';
	echo 'From: <a href="../profile.php?uid='.$from.'">'.$from_nick.'</a> <a href="messages_all.php?uid='.$from.'">»»»</a> | ';
	echo 'To: <a href="../profile.php?uid='.$to.'">'.$to_nick.'</a> <a href="messages_all.php?uid='.$to.'">»»»</a><br/>';
	echo 'Mesaj: '.$message.'<br/>';
	echo '</div>';
}

break;
}

echo '</div><br/>';
include 'inc/footer.php';
?>
