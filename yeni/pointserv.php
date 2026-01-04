<?
error_reporting(0);
session_start();

$__posttopoint = 300;

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$checkAuth = checkAuth('`id`, `nickname`, `hhh`, `point`, `photo`, `post`, `country`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=pointserv">'.$__lng['giris'].'</a> | <a href="reg.php?loc=pointserv">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$point = $userrow['hhh'];
$xal = $userrow['point'];
$photo = $userrow['photo'];
$post = $userrow['post'];
$country = $userrow['country'];

$expPhoto = explode('|', $photo);
$photoId = $expPhoto[1];

$mod = $_GET['mod'];

switch($mod){

default:

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal xidmetleri'].'</div>';
echo '<div class="layer">';

echo $__lng['hesabinizdaki ballar'].': '.$point.'<br/>';
echo '<a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].'</a><br/><br/>';

echo '- <a href="pointserv.php?mod=onlinerating">'.$__lng['irelide gorun'].'</a><br/>';
echo '- <a href="pointserv.php?mod=ozunutanit">'.$__lng['ozunu tanit'].'</a><br/>';
echo '- <a href="pointserv.php?mod=changelogin">'.$__lng['loqini deyis'].'</a><br/>';
echo '- <a href="pointserv.php?mod=send_point">'.$__lng['bal gonder'].'</a><br/>';
echo '- <a href="pointserv.php?mod=posttopoint">'.$__lng['postla bal al'].'</a><br/>';
echo '- <a href="pointserv.php?mod=dellogin">'.$__lng['loqin sil hesab bagla'].'</a><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;


case 'buy';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';

/*
echo $__lng['sms ile bal al'].':<br/><br/>';
echo '10 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9136&amp;id='.$id.'&amp;bal=20">20 '.$__lng['bal al'].'</a><br/>';
echo '25 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9142&amp;id='.$id.'&amp;bal=50">50 '.$__lng['bal al'].'</a><br/>';
echo '60 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9143&amp;id='.$id.'&amp;bal=120">120 '.$__lng['bal al'].'</a><br/>';
echo '150 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9148&amp;id='.$id.'&amp;bal=300">300 '.$__lng['bal al'].'</a><br/><br/>';
*/

echo 'Portmanat ile bal al (Texniki problem var):<br/>';
echo '<form action="pointserv.php" method="POST">Kod: <input type="text" name="code" /><br/><input type="submit" name="code" value="Tesdiqle" /></form><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;


case 'onlinerating';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['irelide gorun'].'</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){

echo $__lng['xal sayi cox olarsa'].'<br/>';

$asa = mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `point` > ".$xal." AND `time`>".time().";");
$count_users = mysql_result($asa, 0);
$place=$count_users+1;
$all_user = mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `time`>".time().";");
$count_all_user = mysql_result($all_user, 0);

if($xal > 0) echo "".$__lng['xallarinizin sayi'].": <b>$xal</b>. ".$__lng['loqininizin onlaynda yeri'].": <b>$place</b><br/>";
else  echo "".$__lng['xallarinizin sayi'].": <b>$xal</b>. ".$__lng['loqininizin onlaynda yeri'].": <b>$place</b> - <b>$count_all_user</b><br/>";

if($place==1){
	echo $__lng['sizin loqin liderdir'].'<br/>';
}
else{
	echo $__lng['xallarin sayini artirmaqla'].'<br/>';
}

echo '<br/><form name="form" method="post" action="pointserv.php?mod=onlinerating">';
echo '<select name="xal" value="1">
<option value="1">1 '.$__lng['xal'].' (1 '.$__lng['bal'].')</option>
<option value="5">5 '.$__lng['xal'].' (5 '.$__lng['bal'].')</option>
<option value="10">10 '.$__lng['xal'].' (10 '.$__lng['bal'].')</option>
<option value="50">50 '.$__lng['xal'].' (50 '.$__lng['bal'].')</option>
<option value="100">100 '.$__lng['xal'].' (100 '.$__lng['bal'].')</option>
<option value="500">500 '.$__lng['xal'].' (500 '.$__lng['bal'].')</option>
<option value="1000">1000 '.$__lng['xal'].' (1000 '.$__lng['bal'].')</option>
</select><br/>';

echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" />';
echo '<input type="hidden" name="action" value="add" />';
echo '</form><br/>';

echo '<b>'.$__lng['qeyd'].':</b> '.$__lng['plus isaresi xallarin sayi'].'<br/>';
}
else{
$_xal = intval($_POST['xal']);

if($_xal<1){
	echo $__lng['minium 1 xal'].'.<br/>';
	break;
}

if($point < $_xal){
	echo $__lng['hesabda bal yoxdur'].'.<br/>';
	break;
}

$update = mysql_query("UPDATE `admin_alochat`.`user` SET `coins` = `coins`-'".$_xal."', `point` = `point`+".$_xal." WHERE `id` = '".$id."';");
//$update = mysql_query("UPDATE `chat_users` SET `hhh` = `hhh`-'".$_xal."', `point` = `point`+".$_xal." WHERE `id` = '".$id."';");

if($update) echo $__lng['xaliniz artirildi'].'!<br/>'; else echo 'Database Error [1158]<br/>';

$q = mysql_query("SELECT `point` FROM `chat_users` WHERE `id` = '".$id."';");

if(mysql_num_rows($q) != 0){
	$user = mysql_fetch_array($q);
	$xal = $user['point'];
}

$asa = mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `point` > ".$xal.";");
$count_users = mysql_result($asa, 0);
$place=$count_users+1;

echo $__lng['xallarinizin sayi'].' <b>'.$xal.'</b>. '.$__lng['loqininizin onlaynda yeri'].': <b>'.$place.'</b><br/>';

}

break;


case 'ozunutanit';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['ozunu tanit'].'</div>';
echo '<div class="layer">';

if($_POST['submit'] == ''){
	echo $__lng['ozunu tanitdan istifade et'].'<br/>';
	echo $__lng['xidmetden son istifade edenin'].'<br/><br/>';
	echo $__lng['xidmetin deyeri'].': 10 '.$__lng['bal'].'<br/>';
	if($point < 10) echo $__lng['hesabda bal yoxdur'].'. <a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].'</a><br/>';
	echo '<br/>';
	echo $__lng['emeliyyati tesdiqleyin'].':<br/><br/>';

	echo '<form name="form" method="post" action="pointserv.php?mod=ozunutanit">';
	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/>';
	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{
	if($point < 10){
		echo $__lng['hesabda bal yoxdur'];
		break;
	}

	$update = mysql_query("UPDATE `admin_alochat`.`user` SET `coins` = `coins`-10 WHERE `id` = '".$id."' LIMIT 1;");
	//$update = mysql_query("UPDATE `chat_users` SET `hhh` = `hhh`-10 WHERE `id` = '".$id."' LIMIT 1;");

	if($update){
		mysql_query("INSERT INTO `chat_ozunutanit` SET `uid` = '".$id."' , `login` = '".$login."', `sex` = '".$sex."', `status` = '".$status."', `photo_id` = '".$photoId."', `country` = '".$country."', `time` = '".time()."';");
		if(mysql_affected_rows()>0) echo $__lng['tebrik ozunu tanitdasiniz'].'<br/>'; else echo 'Databse error [1126]<br/>';
	}
	else{
		echo 'Databse error [1127]<br/>';
	}
}

break;


case 'changelogin';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['loqin deyismek'].'</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){
echo $__lng['xidmetin deyeri'].': 20 '.$__lng['bal'].'<br/><br/>';

echo '<form name="form" method="post" action="pointserv.php?mod=changelogin">';

echo $__lng['yeni loqin'].': (min:3)<br/>';
echo "<input name=\"new_login\" value=\"$login\" maxlength=\"20\"/><br/>";

echo $__lng['parolunuz'].":<br/>
<input type=\"text\" name=\"security_pass\" value=\"\" maxlength=\"20\"/><br/>";

echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" />';
echo '<input type="hidden" name="action" value="add" />';
echo '</form><br/>';

echo $__lng['loqin qaydalara uygun olsun'].'<br/><br/>';
}
else{
$security_pass = mysql_escape_string($_POST['security_pass']);

$new_login = htmlspecialchars(mysql_escape_string(trim($_POST['new_login'])));
$new_login = str_replace('$', '$$', $new_login);
$error = "";

$security_pass_q = mysql_query("SELECT * FROM `chat_users` WHERE `id` = '".$id."' AND `password` = '".$security_pass."';");
if(mysql_affected_rows() == 0){$error .= $__lng['parolu duz yaz'].".<br/>\n";}

if(preg_match("/[^A-Za-z0-9\@\*\(\)\!\-\~\_\[\]\=]+/",$new_login)) $error .= $__lng['loqinde qadaga simvol']."<br/>\n";
if(strlen($new_login) > 20) $error .= $__lng['loqin min max ola biler'].'<br/>';
if(detectBadWord($new_login)) $error = $__lng['loqinde qadagan olunmus soz'].'<br/>';
if(strlen($new_login) < 3) $error .= $__lng['loqin min max ola biler'].'<br/>';

if(empty($new_login)) $error .= $__lng['loqin yazilmayib'].'<br/>';

$q = mysql_query("SELECT `level` FROM `chat_users` WHERE `nickname` = '".$new_login."' AND `id` != '".$id."';");
if(mysql_num_rows($q) != 0) $error .= $__lng['basqa loqin sec'].'<br/>';

if($point < 20) $error .= $__lng['loqin deyismek ucun bal yoxdur'].'<br/>';

if(!empty($error)){
	echo $error;
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

$update = mysql_query("UPDATE `admin_alochat`.`user` SET `nickname` = '".$new_login."' ,`coins` = `coins`-20 WHERE `id` = '".$id."' LIMIT 1;");
//$update = mysql_query("UPDATE `chat_users` SET `nickname` = '".$new_login."' ,`hhh` = `hhh`-20 WHERE `id` = '".$id."' LIMIT 1;");

if($update){
	$_SESSION['login'] = $new_login;
	echo $__lng['loqin deyisdirildi'].'<br/>';
	mysql_query("UPDATE `room` SET `login` = '".$new_login."' WHERE `uid` = '".$id."' LIMIT 1;");
 }
else{
	echo "Error<br/>\n";
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}

}

break;



case 'send_point';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal gonder'].'</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){
	echo $__lng['hesabdaki ballar'].': '.$point.'<br/><br/>';

	echo '<form name="form" method="post" action="pointserv.php?mod=send_point">';

	echo $__lng['login'].":<br/>
	<input name=\"send_login\" maxlength=\"20\"/><br/>";

	echo $__lng['bal'].":<br/>
	<input type=\"text\" name=\"send_bal\" value=\"\" maxlength=\"20\"/><br/>";

	echo '<input type="submit" name="submit" value="'.$__lng['gonder'].'" />';
	echo '<input type="hidden" name="action" value="add" />';
	echo '</form><br/>';
	echo $__lng['bal komissiyasi tutulur'];
}
else{
	$send_login = trim(mysql_escape_string($_POST['send_login']));
	$send_bal_100 = intval($_POST['send_bal']);
	$send_bal = floor($send_bal_100*0.8);

	if(empty($send_login)){
		$error = $__lng['loqin qeyd olunmayib bal'].'.<br/>';
	}
	if(strtolower($login) == strtolower($send_login)){
		// $error = 'Özünüze bal göndere bilmezsiniz.<br/>';
	}
	if($send_bal_100<10){
		$error = $__lng['minium bal gondermek'].'.<br/>';
	}
	if($send_bal_100>10000){
		$error = $__lng['maksimum bal gondermek'].'.<br/>';
	}
	if($send_bal>$point){
		$error = $__lng['hesabda bal yoxdur'].'.<br/>';
	}
	
	$send_to_q = mysql_query("SELECT `id` FROM `chat_users` WHERE `nickname`='".$send_login."'");
	if(mysql_num_rows($send_to_q)==0){
		$error = $__lng['bal alacaq loqin tapilmadi'].'.<br/>';
	}else{
		$send_to_a = mysql_fetch_array($send_to_q);
		$send_to_id = $send_to_a['id'];
	}
	if(!empty($error)){
		echo '<span style="color:red;">'.$__lng['sehv'].':</span> '.$error;
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	$update1 = mysql_query("UPDATE `admin_alochat`.`user` SET `coins`=`coins`-".$send_bal_100." WHERE `id`='".$id."'");
	$update2 = mysql_query("UPDATE `admin_alochat`.`user` SET `coins`=`coins`+".$send_bal." WHERE `id`='".$send_to_id."'");
	
	//$update1 = mysql_query("UPDATE `chat_users` SET `hhh`=`hhh`-".$send_bal_100." WHERE `id`='".$id."'");
	//$update2 = mysql_query("UPDATE `chat_users` SET `hhh`=`hhh`+".$send_bal." WHERE `id`='".$send_to_id."'");
	echo 'Hesabınızdan '.$send_bal_100.' bal çıxıldı ve '.$send_login.' loginine '.$send_bal.' bal gönderildi.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
}

break;


case 'posttopoint';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['postla bal al'].'</div>';
echo '<div class="layer">';

if($_POST['submit'] == ''){
	echo $__lng['x posta x bal ala biler'].'<br/><br/>';
	
	echo $__lng['postlarinizin sayi'].': '.$post.'<br/><br/>';
	if($post < $__posttopoint) echo $__lng['postlar bal ucun kifayet etmir'].'<br/><br/>';
	
	echo '<form name="form" method="post" action="pointserv.php?mod=posttopoint">';
	//echo 'Postlar: <br/><input type="text" format="*N" size="7" name="posts" /><br/><br/>';
	echo $__lng['postlar'].': <br/><select name="posts" value="1">
	<option value="300">300 '.$__lng['post'].' (1 '.$__lng['bal'].')</option>
	<option value="600">600 '.$__lng['post'].' (2 '.$__lng['bal'].')</option>
	<option value="900">900 '.$__lng['post'].' (3 '.$__lng['bal'].')</option>
	<option value="1200">1200 '.$__lng['post'].' (4 '.$__lng['bal'].')</option>
	<option value="1500">1500 '.$__lng['post'].' (5 '.$__lng['bal'].')</option>
	<option value="1800">1800 '.$__lng['post'].' (6 '.$__lng['bal'].')</option>
	<option value="2100">2100 '.$__lng['post'].' (7 '.$__lng['bal'].')</option>
	<option value="2400">2400 '.$__lng['post'].' (8 '.$__lng['bal'].')</option>
	<option value="2700">2700 '.$__lng['post'].' (9 '.$__lng['bal'].')</option>
	<option value="3000">3000 '.$__lng['post'].' (10 '.$__lng['bal'].')</option>
	<option value="6000">6000 '.$__lng['post'].' (20 '.$__lng['bal'].')</option>
	</select><br/>';
	echo '<input type="submit" name="submit" value="'.$__lng['bal al'].'" /><br/>';
	echo '</form>';
}
else{
	$_posts = intval($_POST['posts']);
	
	if($_posts < 1){
		echo $__lng['postla bal ucun minimum'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($post < 1){
		echo $__lng['postla bal ucun minimum'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($_posts > 6000){
		echo 'ERROR<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($post < $__posttopoint){
		echo $__lng['postla bal ucun minimum'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($post < $_posts){
		echo $__lng['kifayet qeder post yoxdur'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	$checkQuery = mysql_query("SELECT `id` FROM `logs_buypoint` WHERE `time` > '".(time()-300)."' AND `uid` = '".$id."'");
	if(mysql_num_rows($checkQuery) > 0){
		echo $__lng['intervalla servisden istifade olar'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	$addPoint = round($_posts/$__posttopoint);

 	$update = mysql_query("UPDATE `admin_alochat`.`user` SET `coins` = `coins`+".$addPoint.", `all_coins` = `all_coins`+".$addPoint.", `msg_count` = `msg_count`-".$_posts." WHERE `id` = '".$id."' LIMIT 1;");
 	//$update = mysql_query("UPDATE `chat_users` SET `hhh` = `hhh`+".$addPoint.", `iii` = `iii`+".$addPoint.", `post` = `post`-".$_posts." WHERE `id` = '".$id."' LIMIT 1;");

	if(mysql_affected_rows()>0){
		echo '<b>'.$addPoint.'</b> '.$__lng['x bal hesaba yuklenildi'].'<br/>';
		
		mysql_query("INSERT INTO `logs_buypoint` SET `uid` = '".$id."', `amount` = '".$addPoint."', `from` = 'posttopoint', `time` = '".time()."', `date` = NOW();");
	}
	else{
		echo 'Databse error [7822]<br/>';
	}
	echo '<br/><a href="pointserv.php?mod=posttopoint">« '.$__lng['geri'].'</a><br/>';
}

break;


case 'dellogin';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['loqin sil hesab bagla'].'</div>';
echo '<div class="layer">';

if($_POST['del'] != 'ok'){
	if(intval($_GET['step'])==0){
		echo $__lng['loqin silseniz melumat itecek'].'<br/><br/>
		'.$__lng['loqini silmeye eminsiniz'].'<br/><br/>
		<a href="pointserv.php">'.$__lng['xeyr'].'</a> | <a href="pointserv.php?mod=dellogin&amp;step=1">'.$__lng['beli'].'</a><br/><br/>
		'.$__lng['loqin silinmesi ucun bal olmali'].'<br/>';
	}

	if(intval($_GET['step'])==1){
		echo '<span style="color: red"><b>'.$__lng['diqqet'].'!</b><br/>'.$__lng['sonuncu xeberdarliq'].'!</span><br/><br/>'.$__lng['loqin birdefelik silinesinmi'].'<br/><br/>';
		
		echo '<form name="form" method="post" action="pointserv.php?mod=dellogin">';
		echo '<input type="submit" name="submit" value="'.$__lng['beli'].'" />';
		echo '<input type="hidden" name="del" value="ok" />';
		echo '</form><br/>';
		
		echo '<a href="pointserv.php">'.$__lng['xeyr'].'</a><br/>';
	}
}
else{
	if($point < 50){
		echo $__lng['hesabda bal yoxdur'].'.<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}

	$delete = mysql_query("DELETE FROM `chat_users` WHERE `id` = '".$id."' LIMIT 1;");

	if($delete){
		mysql_query("DELETE FROM `chat_friends` WHERE `id` = '".$id."' OR `uid` = '".$id."';");
		mysql_query("DELETE FROM `chat_photos` WHERE `uid` = '".$id."';");
		echo $__lng['silindi'].'!<br/><br/>';
	}
	else{
		echo 'Database error [6698]<br/>';
	}
}

break;


}
if(!empty($mod)){
	echo '<br/><a href="pointserv.php">'.$__lng['bal xidmetleri'].'</a><br/>';
}
echo '</div>';
include 'inc/footer.php';
?>
