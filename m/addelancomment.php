<?php
session_start();

$title = 'Şərhlər';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/lang/pack.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

echo '<div class="mnav"><a href="/main.php">AloChat</a> » <a href="comments.php?id='.$_id.'">'.$__lng['sherhler'].'</a></div>';
echo '<div class="layer">';

$checkQuery = mysql_query("SELECT `id` FROM aloaz_db.`elan` WHERE `id` = '".$_id."';");
if(mysql_num_rows($checkQuery) == 0){
	echo 'Unknown share<br/>';
	echo '</div>';
	include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
	exit;
}

$checkAuth = checkAuth('`id`, `phone`, `mysmile`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=share">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=share">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$uid = $userrow['id'];
$phone = $userrow['phone'];
$mysmile = $userrow['mysmile'];

if(!isset($_POST['secnumber'])){
	$secnumber = rand(1000, 9999);
	$_SESSION['secnumber'] = $secnumber;
	
	if(strlen($phone) != 12){
		echo '<div class="notif">Yalnız nömrəsi təsdiqlənən istifadəçilər şərh yaza bilər - <a href="submitphone.php">'.$__lng['tesdiqle'].'</a></div><br/>';
	}
	
	echo '* '.$__lng['qeyri etik reklam yazilar olmaz'].'<br/><br/>';
	
	echo '<form method="post">';
	echo $__lng['sherh'].':<br/>';
	echo "<input type=\"text\" name=\"comment\"/><br/>";
	echo '<input type="submit" value="'.$__lng['elave et'].'">';
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
	
	if($secnumber != $_SESSION['secnumber']) $error .= '- '.$__lng['ardicil elave olmaz'].'<br/>';
	//if(strlen($phone) != 12) $error .= '- Nömrə təsdiqlənməyib. Yalnız nömrəsi təsdiqlənən istifadəçilər şərh yaza bilər.<br/>';
	if(empty($comment)) $error .= '- '.$__lng['sherh yazilmayib'].'<br/>';

	if(!empty($error)){
		echo 'Aşağıdakı səhvlər baş verdi: <br/><br/>';
		echo '<span style="color: red">'.$error.'</span><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a></div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	
	if($mysmile==1 and strpos($comment,".my.")!==false){
		$smile = mysql_fetch_assoc(mysql_query("SELECT `smile` FROM `aloaz_db`.`smiles` WHERE `user_id`='".$uid."' ORDER BY `id` DESC LIMIT 1"));
		if($smile){
			$comment = str_replace(".my.",$smile["smile"],$comment);
		}	
	}
	
	mysql_query("INSERT INTO aloaz_db.`elan_comment` SET `uid` = '".$uid."', `elan_id` = '".$_id."', `comment` = '".$comment."', `time` = '".time()."'");
	if(mysql_affected_rows() > 0){
		echo $__lng['muveffeqiyyetle elave olundu'].'<br/>';
	}
	else{
		echo 'Error<br/>';
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
