<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/lang/pack.php';
include 'inc/config.php';

if(!empty($_SERVER["HTTP_CLIENT_IP"])) $user_ip = $_SERVER["HTTP_CLIENT_IP"];
elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) $user_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
else $user_ip = $_SERVER["REMOTE_ADDR"];
if(strlen($user_ip)>15){$explode_1 = explode(",",$user_ip);$user_ip = $explode_1[0];}
$ip2long = sprintf("%u",ip2long($user_ip));

$country_q = mysql_query("SELECT `country_code`,`country_name` FROM `ip_tables` WHERE ".$ip2long." BETWEEN `ip_num_start` AND `ip_num_end`");
$country_a = mysql_fetch_array($country_q);
$country_code = strtolower($country_a['country_code']);

if($_COOKIE['alochat_lng'] == ''){
	if($country_code == 'az') header('location: setlang.php?lang=az');
	else if($country_code == 'ru') header('location: setlang.php?lang=ru');
	else if($country_code == 'tr') header('location: setlang.php?lang=tr');
	else header('location: setlang.php?lang=en');
}

$checkAuth = checkAuth('`id`');
if($checkAuth != 'error'){
	header('location: http://'.$_SERVER['HTTP_HOST'].'/main.php');
	exit;
}

$refsite = mysql_escape_string($_GET['refsite']);

$title = 'AloChat - İlk Milli Mesajlaşma Ve Tanışlıq Platforması';
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj';
$meta_description = 'Sayt ve ya Android, Iphone mobil tetbiqlerimiz vasitesi ile daxil olaraq yeni dostlar qazan, pulsuz mesajlaş, paylaş ve tanış ol!';
include 'inc/header.php';

echo '<div class="mnav">AloChat - BETA</div>';

echo '<img src="img/logo.png" alt="logo" style="display: block; margin-top: -2px; margin-left: auto; margin-right: auto;"/><br/>';

echo '<div class="layer">';

echo $__lng['yenisen']." <a href=\"reg.php?refsite=".$refsite."\">".$__lng['pulsuz qeyd ol']."</a><br/><br/>";
//else echo $__lng['yenisen']." <a href=\"reg-global.php\">".$__lng['pulsuz qeyd ol']."</a><br/><br/>";
 
echo $__lng['pulsuz qosul'].'<br/>';
echo $__lng['yeni qeyd olanlara'].'<br/>';

echo '<br/>'.$__lng['onlayn'].': '.getOnline().' '.$__lng['nefer'].'<br/><br/>';

echo $__lng['loqin ve ya nomre'].':<br/>';
echo '<form method="post" action="main.php">';
echo '<input type="text" name="login" maxlength="30" /><br/>';
echo $__lng['sifre'].':<br/>';
echo '<input type="password" name="password" maxlength="25" /><br/>';
echo '<input type="submit" value="'.$__lng['daxil ol'].'" class="submitButton" /> ';
//if($country_code == 'az') echo '<a href="reg.php" class="authButton">'.$__lng['qeyd ol'].'</a> '; else echo '<a href="reg-global.php" class="authButton">'.$__lng['qeyd ol'].'</a> ';
echo '<a href="reg.php" class="authButton">'.$__lng['qeyd ol'].'</a> ';
echo '</form>';

//if($_COOKIE['alochat_lng'] == 'az'){
echo '<br/><a href="recover.php">'.$__lng['sifre berpasi'].'</a><br/><br/>';

echo '<b>'.$__lng['android tetbiqi yukle'].':</b><br/>'.$__lng['noqsanlarla bagli mail'].'<br/><br/>';
echo '<a href="https://play.google.com/store/apps/details?id=az.alo.chat"><img src="img/googleplay.gif" alt="Google Play" /></a><br/>';

//<a href="about.php">Haqqında</a> - 
//}
echo '<br/>';
echo '<a href="news.php">'.$__lng['yenilikler'].'</a> - <a href="share/">'.$__lng['paylash'].'</a> - <a href="terms.php">'.$__lng['istifade sertleri'].'</a> - <a href="contact.php">'.$__lng['elaqe'].'</a><br/><br/>';

if($_COOKIE['alochat_lng'] == 'az') echo '<a href="links.php">'.$__lng['fayldali linkler'].'</a><br/><br/>';

echo '</div>';

echo '<div style="text-align:center;">';

echo '<form action="setlang.php" method="get" >';
echo '<select name="lang" style="height: 23px;" onchange="javascript: submit()">
<option '.($_COOKIE['alochat_lng'] == 'az' ? 'selected="selected"' : '').' value="az">Azerbaycanca</option>
<option '.($_COOKIE['alochat_lng'] == 'en' ? 'selected="selected"' : '').' value="en">English</option>
<option '.($_COOKIE['alochat_lng'] == 'tr' ? 'selected="selected"' : '').' value="tr">Türkçe</option>
<option '.($_COOKIE['alochat_lng'] == 'ru' ? 'selected="selected"' : '').' value="ru">Русский</option>
</select>';
echo '</form><br/>';

//http://mobilink.az/js2.php?publisher_id=15949/

//if($_COOKIE['alochat_lng'] == 'az'){
	include 'inc/mobilinkad.php';
	echo '<a href="http://m.mobilink.az">M</a>obilink reklam yeri:<br/>';
	$mobilink_ads = mobilink_ad($mobilink_params);
	$mobilink_ads = str_replace('href=', ' target="_blank" href=', $mobilink_ads);
	echo $mobilink_ads.'<br/><br/>';
//}

?>
<div align="center"><script type="text/javascript" src="http://mobilink.az/pub/15949/"></script></div>
<script type="text/javascript">document.write('<scr'+'ipt type="text/javascript" src="//mobilink.az/pub/16146?t='+new Date().getTime()+'" charset="utf-8" ></scr'+'ipt>');</script>
<?

echo '</div>';
include 'inc/footer.php';
?>
