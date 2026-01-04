<? 
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$_period = checkData($_GET['period']);

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Varlı istifadeçiler</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('id');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=topposts">'.$__lng['giris'].'</a> | <a href="reg.php?loc=rich-users">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

echo 'Varlı istifadeçiler TOP 20<br/>';

if($_period == 'month'){ 
	$where_period = " and `update_date`>'".date("Y-m-d H:i:s",strtotime(date("Y-m")."-01"))."'";
	echo '<a href="top-coins.php">'.$__lng['cemi'].'</a> / ';
	echo 'Bu ay<br/><br/>';
}
else{ 
	$where_period = '';
	echo $__lng['cemi'].' / ';
	echo '<a href="top-coins.php?period=month">Bu ay</a><br/><br/>';
}

echo 'Reytinqde yalnız Portmanat ve diger ödeme üsulları ile bal alan istifadeçiler iştirak edir. Bal xercledikde reytinq azalmır. İştirakçılar alınan ballara göre sıralanır.<br/><br/>';

echo '<table width="100%" cellpadding="2">';

$show_limit = 20;
$all_rows = 20;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit; 
$query = mysql_query("SELECT `user_id`,sum(`coins`) as s FROM `aloaz_db`.`transactions`  WHERE `payment_status`=1 ".$where_period." GROUP BY `user_id` ORDER BY s DESC LIMIT ".$show_limit.";");

$query = mysql_query("SELECT sum(`t`.`coins`) as s,t.id,u.nickname,`u`.`sex`,`u`.`profile_photo`,`u`.`last_post`,`u`.`coins`,`u`.`age` FROM `aloaz_db`.`transactions` as `t` RIGHT OUTER JOIN `aloaz_db`.`user` as `u` ON `t`.`user_id`=`u`.`id`
 WHERE `payment_status`=1 GROUP BY `t`.`user_id` ORDER BY s DESC LIMIT 20");
$num = $start;  
$i=1;	 	
while($transaction = mysql_fetch_array($query)){  
 	$user_id = $transaction["user_id"];  
	if($transaction["s"]>0){ 
		$num++;	
		$uid_id = $transaction["id"];
		$uid_sex = $transaction['sex'];
		$uid_login = $transaction['nickname']; 
		$uid_photo = $transaction['profile_photo'];
		$uid_status = htmlspecialchars($transaction['last_post']);
		$uid_coins = $transaction['coins'];
		$uid_age = $transaction["age"];
		if(strlen($uid_status) > 50) $uid_status = substr($uid_status, 0, 50).'...';

		if($uid_sex==0){
			$uid_sex_='K';
			$uid_sex_img ='man';
		}
		else{
			$uid_sex_='Q';
			$uid_sex_img='woman';
		}

		//$uid_age = floor( (strtotime(date('Y-m-d')) - strtotime("$uid_birth_year-$uid_birth_month-$uid_birth_day")) / 31556926);

		if($num == 1) $num_bgcolor = '#f93904'; 
		else if($num == 2) $num_bgcolor = '#ed552c'; 
		else if($num == 3) $num_bgcolor = '#e3856b'; 
		else  $num_bgcolor = '#818181';
		
		if(empty($uid_photo)) $img_file = 'img/'.$uid_sex_img.'.gif';
		else $img_file = 'http://alochat.com'.$uid_photo;
		
		echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td style="text-align: center; color: #fff; background: '.$num_bgcolor.';">'.$num.'</td><td><a href="profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a></td>
		<td width="100%" style="line-height: 17px"><a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a> <span style="font-size:11px">('; 
		echo $uid_sex_.'/'; 
		echo ''.$uid_age.')<br/>'.$uid_status.'</span><br/>';
		echo '<span style="font-size:11px;">Ballar:</span> <span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;">'.($transaction["s"]).'</span>';
		echo '</td></tr>';
} 
}
echo '</table>';


echo '</div>';
include 'inc/footer.php';
?>