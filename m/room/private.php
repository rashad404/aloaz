<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `country_id`, `last_activity`, `place`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$country = 'az'; //$userrow['country'];

echo $__lng['otaq ac eylen'].'<br/><br/>';
$checkQuery = mysql_query("SELECT `view`, `view` FROM `aloaz_db`.`room` WHERE `uid` = '".$id."';");
if(mysql_num_rows($checkQuery) == 0) echo '<a class="button" href="create.php">'.$__lng['yeni otaq yarat'].'</a><br/><br/>';
else{
	echo '<a class="button" href="manage.php">'.$__lng['otaq idare paneli'].'</a><br/><br/>';
}

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`room` WHERE `country` = '".$country."' and id!=10");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);
/*
$vipQuery = mysql_query("SELECT `id`, `name`, `uid`, `login` FROM `aloaz_db`.`room` WHERE `country` = '".$country."' and `type`=1  ORDER BY `online` DESC, `refresh` DESC;");
while($row = mysql_fetch_assoc($vipQuery)){
	$room_id = $row['id'];
	$room_name = $row['name'];
	$room_login = $row['login'];
	$t   = time()-600;
	$usersQuery = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `place` = '".$room_id."' AND `last_activity` > '".$t."' ORDER BY `last_activity` DESC");
	$roomOnline = mysql_num_rows($usersQuery);

	mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$roomOnline."' WHERE `id` = '".$room_id."' LIMIT 1");

	echo '<div style="margin-bottom:8px;border: 1px solid red;display: inline-block;padding: 5px;"><a href="msgs.php?rid='.$room_id.'">'.$room_name.' ['.$roomOnline.']</a> # <span style="color: gray">'.$room_login.'</span><br/>';

	while($usersRow = mysql_fetch_array($usersQuery)){
		$usersLogin = $usersRow['nickname'];
		$roomUsers .= $usersLogin.', ';
	}
	$roomUsers = substr($roomUsers, 0, -2);
	echo $roomUsers;
	unset($roomUsers);
	echo '</div>';
}


*/

$query = mysql_query("SELECT `id`, `name`, `uid`, `login` FROM `aloaz_db`.`room` WHERE `country` = '".$country."' and `type`=0 and id!=10 ORDER BY `online` DESC, `refresh` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$room_id = $row['id'];
	$room_name = $row['name'];
	$room_login = $row['login'];
	$t   = time()-600;
	$usersQuery = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `place` = '".$room_id."' AND `last_activity` > '".$t."' ORDER BY `id` ASC");
	$roomOnline = mysql_num_rows($usersQuery);
	
	mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$roomOnline."' WHERE `id` = '".$room_id."' LIMIT 1");
	
	echo '<div style="margin-bottom:8px"><a href="msgs.php?rid='.$room_id.'">'.$room_name.' ['.$roomOnline.']</a> # <span style="color: gray">'.$room_login.'</span><br/>';
	
	while($usersRow = mysql_fetch_array($usersQuery)){
		$usersLogin = $usersRow['nickname'];
		$roomUsers .= $usersLogin.', ';
	}
	$roomUsers = substr($roomUsers, 0, -2);
	echo $roomUsers;
	unset($roomUsers);
	echo '</div>';
}

echo '<br/><div class="pageNav" style="text-align:left;">';
if($page > 1) echo '<a href ="private.php?page='.($page-1).'">&lt;</a> ';
if($page < $max) echo '<a href ="private.php?page='.($page+1).'">&gt;</a>';

if($page > 1 || $page < $max) echo '<br/>';

echo '</div>';

echo '</div>';
echo '<br/>';
if($_SERVER["REMOTE_ADDR"] == '37.32.67.22'){
	echo $userrow["last_activity"]." - ".date("d-m-Y H:i:s",$userrow["last_activity"])."  - ".$userrow["place"];
}
include '../inc/footer.php';
?>
