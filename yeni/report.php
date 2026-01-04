<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['sikayet'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=report">'.$__lng['giris'].'</a> | <a href="reg.php?loc=report">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];

$_uid = intval($_GET['uid']);

$query = mysql_query("SELECT `id`, `nickname` FROM `chat_users` WHERE `id` = '".$_uid."';");

if(mysql_num_rows($query) == 0){
	echo $__lng['istifadeci tapilmadi'].'<br/>';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}

$row = mysql_fetch_array($query);
$uid = $row['id'];
$u_login = $row['nickname'];

echo $__lng['sikayet olunan'].' <a href="profile.php?uid='.$uid.'">'.$u_login.'</a><br/><br/>';

if(intval($_GET['cancel']) == 1){
	mysql_query("DELETE FROM `chat_reports` WHERE `uid` = '".$_uid."' AND `reporter` = '".$id."'");
	if(mysql_affected_rows() > 0){
		echo $__lng['sikayet legv olundu'].'!<br/><br/>';
	}
}

$checkQuery = mysql_query("SELECT `id` FROM `chat_reports` WHERE `uid` = '".$_uid."' AND `reporter` = '".$id."' AND `time` > '".(time()-3600*24*30)."'");
if(mysql_num_rows($checkQuery) > 0){
	echo $__lng['evvel sikayet olunub'].'.<br/><br/>';
	echo '<a href="report.php?uid='.$_uid.'&amp;cancel=1">'.$__lng['sikayeti legv et'].'</a><br/>';
}
else{
	if($_POST['submit'] == ''){
		echo '<form action="report.php?uid='.$_uid.'" method="post">';
		echo '* '.$__lng['qayda pozuntusu tipi'].':<br/>';
		echo '<select name="type">
			<option value=""> </option>
			<option value="ads">'.$__lng['sayt reklami'].'</option>
			<option value="disturb">'.$__lng['tehqir'].'</option>
			<option value="photo">'.$__lng['erotik profil sekli'].'</option>
			<option value="status">'.$__lng['qeyri etik status'].'</option>
			<option value="other">'.$__lng['diger qayda pozuntusu'].'</option>
		</select><br/>';
		echo '* '.$__lng['melumat min herf'].':<br/>';
		echo '<input type="text" name="note" /><br/>';
		echo '<input type="submit" name="submit" value="'.$__lng['bildir'].'" class="submitButton" /><br/>';
		echo "</form><br/>";

		echo $__lng['qayda pozanlari bildirin'].'<br/><br/>';
		echo $__lng['sikayetler avtomatik rejimde'].'<br/><br/>';
		echo $__lng['sikayetcinin anonimliyi qorunur'].'<br/><br/>';
		echo '<b>'.$__lng['diqqet'].':</b> '.$__lng['yalan sikayet qadagandir'].'<br/>';
	}
	else{
		$_note = trim(mysql_escape_string($_POST['note']));
		$_type = trim(mysql_escape_string($_POST['type']));
		if(strlen($_note) < 10){
			echo $__lng['melumat min herf olmalidir'].'<br/>';
		}
		elseif(strlen($_type) == ''){
			echo $__lng['qayda pozuntusu secilmeyib'].'.<br/>';
		}
		else{
			mysql_query("INSERT INTO `chat_reports` SET `uid` = '".$_uid."', `reporter` = '".$id."', `note` = '".$_note."', `type` = '".$_type."', `time` = '".time()."'");
			if(mysql_affected_rows() > 0){
				echo $__lng['sikayet qeyde alindi'].'<br/>';
			}
			else{
				echo 'Database Error<br/>';
			}
		}
		
	}
}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
echo '</div>';
include 'inc/footer.php';
?>
