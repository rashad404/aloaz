<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$checkAuth = checkAuth('`id`, `nickname`, `coins`, `point`, `profile_photo`, `msg_count`, `country_id`,`user_status`,`unseen`,`invisible`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=pointserv">'.$__lng['giris'].'</a> | <a href="reg.php?loc=pointserv">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$point = $userrow['coins'];
$xal = $userrow['point'];
$photo = $userrow['profile_photo'];
$post = $userrow['msg_count'];
$user_status = $userrow['user_status'];
$user_unseen = $userrow["unseen"];
$user_invisible = $userrow["invisible"];
$country = 'az';//$userrow['country_id'];

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Statistika</div>';
echo '<div class="layer">';

$cnt_all_users = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user`"), 0);
$cnt_men = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `sex` = '0'"), 0);
$cnt_woman = $cnt_all_users - $cnt_men;

echo 'Aktiv istifadəçi sayı: '.$cnt_all_users.'<br/>';
echo 'Kişilər: '.$cnt_men.'<br/>';
echo 'Qadınlar: '.$cnt_woman.'<br/><br/>';

$cnt_team = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `user_status` > '0'");
$cnt_team = mysql_result($cnt_team, 0);

$cnt_new_users = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `created_at` > '".strtotime(date('Y-m-d 00:00:00'))."'");
$cnt_new_users = mysql_result($cnt_new_users, 0);

$cnt_birthday = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `birthday` LIKE '_____".date('m-d')."'");
$cnt_birthday = mysql_result($cnt_birthday, 0);

echo 'Vəzifəli istifadəçilər: <a href="team.php">'.$cnt_team.'</a><br/>';
echo 'Ad günü olanlar: <a href="birth.php">'.$cnt_birthday.'</a><br/>';
echo 'Bu gün qeyd olanlar: <a href="new-users.php">'.$cnt_new_users.'</a><br/><br/>';

echo '<a href="top-coins.php">En varlılar</a><br/>';
echo '<a href="room/viktorina-top.php">En ağıllılar</a><br/>';
echo '<a href="topposts.php">En çox mesaj yazanlar</a><br/>';

echo '</div>';
include 'inc/footer.php';
?>
