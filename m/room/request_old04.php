<?
session_start();

include '../inc/func.php';
include '../inc/functions.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$_rid = intval($_GET['rid']);

$checkQuery = mysql_query("SELECT `status` FROM `room_subs` WHERE `rid` = '".$_rid."' AND `uid` = '".$id."'");
if(mysql_num_rows($checkQuery) > 0){
	$subs_status = mysql_result($checkQuery, 0);
	echo $__lng['otaga uzv sorgusu gondermisiniz'].'<br/>';
	if($subs_status > 1) echo '<br/>'.$__lng['status idareci qadaga qoyub'].'<br/>';
	if($subs_status == 0) echo '<br/>'.$__lng['status idareci baxmayib'].'<br/>';
}
else{
	mysql_query("INSERT INTO `room_subs` SET `rid` = '".$_rid."', `uid` = '".$id."', `time` = '".time()."'");
	if(mysql_affected_rows() > 0){
		echo $__lng['otaga uzv sorgusu gonderildi'].'<br/><br/>';
		echo $__lng['otaga uzvluk tesdiqlendikden sonra'].'<br/>';
	}
}

echo '<br/><a href="msgs.php?rid='.$_rid.'">« '.$__lng['otaga qayit'].'</a><br/>';

echo '</div>';
include '../inc/footer.php';
?>