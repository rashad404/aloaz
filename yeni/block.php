<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'Alochat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="block.php">'.$__lng['qadaga'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=block">'.$__lng['giris'].'</a> | <a href="reg.php?loc=block">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$_uid = checkData($_GET['uid']);

$mod = checkData($_GET['mod']);
switch($mod){
	
default:

echo $__lng['qadaga qoyduqlariniz'].':<br/><br/>';

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `chat_blocks` WHERE `id` = '".$id."'");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `uid` FROM `chat_blocks` WHERE `id` = '".$id."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");

if(mysql_num_rows($query) == 0){
	echo $__lng['qadaga qoymamisiniz'].'.<br/>';
	echo '</div>';
	break;
}

while($row = mysql_fetch_array($query)){
	$uid = $row['uid'];
	
	$userQuery = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."'");
	$uidLogin = mysql_result($userQuery, 0);
	
	echo '<a href="profile.php?uid='.$uid.'">'.$uidLogin.'</a> - <a href="block.php?mod=del&amp;uid='.$uid.'">'.$__lng['qadaga legvi'].'</a><br/><br/>';
}

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="block.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="block.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="block.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="block.php?page='.$i.'">'.$i.'</a> ';
			}
			else{
				echo ' <span id="pageButon_off">'.$i.'</span> ';
			}
		}
		
	}
}
if($page <= $max - $interval) echo '... ';

if($max > $interval){
	if($max != $page){
		echo ' <a href="block.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="block.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

break;


case 'request':

$checkUserQuery = mysql_query("SELECT `id` FROM `chat_users` WHERE `id` = '".$_uid."';");
if(mysql_num_rows($checkUserQuery) == 0){
	echo $__lng['istifadeci tapilmadi'];
	break;
}

$checkBlockQuery = mysql_query("SELECT `id` FROM `chat_blocks` WHERE `id` = '".$id."' AND `uid` = '".$_uid."';");
if(mysql_num_rows($checkBlockQuery) > 0){
	echo $__lng['evvel qadaga qoymusunuz'];
	break;
}

$block_confirm = intval($_GET['block_confirm']);
if($block_confirm == 0){
	echo $__lng['blok etmeye eminsiniz'].'<br/><br/>';
	echo $__lng['beli eminem'].'. <a href="block.php?mod=request&amp;uid='.$_uid.'&amp;block_confirm=1">'.$__lng['tesdiqle'].'</a><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

mysql_query("INSERT INTO `chat_blocks` SET `id` = '".$id."', `uid` = '".$_uid."', `time` = '".time()."';");
mysql_query("UPDATE `admin_alochat`.`conversation` SET `blocked`=1 WHERE (user_one='".$id."' AND user_two='".$_uid."') OR (user_one='".$_uid."' AND user_two='".$id."') LIMIT 1");
if(mysql_affected_rows()>0){
	echo $__lng['qadaga qoyuldu'].'<br/>';
}
else{
	echo 'Database error<br/>';
}

break;


case 'del':

$del_confirm = intval($_GET['del_confirm']);
if($del_confirm == 0){
	echo $__lng['qadaga legvine eminsiniz'].'<br/><br/>';
	echo $__lng['beli eminem'].'. <a href="block.php?mod=del&amp;uid='.$_uid.'&amp;del_confirm=1">'.$__lng['qadaga legvi'].'</a><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

$checkBlockQuery = mysql_query("SELECT `uid` FROM `chat_blocks` WHERE `id` = '".$id."' AND `uid` = '".$_uid."';");
if(mysql_num_rows($checkBlockQuery) == 0){
	echo $__lng['qadaga qoyulmayib'].'.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}

mysql_query("DELETE FROM `chat_blocks` WHERE `id` = '".$id."' AND `uid` = '".$_uid."' LIMIT 1;");
mysql_query("UPDATE `admin_alochat`.`conversation` SET `blocked`=0 WHERE (user_one='".$id."' AND user_two='".$_uid."') OR (user_one='".$_uid."' AND user_two='".$id."') LIMIT 1");

if(mysql_affected_rows()>0){
	echo $__lng['qadaga legv olundu'].'.<br/>';
}
else{
	echo 'Database error<br/>';
}

break;

}

echo '</div>';
include 'inc/footer.php';

?>
