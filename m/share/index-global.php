<?
error_reporting(0);
session_start();

include '../inc/func.php';
include '../inc/functions.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

$checkAuth = checkAuth('`id`, `country`');
if($checkAuth == 'error'){
	echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['paylash'].'</div>';
	echo '<div class="layer">';
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=online">'.$__lng['giris'].'</a> | <a href="reg.php?loc=online">'.$__lng['qeyd ol'].'</a>', 2);
}

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['paylash'].'</div>';
echo '<div class="layer">';

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$country = strtolower($userrow['country']);

$globalOnlineQuery = mysql_query("SELECT `country`, COUNT(`country`) as `cnt_country` FROM `share_list` GROUP BY `country` LIMIT 100;");
while($globalOnlineRow = mysql_fetch_array($globalOnlineQuery)){
	$onlineCountry = $globalOnlineRow['country'];
	$cnt_country = $globalOnlineRow['cnt_country'];
	echo '<a href="index.php?country='.$onlineCountry.'">'.countryCodeName($onlineCountry).' ('.$cnt_country.')</a><br/>';
}

echo '</div><br/>';

echo '</div>';
include '../inc/footer.php';
?>
