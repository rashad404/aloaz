<?
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/lang/pack.php';

$title = 'Elanlar';
include 'inc/header.php';

if($_SESSION['auth'] == true) $home_alochat = '<a href="main.php">AloChat</a>'; else  $home_alochat = 'AloChat';
echo '<div class="mnav">'.$home_alochat.' » <a href="elan.php">'.$title.'</a></div>';
echo '<div class="layer">';

$admin_status = 0;
$checkAuth = checkAuth('`id`, `user_status`');
if($checkAuth != 'error'){
	$userrow = mysql_fetch_array($checkAuth);
	if($userrow["user_status"]==10) $admin_status = 1;
	
	$id = $userrow["id"];
	
}

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'" alt="'.$key.'" />';
}

switch($_GET['mod']){

default:

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`elan`");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 8;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);

if($admin_status == 1){
	$_del = intval($_GET['del']);
	$_elan_id = intval($_GET['id']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="elan.php?id='.$_elan_id.'&amp;page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="elan.php?id='.$_elan_id.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		mysql_query("DELETE FROM aloaz_db.`elan` WHERE `id` = '".$_elan_id."' LIMIT 1;");
		mysql_query("DELETE FROM aloaz_db.`elan_comment` WHERE `elan_id` = '".$_elan_id."';");
	}
}

$query = mysql_query("SELECT `id`, `title`, `body`, `uid`, `time` FROM `aloaz_db`.`elan` ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo 'Elan yoxdur<br/><br/>';
}
while($row = mysql_fetch_array($query)){
	$elan_id = $row['id'];
	$elan_title = $row['title'];
	$elan_body = $row['body'];
	$elan_uid = $row['uid'];
	$elan_time = $row['time'];
	
	$elan_nick = mysql_result(mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = '".$elan_uid."'"), 0);
	
	$cnt_elan = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`elan_comment` WHERE `elan_id` = '".$elan_id."'"), 0);
	
	if($elan_time > strtotime('today')) $elan_date_str = 'Bugün '.date('H:i', $elan_time);
	else $elan_date_str = date('d-m-Y H:i', $elan_time);
	
	echo '<span style="font-size: 12px">'.$elan_date_str.' <img src="img/comment.png" alt="." style="vertical-align:middle; padding: 0 2px 0 5px" /> '.$cnt_elan.'</span><br/>';
	if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="elan.php?page='.$page.'&amp;del=1&amp;id='.$elan_id.'">'.$__lng['sil'].'</a></span>';
	echo $elan_nick.' <a href="elan.php?mod=read&amp;id='.$elan_id.'">'.$elan_title.'</a><br/><br/>';
}

if($page > 1) echo '<a id="pageButon" href ="elan.php?page='.($page-1).'">&lt;</a> ';
if($page < $max) echo '<a id="pageButon" href ="elan.php?page='.($page+1).'">&gt;</a>';

if($page > 1 || $page < $max) echo '<br/>';

echo 'Qeyd: Yalnız vezifeli istifadeçiler elan yaza bilir.<br/>';

break;


case 'read':

$_elan_id = intval($_GET['id']);

$query = mysql_query("SELECT * FROM `aloaz_db`.`elan` WHERE `id` = '".$_elan_id."';");

if(mysql_num_rows($query) == 0){
	echo $__lng['xeber tapilmadi'].'<br/>';
	break;
}
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;


$row = mysql_fetch_array($query);
$elan_id = $row['id'];
$elan_title = $row['title'];
$elan_body = nl2br($row['body']);
$elan_uid = $row['uid'];
$elan_time = $row['time'];
$elan_body = str_replace(array_keys($smilesArray), array_values($smilesArray), $elan_body);

if($admin_status == 1 || $elan_uid == $id){
	$_del = intval($_GET['del']);
	$_commentid = intval($_GET['commentid']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="elan.php?mod=read&id='.$_elan_id.'&amp;page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="elan.php?mod=read&id='.$_elan_id.'&amp;commentid='.$_commentid.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		mysql_query("DELETE FROM aloaz_db.`elan_comment` WHERE `id` = '".$_commentid."' AND `elan_id` = '".$_elan_id."' LIMIT 1;");
	}
}

if($elan_time > strtotime('today')) $elan_date_str = 'Bugün '.date('H:i', $elan_time);
else $elan_date_str = date('d-m-Y H:i', $elan_time);

	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$elan_uid."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$u_photo;
	
echo '<table width="100%" cellpadding="1">';
echo '<tr><td><img src="'.$img_file.'" alt="." width="40" height="40" /></td>
<td width="100%"><a href="profile.php?uid='.$elan_uid.'">'.$u_login.'</a><br/>'; 
echo '<span style="font-size: 12px">'.$elan_date_str.'</span><br/>';
echo '</td></tr>';
echo '</table>';
echo '<b>'.$elan_title.'</b><br/>';
echo ''.$elan_body.'<br/>';

// yusif start
$file_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`elan_comment` WHERE `elan_id` = '".$_elan_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 5;

if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
echo '<br/><img src="/img/comment.png" alt="Şerhler" style="vertical-align:middle;"> '.$all_rows;
echo ' <a href="addelancomment.php?id='.$_elan_id.'">Şerh yaz</a><br /><br />';

$query = mysql_query("SELECT `id`, `comment`, `uid`, `time` FROM aloaz_db.`elan_comment` WHERE `elan_id` = '".$_elan_id."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo $__lng['sherh yazilmayib'].'<br/>';
}
while($row = mysql_fetch_array($query)){
	$comment_id = $row['id'];
	$comment = $row['comment'];
	$comment_uid = $row['uid'];
	$comment_date = $row['time'];
	$comment = str_replace(array_keys($smilesArray), array_values($smilesArray), $comment);
 
	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$comment_uid."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$u_photo;
	
	if(date('d-m-Y', $comment_date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $comment_date);
	else if(date('d-m-Y', $comment_date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $comment_date);
	else $date_str = date('d-m-Y H:i', $comment_date);
	
	echo '<a href="/profile.php?uid='.$comment_uid.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
	if($admin_status == 1 || $elan_uid == $id) echo '<span style="float:right; padding-right: 8px;"><a href="elan.php?mod=read&id='.$_elan_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small><br/>';
	echo $comment.'<br/><br/>';
}

echo '<br/><div class="pageNav">';

if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

if($page > 1) echo '<a href ="?mod=read&id='.$_elan_id.'&amp;page='.($page-1).'">&lt;</a> ';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"?mod=read&id=".$_elan_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"?mod=read&id=".$_elan_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " <span>".$i."</span> ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"?mod=read&id=".$_elan_id."&amp;page=".$i."\">".$i."</a> ";
			}
			else{
				echo " <span>".$i."</span> ";
			}
		}
		
	}
}
if($page <= $max - 5) echo '... ';

if($max > $interval){
	if($max != $page){
		echo " <a href=\"?mod=read&id=".$_elan_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " <span>".$max."</span> ";
	}
}

if($page < $max) echo '<a id="pageButon" href ="?mod=read&id='.$_elan_id.'&amp;page='.($page+1).'">&gt;</a> ';
echo '</div><br/>';

break;

}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include 'inc/footer.php';
?>
