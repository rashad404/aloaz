<?
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['sifre berpasi'];
$meta_description = 'AloChat - '.$__lng['sifre berpasi'];
include 'inc/header.php';

echo '<div class="mnav"><a href="index.php">AloChat</a> » '.$title.'</div>';
echo '<div class="layer">';

$ip = getenv('REMOTE_ADDR');
$ua = mysql_escape_string(htmlspecialchars(getenv('HTTP_USER_AGENT')));

if(!isset($_POST['submit'])){
	echo $__lng['sifre sms ile gonderilecek'].'<br/><br/>';

	echo '<form name="form" method="post" action="recover.php">';
	echo $__lng['login ve ya nomre'].':<br/>';
	echo '<input type="text" name="login" /><br/><br/>';

	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/>';
	echo '</form>';
}
else{
	$_login = checkData($_POST['login']);
	if(preg_match("/[0-9]{12}/i", $_login)) $sql_login = " `phone` = '".$_login."' "; else $sql_login = " `nickname` = '".$_login."' ";
	
	$query = mysql_query("SELECT `password`, `phone` FROM `chat_users` WHERE ".$sql_login.";");
	
	if(mysql_num_rows($query) == 0){
		echo $__lng['melumatlarla istifadeci tapilmadi'].'.<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	}
	else{
		$user_row = mysql_fetch_array($query);
		$user_phone = $user_row['phone'];
		$user_password = $user_row['password'];
		
		if(strlen($user_phone) != 12){
			echo $__lng['nomre tesdiqlenmediyinden sifre'].'.<br/>';
			echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		}
		else{
			if($_POST['complate'] != 'ok'){
				$checkQuery = mysql_query("SELECT `id` FROM `chat_pincodes` WHERE `phone` = '".$user_phone."' AND `used` = '0' AND `time` > '".(time()-3600*12)."'");
				if(mysql_num_rows($checkQuery) == 0){
					$pincode = rand(1111,9999);
					mysql_query("INSERT INTO `chat_pincodes` SET `phone` = '".$user_phone."', `pin` = '".$pincode."', `ip` = '".$ip."', `ua` = '".$ua."', `time` = '".time()."'");
				
					//$sendSmsText = 'AloChat -daki sifreniz: '.$user_password.'';
					$sendSmsText = $__lng['sms sifre deyismek ucun kod'].': '.$pincode.'';
					if(sendSMS($user_phone, $sendSmsText)){
						echo $__lng['sifre smsle gonderildi'].'<br/><br/>';
					}
					else{
						echo 'SMS ERROR [5299]: Could not send sms.<br/><br/>';
					}
				}
				
				echo '<form name="form" method="post" action="recover.php?mod=complate">';
				echo $__lng['sms tesdiq kodunu daxil edin'].':<br/>';
				echo '<input type="text" name="pin_code" placeholder="'.$__lng['tesdiq kodu'].'" /><br/><br/>';
				echo $__lng['yeni sifre min'].':<br/>';
				echo '<input type="text" name="new_pass" placeholder="'.$__lng['sifre'].'" /><br/><br/>';

				echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" /><br/>';
				echo '<input type="hidden" name="complate" value="ok" />';
				echo '<input type="hidden" name="login" value="'.$_login.'" />';
				echo '</form>';
			}
			else{
				$_pincode = checkData(trim($_POST['pin_code']));
				$_pincode = SqlInjectFilter($_POST['pin_code']);
				
				$_newpass = checkData(trim($_POST['new_pass']));
				
				if(strlen($_newpass) < 6){
					echo $__lng['sifre simvoldan az olmamali'].'<br/>';
				}
				else{
					$checkPinQuery = mysql_query("SELECT `id` FROM `chat_pincodes` WHERE `phone` = '".$user_phone."' AND `pin` = '".$_pincode."' AND `used` = '0' AND `time` > '".(time()-3600*12)."'");
					if(mysql_num_rows($checkPinQuery) == 0){
						echo $__lng['tesdiq kodu yanlisdir'].'.<br/>';
					}
					else{
						mysql_query("UPDATE `chat_users` SET `password` = '".$_newpass."', `md5_pass` = '".md5($_newpass)."' WHERE `phone` = '".$user_phone."' LIMIT 1");
						if(mysql_affected_rows() > 0){
							echo $__lng['sifre deyisdirildi'].'<br/>';
						}
						else{
							echo 'Database error [5716]<br/>';
						}
						mysql_query("UPDATE `chat_pincodes` SET `used` = '1' WHERE `phone` = '".$user_phone."' AND `pin` = '".$_pincode."'");
					}
				}
			}
		}
	}
}

echo '<br/></div>';
include 'inc/footer.php';
?>
