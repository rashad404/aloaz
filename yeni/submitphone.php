<?
session_start();

function random_key($len){
	$key = substr(md5(time()), 0, $len);
    return $key;
}

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'Nömre tesdiqi';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">AloChat</a> » '.$title.'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError('Xahiş edirik istifadeçi adı ve şifrenizle sayta daxil olun.<br/><br/>'.
	'<a href="index.php">Giriş</a> | <a href="reg.php">Qeydiyyat</a>', 2);

}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$_mod = $_GET['mod'];

$ip = getenv('REMOTE_ADDR');
$ua = mysql_escape_string(htmlspecialchars(getenv('HTTP_USER_AGENT')));

if($_mod == 'pass'){
	$_mobile = intval($_POST['mobile']);

	if(!empty($_POST['mobile']) && strlen($_mobile) != 12) $error = 'Nömrenizi düzgün daxil etmemisiniz.';
	if(empty($_POST['mobile'])) $error = 'Nömreni daxil etmemisiniz.';
	if(!isset($_COOKIE['PHPSESSID'])) $error = "Qeydiyyatdan keçmek, sayta daxil olmaq üçün COOKIE (çerezler) aktiv olmalıdır.";
	
	$checkIpConfirm = mysql_query("SELECT `id` FROM `sms_regconfirm` WHERE `ip` = '".$ip."' AND `reg` = '0' AND `date` > '".(time() - 1800)."'");
	if(mysql_num_rows($checkIpConfirm) > 5) $error = "Bir neçe deqiqe sonra yeniden yoxlayın.";
	
	if(empty($error)){
		$q = mysql_query("SELECT `phone` FROM `chat_users` WHERE `phone` = '".$_mobile."';");
		if(mysql_num_rows($q) != 0){
			$error = "Bu nömre ile qeydiyyatlı nick var. Bir nömre ile yalnız bir defe qeyd olmaq mümkündür.";
		}
	}
	
	if(!empty($error))$form_step = 1;
	else{
		$form_step = 2;
		//$randkey = random_key(4);
		$randkey = rand(1111,9999);
		$_SESSION['mobile'] = $_mobile;
		
		$checkMobileConf = mysql_query("SELECT `id` FROM `sms_regconfirm` WHERE `phone` = '".$_mobile."' AND `date` > '".(time() - 3600)."'");
		if(mysql_num_rows($checkMobileConf) == 0){
			$sendSmsText = 'AloChat aktivlesdirilme ucun tesdiq kodu: '.$randkey.'';

			if(sendSMS($_mobile, $sendSmsText)){
				mysql_query("INSERT INTO `sms_regconfirm` SET `phone` = '".$_mobile."', `pass` = '".$randkey."', `ip` = '".$ip."', `ua` = '".$ua."', `date` = '".time()."'");
			}
			else{
				$error = 'Sending sms error';
			}
		}
	}
}

if($_mod == 'login'){
	$_pass = checkData($_POST['pass']);
		
	if(empty($_POST['pass'])) $error = 'Kodu daxil etmemisiniz.';
	$_mobile = intval($_SESSION['mobile']);
	$_SESSION['pass'] = $_pass;
	
	if(!empty($error)) $form_step = 2;
	else{
		$form_step = 3;
		$checkMobileConf = mysql_query("SELECT `id` FROM `sms_regconfirm` WHERE `phone` = '".$_mobile."' AND `pass` = '".$_pass."' AND `date` > '".(time() - 3600)."'");
		if(mysql_num_rows($checkMobileConf) == 0){
			$form_step = 2;
			$error = 'Kod düzgün daxil olunmayıb.';
		}
		else{
			$checkMobile = mysql_query("SELECT `phone` FROM `chat_users` WHERE `phone` = '".$_mobile."';");
			if(mysql_num_rows($checkMobile) != 0){
				$error = "Bu nömre ile qeydiyyatlı nick var. Bir nömre ile yalnız bir loqin aktivleşdirmek olar.";
			}
			mysql_query("UPDATE `chat_users` SET `phone` = '".$_mobile."' WHERE `id` = '".$id."' LIMIT 1;");
		
			mysql_query("UPDATE `sms_regconfirm` SET `reg` = '1' WHERE `phone` = '".$_mobile."' LIMIT 1");
		}
	}
}

if(!isset($form_step)) $form_step = 1;

switch($form_step){

case '1':
	echo 'Nömrenizi daxil edin. Tesdiq kodu sms-le nömrenize gönderilecek.<br/>';
	echo '<i>Nömre tesdiqlenmesi <b>pulsuz</b>dur.</i> Eyni nömre ile yalnız 1 defe qeyd olmaq olur.<br/><br/>';
	if(!empty($error)) echo '<span style="color: red;">'.$error.'</span><br/><br/>';
	echo '<form name="form" method="post" action="submitphone.php?mod=pass">';
	echo 'Mobil nömreniz:<br/>';
	echo '+ <input type="text" name="mobile" size="12" value="994" /><br/>';
	echo '(Nümune: 994501234567)<br/>';
	echo '<input type="submit" name="submit" value="Davam et" /><br/>';
	echo '</form><br/>';
	
	echo '<i>Nömrenizin anonimliyi tam qorunacaq. Nömrenizden yalnız parol berpasında istifade oluna biler.</i><br/>';
	echo '<br/><a href="javascript:history.back(1)">« Geri</a>';
break;

case '2':
	if(!empty($error)) echo '<span style="color: red;">'.$error.'</span><br/><br/>';
	echo '<form name="form" method="post" action="submitphone.php?mod=login">';
	echo 'Nömrenize smsle gönderilen tesdiq kodunu daxil edin:<br/>';
	echo '<input type="text" name="pass" size="10" /><br/>';
	echo '<input type="submit" name="submit" value="Tesdiqle" /><br/>';
	echo '</form>';
	echo '<br/><a href="javascript:history.back(1)">« Geri</a>';
break;

case '3':
	echo 'Nömre müveffeqiyyetle tesdiqlendi.<br/>';
break;

}

echo '</div>';
include 'inc/footer.php';
?>
