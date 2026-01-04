<?
include '../../inc/config.php';
include '../../inc/functions.php';
include '../../inc/func.php';

$query = mysql_query("SELECT COUNT(`uid`), `uid` FROM `chat_reports` WHERE `time` > '".(time()-3600*240)."' AND `ban_time` = 0 GROUP BY `uid` ORDER BY COUNT(`uid`) DESC");
while($row = mysql_fetch_array($query)){
	$uid = $row['uid'];
	$countUid = $row['COUNT(`uid`)'];
	if($countUid > 2){
		echo $uid.' ('.$countUid.') ';
		$userQuery = mysql_query("SELECT `nickname`, `phone` FROM `chat_users` WHERE `id` = '".$uid."'");
		$phone = mysql_result($userQuery, 0, 'phone');
		$login = mysql_result($userQuery, 0, 'nickname');
		
		echo $login.' '.$phone;
		echo '<br/>';
		
		/*
		if($phone != ''){
			mysql_query("INSERT INTO `chat_phone_ban` SET `phone` = '".$phone."', `reason` = 'Çoxsaylı istifadeçilerin etdiyi şikayete esasen avtomatik olaraq kenarlaşdırılmısınız.', `time` = '".time()."'");
			if(mysql_affected_rows()>0){
				mysql_query("UPDATE `chat_reports` SET `ban_time` = '".time()."' WHERE `uid` = '".$uid."'");
				
				// sending information message
				$reportersQuery = mysql_query("SELECT `reporter` FROM `chat_reports` WHERE `uid` > '".$uid."' AND `ban_time` = 0 ORDER BY `id`");
				while($reportersRow = mysql_fetch_array($reportersQuery)){
					$reporterId = $reportersRow['reporter'];
					
					$alochat_msg = 'Siz ve diger istifadeçilerimizin etdiyi şikayetlere esasen '.$login.' loqinli istifadeçinin girişine mehdudiyyet qoyuldu. Bizi melumatlandırdığınız üçün teşekkür edirik!';
					mysql_query("INSERT INTO `chat_messages` SET `from` = '1', `to` = '".$reporterId."', `from_nick` = 'AloChat', `message` = '".$alochat_msg."', `time` = '".time()."'");
				}
			}
		}
		*/
	}
}
?>