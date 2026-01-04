<?php
session_start();

include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include '/home/admin/domains/server-saytim.net/public_html/chat/config.php';

$loc = trim(htmlspecialchars(mysql_escape_string($_GET['loc'])));

if(strlen($loc) > 50) $loc = '';

if(isset($_REQUEST['id']) && isset($_REQUEST['password'])){
	$checkAuth = checkAuthBlog();
	if($checkAuth == 'error'){
		$error = 'İstifadeçi adı ve ya şifre yanlışdır.';
	}
	else{
		if($loc == '') $loc = '/blog/';
		header('location: '.$loc.'');
		exit();
	}
}

$title = 'Giriş';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav">Giriş</div>';
echo '<div class="layer">';
echo $error_code;
echo '<form method="post" action="login.php">';
if(!empty($error)) echo 'Sehv: <span style="color:red;">'.$error.'</span><br/><br/>';
echo 'ID:<br/>';
echo '<input type="text" name="id" /><br/>';
echo 'Şifre:<br/>';
echo '<input type="password" name="password" /><br/>';
echo '<input type="submit" name="submit" value="Daxil ol" /></form><br/>';

echo '<a href="http://m.alo.az/chat/registration.php?loc=blog">Qeydiyyat</a><br/>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
