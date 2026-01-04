<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a> » <a href="manage.php">'.$__lng['otaq idare paneli'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$roomQuery = mysql_query("SELECT `id`, `name` FROM `aloaz_db`.`room` WHERE `uid` = '".$id."';");
if(mysql_num_rows($roomQuery) == 0){
	echo $__lng['otaq tapilmadi'].'<br/>';
	echo '</div><br/>';
	include '../inc/footer.php';
	exit;
}
else{
	$roomRow = mysql_fetch_array($roomQuery);
	$rid = $roomRow['id'];
	$roomName = $roomRow['name'];
}

switch($_GET['mod']){

case 'subs';

$_status = intval($_GET['status']);
if($_status != 1 && $_status != 2 && $_status != 0) $_status = 0;

if($_status == 0) echo $__lng['otaga uzv olmaq isteyenler'].':<br/><br/>';
else if($_status == 1) echo $__lng['otaga uzvluyu tesdiqlenenler'].':<br/><br/>';
else echo $__lng['otaga uzvluyu legvolunanlar'].':<br/><br/>';

$_uid = intval($_GET['uid']);
$_submit = intval($_GET['submit']);

if($_submit > 2) $_submit = 2;

if($_submit > 0 && $_uid > 0){
	$update = mysql_query("UPDATE `aloaz_db`.`room_subs` SET `status` = '".$_submit."' WHERE `rid` = '".$rid."' AND `uid` = '".$_uid."'");
	if(!$update) echo 'DB error<br/>';
}

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`room_subs` WHERE `status` = '".$_status."' AND `rid` = '".$rid."'");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);

$query = mysql_query("SELECT `uid`, `time` FROM `aloaz_db`.`room_subs` WHERE `status` = '".$_status."' AND `rid` = '".$rid."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");

if(mysql_num_rows($query) == 0){
	echo '<i>'.$__lng['istifadeci tapilmadi'].'</i><br/>';
}
else{
	while($row = mysql_fetch_array($query)){
		$subs_uid = $row['uid'];
		$subs_time = $row['time'];
		
		$subsQuery = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = '".$subs_uid."'");
		$subsRow = mysql_fetch_array($subsQuery);
		
		$subsLogin = $subsRow['nickname'];
		
		echo '<a href="../profile.php?uid='.$subs_uid.'">'.$subsLogin.'</a>';
		if($_status == 0)  echo ' - <a href="manage.php?mod=subs&amp;status='.$_status.'&amp;uid='.$subs_uid.'&amp;submit=1">'.$__lng['tesdiqle'].'</a> / <a href="manage.php?mod=subs&amp;status='.$_status.'&amp;uid='.$subs_uid.'&amp;submit=2">'.$__lng['imtina'].'</a>';
		if($_status == 1)  echo ' - <a href="manage.php?mod=subs&amp;status='.$_status.'&amp;uid='.$subs_uid.'&amp;submit=2">'.$__lng['deaktiv et'].'</a>';
		if($_status > 1)  echo ' - <a href="manage.php?mod=subs&amp;status='.$_status.'&amp;uid='.$subs_uid.'&amp;submit=1"> '.$__lng['aktivlesdir'].'</a>';
		echo '<br/>';
		echo date('Y-m-d H:i', $subs_time).'<br/><br/>';
	}

	if($page > 1) echo '<a id="pageButon" href ="manage.php?mod=subs&amp;status='.$_status.'&amp;page='.($page-1).'">&lt;</a> ';
	if($page < $max) echo '<a id="pageButon" href ="manage.php?mod=subs&amp;status='.$_status.'&amp;page='.($page+1).'">&gt;</a>';

	if($page > 1 || $page < $max) echo '<br/>';
}
break;


case 'edit';

$roomQuery = mysql_query("SELECT `id`, `name`, `view` FROM `aloaz_db`.`room` WHERE `uid` = '".$id."'");
if(mysql_num_rows($roomQuery) == 0){
	echo $__lng['otaq tapilmadi'].'.<br/>';
	echo '</div>';
	include '../inc/footer.php';
	exit;
}
$roomRow = mysql_fetch_array($roomQuery);
$roomId = $roomRow['id'];
$roomName = $roomRow['name'];
$roomView = $roomRow['view'];

if(isset($_POST['submit']) ){
	$_name = checkData($_POST['name']);
	$_view = intval($_POST['view']);
	if(strlen($_name) < 3) $error .= '- '.$__lng['min otaq adi sehvi'].'<br/>';
	if(strlen($_name) > 35) $error .= '- '.$__lng['max otaq adi sehvi'].'<br/>';
	
	$checkNameQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room` WHERE `name` = '".$_name."' AND `id` != '".$roomId."' LIMIT 1");
	if(mysql_num_rows($checkNameQuery) > 0) $error .= '- '.$__lng['otaq var basqasin sec'].'<br/>';
	
	if(!empty($error)){
		echo '<span style="color: red">'.$error.'</span><br/>';
	}
	else{
		$update = mysql_query("UPDATE `aloaz_db`.`room` SET `name` = '".$_name."', `view` = '".$_view."' WHERE `id` = '".$roomId."' LIMIT 1");
		if($update){
			echo $__lng['deyisdirildi'].'.<br/>';
		}
		else{
			echo 'DB error.<br/>';
		}
		echo '</div>';
		include '../inc/footer.php';
		exit;
	}
}

echo '<form method="post" action="manage.php?mod=edit">';
echo $__lng['otaq adi'].':<br/>';
echo '<input type="text" name="name" value="'.$roomName.'"/><br/><br/>';

echo $__lng['yazilari oxumaq icazesi'].':<br/>';
echo '<select name="view">';
echo '<option value="0"'.($roomView == '0' ? ' selected' : '').'>'.$__lng['hami'].'</option>';
echo '<option value="1"'.($roomView == '1' ? ' selected' : '').'>'.$__lng['yalniz uzvler'].'</option>';
echo '</select><br/><br/>';
	
echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" /><br/>';
echo '</form>';

break;


case 'clear_msgs';

$delete = mysql_query("DELETE FROM `aloaz_db`.`room_msgs` WHERE `rid` = '".$rid."'");
if($delete) echo $__lng['otaqdaki yazilar silindi'].'<br/>';
else echo 'DB error<br/>';
 
break;


case 'del_room';

$_confirm = intval($_GET['confirm']);
if($_confirm == 0){
	echo $__lng['otaq silinsinmi'].'<br/><br/>';
	echo 'Beli, eminem. <a href="manage.php?mod=del_room&amp;confirm=1">'.$__lng['sil'].'</a><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

mysql_query("DELETE FROM `aloaz_db`.`room` WHERE `id` = '".$rid."' AND `uid` = '".$id."'");
if(mysql_affected_rows() > 0){
	echo $__lng['otaq silindi'].'<br/>';
	mysql_query("DELETE FROM `aloaz_db`.`room_subs` WHERE `rid` = '".$rid."'");
}
else echo 'DB error<br/>';
 
break;


default:

$statusArray = array();

$subsQuery = mysql_query("SELECT status, COUNT(id) FROM `aloaz_db`.`room_subs` WHERE `rid` = '".$rid."' GROUP BY status"); 
while($subsRow = mysql_fetch_array($subsQuery)){
	$statusArray[$subsRow['status']] = $subsRow['COUNT(id)'];
}

echo $__lng['otagin adi'].': <a href="msgs.php?rid='.$rid.'">'.$roomName.'</a><br/><br/>';
echo '- <a href="manage.php?mod=subs&amp;status=0">'.$__lng['otaq tesdiq gozleyenler'].' ['.intval($statusArray[0]).']</a><br/><br/>';
echo '- <a href="manage.php?mod=subs&amp;status=1">'.$__lng['aktiv uzvler'].' ['.intval($statusArray[1]).']</a><br/><br/>';
echo '- <a href="manage.php?mod=subs&amp;status=2">'.$__lng['deaktiv uzvler'].' ['.intval($statusArray[2]).']</a><br/><br/>';
echo '- <a href="manage.php?mod=clear_msgs">'.$__lng['yazilari sil'].'</a><br/><br/>';
echo '- <a href="manage.php?mod=edit">'.$__lng['otaq duzelisi'].'</a><br/><br/>';
echo '- <a href="manage.php?mod=del_room">'.$__lng['otagi sil'].'</a><br/>';

break;


}

echo '</div>';
include '../inc/footer.php';

?>
