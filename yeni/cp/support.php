<?php
error_reporting(0);
session_start();

include '../inc/func.php';
include '../inc/functions.php';
include '../inc/config.php';

$title = 'AloChat';
include '../inc/header.php';
echo '<script type="text/javascript" src="js/jquery.min.js"></script>';
echo '<script type="text/javascript" src="js/support_add_answer.js"></script>';
	
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="support.php">Müraciet</a></div>';
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

if($id != 1096824 && $id != 785) exit;

switch($_GET['mod']){

default:

$query = mysql_query("SELECT * FROM `chat_support` ORDER BY `time` DESC LIMIT 100;");

if(mysql_num_rows($query) == 0){
	echo 'Müraciet tapılmadı<br/>';
	break;
}

while($row = mysql_fetch_array($query)){
	$supp_id = $row['id'];
	$supp_uid = $row['uid'];
	$nick_q = mysql_fetch_array(mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id`='".$supp_uid."'"));
	$supp_nick = $nick_q[0];
	$supp_title = $row['title'];
	$supp_body = $row['body'];
	$supp_answer = $row['answer'];
	$supp_time = $row['time'];
	
	echo '<div class="support">';
	echo '<span style="font-size:11px">'.date('d-m-Y H:i', $supp_time).'</span><br/>';
	echo 'Login: <a href="../profile.php?uid='.$supp_uid.'">'.$supp_nick.'</a><br/>';
	echo 'Başlıq: '.$supp_title.'<br/>';
	echo 'Müraciet: '.$supp_body.'<br/><hr />';
	echo 'Cavab: ';
	echo '
		<input name="answer" class="answer" supp_id="'.$supp_id.'" value="'.$supp_answer.'"/>
		<span style="color:red;" id="'.$supp_id.'" class="result"></span>
	';
	echo '</div>';
}

break;
}

echo '</div><br/>';
include 'inc/footer.php';
?>
