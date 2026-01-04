<?php
ob_start();
session_start();

//if($_SERVER['REMOTE_ADDR'] != '85.132.19.140') exit;

function random_key($len){
	$key = substr(md5(time()), 0, $len);
    return $key;
}

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/csrf_func.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

if($_SESSION['auth'] == true){
	header('location: main.php');
	exit;
}

$title = $__lng['qeydiyyat'];
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj, Paylaş, Qeydiyyat';
$meta_description = 'AloChat -da Pulsuz qeyd ol. Pulsuz mesajlaş ve yeni dostlar tap!';
include 'inc/header.php';

echo '<div class="mnav"><a href="index.php">AloChat</a> » '.$title.'</div>';
echo '<div class="layer">';

$_mod = $_GET['mod'];

$ip = getenv('REMOTE_ADDR');
$ua = mysql_escape_string(htmlspecialchars(getenv('HTTP_USER_AGENT')));



if($_mod == 'login'){
	$_pass = checkData($_POST['pass']);
		
	if(empty($_POST['pass'])) $error = $__lng['kod daxil olunmayib']; 
	$_SESSION['pass'] = $_pass;
	
	if(!empty($error)) $form_step = 2;
	else{
		$form_step = 3;
		$checkMobileConf = mysql_query("SELECT `id` FROM `aloaz_db`.`sms_regconfirm` WHERE `phone` = '".$_mobile."' AND `pass` = '".$_pass."' AND `date` > '".(time() - 3600)."'");
		if(mysql_num_rows($checkMobileConf) == 0){
			$form_step = 2;
			$error = $__lng['kod sehv daxil olunub'];
		}
	}
}

if($_mod == 'finish' and $_POST and isTokenValid()){
	$_login = checkData($_POST['login']);
	$_password = checkData($_POST['password']);
	$_full_name = checkData($_POST['full_name']);
	$_sex = intval($_POST['sex']);
	$_birth_day = checkData($_POST['birth_day']);
	$_birth_month = checkData($_POST['birth_month']);
	$_birth_year = intval($_POST['birth_year']); 
	$_pass = checkData($_SESSION['pass']);
	
	if(empty($_login) || strlen($_login) > 18 || strlen($_login) < 4) $error = '- '.$__lng['loqin min max ola biler'];
	if(detectBadWord($_login)) $error = '- '.$__lng['loqinde qadagan olunmus soz'];
	if(strlen($_password) < 4) $error = '- '.$__lng['sifre simvoldan az olmamali'];
	if(preg_match("/[^0-9a-zA-Z_]+/", $_password)) $error .= '- '.$__lng['sifrede qadagan olunmus simvol'];
	if($_birth_year > date('Y') -8) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if($_birth_year < date('Y') -70) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if((intval($_birth_day) < 1 || intval($_birth_day) > 31) || strlen($_birth_day) != 2) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if((intval($_birth_month) < 1 || intval($_birth_month) > 12) || strlen($_birth_month) != 2) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if(preg_match("/[^A-Za-z0-9\@\*\(\)\!\-\~\_\.\[\]\=]+/",$_login)) $error .= $__lng['loqinde qadaga simvol'];
	
	$birthday = "$_birth_year-$_birth_month-$_birth_day";
	$age = floor((time() - strtotime($birthday)) / (24*3600*365));
		
	$q = mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `nickname` = '".$_login."';");
	if(mysql_num_rows($q) != 0){
		$error .= $__lng['basqa loqin sec'];
	}
	
	if(!empty($error)) $form_step = 3;
	else{ 
		mysql_query("INSERT INTO `aloaz_db`.`user` SET `nickname` = '".$_login."', `password` = '".$_password."',`full_name` = '".$_full_name."', `md5_pass` = '".md5($_password)."', `last_post` = 'AloChat istifade edirem', `sex` = ".$_sex.", `birthday` = '".$birthday."', `age` = '".$age."', `ip` = '".$ip."', `ua` = '".$ua."', `last_activity` = ".time().", `created_at` = ".time().", `only_friend` = '0', `regfrom` = 'mobile';");
		if(mysql_affected_rows()>0){
			$form_step = 4;
			$_SESSION['id'] = mysql_insert_id();
			$_SESSION['password'] = md5(trim($_password));
			$_SESSION['login'] = $_login;
			mysql_query("UPDATE `aloaz_db`.`sms_regconfirm` SET `reg` = '1' WHERE `phone` = '".$_mobile."'");
			
			$welcome_msg = $__lng['reg welcome message'];
			mysql_query("INSERT INTO `chat_messages` SET `from` = '1', `to` = '".$_SESSION['id']."', `from_nick` = 'AloChat', `message` = '".$welcome_msg."', `time` = '".time()."'");
		}
		else{
			$form_step = 3;
			$error = 'Error. [65444]';
    $error = 'DB INSERT ERROR: ' . mysql_error();

		}
	}
}

if(!isset($form_step)) $form_step = 3;

switch($form_step){

 
 
case '3': 
	
	if(!empty($error)) echo '<span style="color: red;">'.$error.'</span><br/><br/>';
	echo '<form name="form" method="post" action="register.php?mod=finish">';
	echo $__lng['login nik'].':<br/>';
	echo '<input type="text" name="login" /><br/><br/>';
	echo $__lng['sifre'].':<br/>';
	echo '<input type="text" name="password" /><br/><br/>';
	echo $__lng['ad'].':<br/>';
	echo '<input type="text" name="full_name" /><br/><br/>';
	echo $__lng['cins'].': ';
	echo '<select name="sex" value="0">
	<option value="0">'.$__lng['kisi'].'</option>
	<option value="1">'.$__lng['qadin'].'</option>
	</select><br/><br/>';
	echo '*'.$__lng['tevellud'].':<br/>';     
	echo '<select name="birth_day">';
	for($i=1; $i<=31; $i++){
		if($i < 10) $i = '0'.$i;
		echo '<option value="'.$i.'">'.$i.'</option>';
	}
	echo "</select>";
	echo "-";
	echo "<select name=\"birth_month\">\n";
	echo "<option value=\"01\">".$__lng['yanvar']."</option>\n";
	echo "<option value=\"02\">".$__lng['fevral']."</option>\n";
	echo "<option value=\"03\">".$__lng['mart']."</option>\n";
	echo "<option value=\"04\">".$__lng['aprel']."</option>\n";
	echo "<option value=\"05\">".$__lng['may']."</option>\n";
	echo "<option value=\"06\">".$__lng['iyun']."</option>\n";
	echo "<option value=\"07\">".$__lng['iyul']."</option>\n";
	echo "<option value=\"08\">".$__lng['avqust']."</option>\n";
	echo "<option value=\"09\">".$__lng['sentyabr']."</option>\n";
	echo "<option value=\"10\">".$__lng['oktyabr']."</option>\n";
	echo "<option value=\"11\">".$__lng['noyabr']."</option>\n";
	echo "<option value=\"12\">".$__lng['dekabr']."</option>\n";
	echo "</select>";
	echo "-";
	echo "<select name=\"birth_year\">\n";
	for($i=(date('Y')-12);$i>1950;$i--){
		echo "<option value=".$i.">".$i."</option>\n";
	} 
	echo "</select><br/><br/>";
	echo '<input type="hidden" name="csrf_token" value="'.makeToken().'">';
	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/><br/>';
	echo '<div class="terms-note">Təsdiqləməklə <a href="terms.php">İstifadə Şərtləri</a>ni qəbul etmiş olursunuz.</div>';
	echo '</form>';
break;

case '4':
	if(!empty($error)) echo '<span style="color: red;">'.$error.'</span><br/><br/>';
	echo $__lng['qeydiyyatdan kecdiniz'].'.<br/><br/>';
	echo $__lng['uzv oldugunuza gore tesekkur'].'<br/><br/>';
	echo '<a href="main.php?ref='.rand(1111,9999).'">'.$__lng['daxil ol'].'</a><br/><br/>';
break;

}

echo '<br/></div>';
include 'inc/footer.php';
?>
