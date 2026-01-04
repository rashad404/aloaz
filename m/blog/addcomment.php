<?php
session_start();

$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

echo '<div class="mnav"><a href="index.php">Bloq</a> » <a href="comments.php?id='.$_id.'">Şerhler</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `post`');
if($checkAuth == 'error'){
	displayError('Yalnız qeydiyyatlı istifadeçiler şerh yaza biler.<br/>'.
	'Xahiş edirik istifadeçi adı ve şifrenizle sayta daxil olun.<br/><br/>'.
	'<a href="index.php?loc=blog">Giriş</a> | <a href="/reg.php?loc=blog">Qeydiyyat</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$uid = $userrow['id'];
$posts = $userrow['post'];

if(!isset($_POST['secnumber'])){
	$secnumber = rand(1000, 9999);
	$_SESSION['secnumber'] = $secnumber;
	
	echo '* Qeyri etik, reklam xarakterli yazılar yazmaq qeti qadağandır<br/><br/>';
	
	echo '<form method="post">';
	echo "Şerh:<br/>";
	echo "<input type=\"text\" name=\"comment\"/><br/>";
	echo '<input type="submit" value="Elave et">';
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
	
	if($secnumber != $_SESSION['secnumber']) $error .= "Ardıcıl elave etmek olmaz<br/>";
	if(empty($comment)) $error .= "Şerh yazılmayıb<br/>";
	if($posts < 200) $error .= "200 postdan az postu olan istifadeçiler şerh yaza bilmez.<br/>";

	if(!empty($error)){
		echo $error.'<br/>';
		echo '<a href="javascript:history.back(1)">« Geri</a></div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	
	mysql_query("INSERT INTO `blog_com` SET `uid` = '".$uid."', `bid` = '".$_id."', `comment` = '".$comment."', `date` = '".time()."'");
	if(mysql_affected_rows() > 0){
		echo 'Müveffeqiyyetle elave olundu.<br/>';
	}
	else{
		echo 'Sehv baş verdi<br/>';
		//echo mysql_error();
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>