<?
session_start();
exit;

//if($_SERVER['REMOTE_ADDR'] != '85.132.19.140') exit;

function random_key($len){
	$key = substr(md5(time()), 0, $len);
    return $key;
}

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['qeydiyyat'];
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj, Paylaş, Qeydiyyat';
$meta_description = 'AloChat -da Pulsuz qeyd ol. Pulsuz mesajlaş ve yeni dostlar tap!';
include 'inc/header.php';

echo '<div class="mnav"><a href="index.php">AloChat</a> » '.$title.'</div>';
echo '<div class="layer">';

$_mod = $_GET['mod'];

$ip = getenv('REMOTE_ADDR');
$ua = mysql_escape_string(htmlspecialchars(getenv('HTTP_USER_AGENT')));

if($_mod == 'finish'){
	$_login = checkData($_POST['login']);
	$_password = checkData($_POST['password']);
	$_sex = intval($_POST['sex']);
	$_birth_day = checkData($_POST['birth_day']);
	$_birth_month = checkData($_POST['birth_month']);
	$_birth_year = intval($_POST['birth_year']);
	$_country = checkData($_POST['country']);
	$_mobile = intval($_POST['mobile']);
	$_pass = checkData($_SESSION['pass']);
	
	if($_country == '') $_country = 'az';
	
	if(empty($_login) || strlen($_login) > 18 || strlen($_login) < 4) $error = '- '.$__lng['loqin min max ola biler'];
	if(detectBadWord($_login)) $error = '- '.$__lng['loqinde qadagan olunmus soz'];
	if(strlen($_password) < 4) $error = '- '.$__lng['sifre simvoldan az olmamali'];
	if(preg_match("/[^0-9a-zA-Z_]+/", $_password)) $error .= '- '.$__lng['sifrede qadagan olunmus simvol'];
	if($_birth_year > date('Y') -8) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if($_birth_year < date('Y') -70) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if((intval($_birth_day) < 1 || intval($_birth_day) > 31) || strlen($_birth_day) != 2) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if((intval($_birth_month) < 1 || intval($_birth_month) > 12) || strlen($_birth_month) != 2) $error .= '- '.$__lng['tevellud duzgun daxil olunmayib'];
	if(preg_match("/[^A-Za-z0-9\@\*\(\)\!\-\~\_\.\[\]\=]+/",$_login)) $error .= $__lng['loqinde qadaga simvol'];

	/*
	if(strlen($_mobile) < 6) $error = $__lng['nomre sehv daxil olunub'];
	
	$checkMobile = mysql_query("SELECT `phone` FROM `chat_users` WHERE `phone` = '".$_mobile."';");
	if(mysql_num_rows($checkMobile) != 0){
		$error = $__lng['bir defe qeyd olmaq olar'];
	}
	*/
	
	$q = mysql_query("SELECT `id` FROM `chat_users` WHERE `nickname` = '".$_login."';");
	if(mysql_num_rows($q) != 0){
		$error .= $__lng['basqa loqin sec'];
	}
	
	if(!empty($error)) $form_step = 1;
	else{
		mysql_query("INSERT INTO `chat_users` SET `nickname` = '".$_login."', `password` = '".$_password."', `md5_pass` = '".md5($_password)."', `status` = '".$__lng['alochat istifade edirem']."', `sex` = ".$_sex.", `gun` = '".$_birth_day."', `ay` = '".$_birth_month."', `il` = '".$_birth_year."', `ip` = '".$ip."', `ua` = '".$ua."', `time` = ".time().", `reggun` = '".date('d')."', `regay` = '".date('m')."', `regil` = '".date('Y')."', `hhh` = '20', `iii` = '20', `friends` = '1', `phone` = '".$_mobile."', `regfrom` = 'web', `country` = '".$_country."';");
		if(mysql_affected_rows()>0){
			$form_step = 2;
			$_SESSION['id'] = mysql_insert_id();
			$_SESSION['password'] = md5(trim($_password));
			$_SESSION['login'] = $_login;
			mysql_query("UPDATE `sms_regconfirm` SET `reg` = '1' WHERE `phone` = '".$_mobile."'");
			
			$welcome_msg = $__lng['reg welcome message'];
			mysql_query("INSERT INTO `chat_messages` SET `from` = '1', `to` = '".$_SESSION['id']."', `from_nick` = 'AloChat', `message` = '".$welcome_msg."', `time` = '".time()."'");
		}
		else{
			$form_step = 1;
			$error = 'Error. [65444]';
		}
	}
}

if(!isset($form_step)) $form_step = 1;

switch($form_step){


case '1':
	
	if (!empty($_SERVER["HTTP_CLIENT_IP"])) $user_ip = $_SERVER["HTTP_CLIENT_IP"];
	elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) $user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	else $user_ip = $_SERVER["REMOTE_ADDR"];
	
	if(strlen($user_ip)>15){$explode_1 = explode(",",$user_ip);$user_ip = $explode_1[0];}
	$ip2long = sprintf("%u",ip2long($user_ip));

	$country_q = mysql_query("SELECT `country_code`,`country_name` FROM `ip_tables` WHERE ".$ip2long." BETWEEN `ip_num_start` AND `ip_num_end`");
	$country_a = mysql_fetch_array($country_q);
	$country_code = strtolower($country_a['country_code']);
	$country_name = $country_a['country_name'];
	
	if($country_code == 'az') exit;
	
	if(!empty($error)) echo '<span style="color: red;">'.$error.'</span><br/><br/>';
	echo '<form name="form" method="post" action="reg-global.php?mod=finish">';
	echo $__lng['login nik'].':<br/>';
	echo '<input type="text" name="login" /><br/><br/>';
	echo $__lng['sifre'].':<br/>';
	echo '<input type="text" name="password" /><br/><br/>';
	echo $__lng['cins'].': ';
	echo '<select name="sex" value="0">
	<option value="0">'.$__lng['kisi'].'</option>
	<option value="1">'.$__lng['qadin'].'</option>
	</select><br/><br/>';
	echo $__lng['tevellud'].':<br/>';     
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
	echo "<input type=\"text\" name=\"birth_year\" format=\"*N\" maxlength=\"4\" size=\"4\"/><br/><br/>\n";
	//echo $__lng['mobil nomreniz'].':<br/>';
	//echo '+ <input type="text" name="mobile" size="12" value="994" /><br/><br/>';
	
	echo $__lng['olke'].':<br/>';
	echo '<select name="country">';
	$countryListQuery = mysql_query("SELECT DISTINCT(`country_code`), `country_name` FROM `ip_tables` WHERE `country_code` != 'a1' ORDER BY `country_name`");
	while($countryListRow = mysql_fetch_array($countryListQuery)){
		$countryCodes = strtolower($countryListRow['country_code']);
		$countryName = $countryListRow['country_name'];
		echo '<option value="'.$countryCodes.'"'.($countryCodes == $country_code ? ' selected' : '').'>'.$countryName.'</option>';
	}
	echo '</select><br/><br/>';
  
	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/>';
	echo '</form>';
break;

case '2':
	if(!empty($error)) echo '<span style="color: red;">'.$error.'</span><br/><br/>';
	echo $__lng['qeydiyyatdan kecdiniz'].'.<br/><br/>';
	echo $__lng['uzv oldugunuza gore tesekkur'].'<br/><br/>';
	echo '<a href="main.php?ref='.rand(1111,9999).'">'.$__lng['daxil ol'].'</a><br/><br/>';
break;

}

echo '<br/></div>';
include 'inc/footer.php';

/*
if (!empty($_SERVER["HTTP_CLIENT_IP"])){
   //check for ip from share internet
   $user_ip = $_SERVER["HTTP_CLIENT_IP"];
  }
  elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
   // Check for the Proxy User
   $user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
  }
  else{
   $user_ip = $_SERVER["REMOTE_ADDR"];
  }
  if(strlen($user_ip)>15){$explode_1 = explode(",",$user_ip);$user_ip = $explode_1[0];}
  $ip2long = sprintf("%u",ip2long($user_ip));

  $country_q = mysql_query("SELECT `country_code`,`country_name` FROM `ip_tables` WHERE ".$ip2long." BETWEEN `ip_num_start` AND `ip_num_end`");
  $country_a = mysql_fetch_array($country_q);
  $country_code = strtolower($country_a['country_code']);
  $country_name = $country_a['country_name'];
*/
?>
