<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">Müracietler</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `user_status`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id']; 
$user_status = $userrow["user_status"];

$adminlogin = false;
if($id==1129446 or $id==1129447){
	$adminlogin = true; 
}

if($user_status!=10 and $adminlogin==false){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}

// DELETE A TASK
$_del = intval($_GET['del']);
$_tid = intval($_GET['tid']);

if($_del == 1){
	echo '<div class="notif" align="center">';
	echo 'Silmek istediyinize eminsiniz?<br/>';
	echo '<a href="?page='.intval($_GET['page']).'">'.$__lng['xeyr'].'</a> / ';
	echo '<a href="?page='.intval($_GET['page']).'&amp;del=2&amp;tid='.$_tid.'">'.$__lng['beli'].'</a><br/>';
	echo '</div>';
}
if($_del == 2){
	$query = mysql_query("SELECT `id` FROM aloaz_db.`task` WHERE `id` = '".$_tid."';");
	$row = mysql_fetch_array($query);
	mysql_query("UPDATE `aloaz_db`.`task` SET `status`=1 WHERE id='".$_tid."'"); 
	if(mysql_affected_rows() > 0){
		echo 'silindi<br />';
	}
}

echo 'Admin müracietler<br/><br/>
<a class="button" href="create.php">Yeni müraciət yarat</a><br /><br />';



$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`task` WHERE `status`=0");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);
 	
	
$query = mysql_query("SELECT `id`, `name`, `uid`, `type`,`create_time` FROM `aloaz_db`.`task` WHERE `status`=0 ORDER BY `id` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$task_id = $row['id'];
	$task_name = $row['name'];  
	
	$messagesQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`task_msgs` WHERE `tid`='".$task_id."'");
	$messagesCount = mysql_num_rows($messagesQuery);
	
	mysql_query("UPDATE `aloaz_db`.`room` SET `online` = '".$roomOnline."' WHERE `id` = '".$room_id."' LIMIT 1");
	
	echo '<div style="margin-bottom:8px"><a href="msgs.php?tid='.$task_id.'">'.$task_name.' </a> ('.$messagesCount.') ';	
	echo '<span style="float:right; padding-right: 8px;"><a href="?page='.$page.'&amp;del=1&amp;tid='.$task_id.'">'.$__lng['sil'].'</a></span>';
	echo '<br />';
	echo '</div>';
} 

echo '</div>';
include '../inc/footer.php';
?>
