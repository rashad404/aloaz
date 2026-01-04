<?php
session_start();

$_id = intval($_GET['id']);
if($_SESSION['auth']){
	header('location: http://server-saytim.net/chat/info.php?id='.$_SESSION['id'].'&password='.$_SESSION['password'].'&uid='.$_id.'');
	exit();
}

$title = 'Bloq - BETA 1.0';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include '/home/admin/domains/server-saytim.net/public_html/chat/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="?">'.$title.'</a></div>';
echo '<div class="layer">';
echo '<b>Diqqet!</b><br/>Yalnız qeydiyyatlı istifadeçiler daxil ola biler.<br/>';
echo 'Chatdan qeydiyyatdan keç, bloqun bütün imkanlarından istifade et!<br/><br/>';
echo '<a href="http://server-saytim.net/chat">Daxil ol</a>';
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
