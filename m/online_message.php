<?
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/lang/pack.php';
include 'inc/params.php';

$title = 'Onlayn mesaj';
include 'inc/header.php';

if($_SESSION['auth'] == true) $home_alochat = '<a href="main.php">AloChat</a>'; else  $home_alochat = 'AloChat';
echo '<div class="mnav">'.$home_alochat.' » <a href="online_message.php">'.$title.'</a></div>';
echo '<div class="layer">';

$admin_status = 0;
$checkAuth = checkAuth('`id`, `nickname`, `user_status`, `coins`, `mysmile`');
if($checkAuth != 'error'){
	$userrow = mysql_fetch_array($checkAuth);
	if($userrow["user_status"]==10)
	$admin_status = 1;
	
	$auth_uid = $userrow["id"];
	$auth_login = $userrow["nickname"];
	$auth_coins = $userrow["coins"];
	$auth_mysmile = $userrow["mysmile"];
}
else{
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=online">'.$__lng['giris'].'</a> | <a href="register.php">'.$__lng['qeyd ol'].'</a>', 2);
}

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'" alt="'.$key.'" />';
}

switch($_GET['mod']){

default:

echo '<a href="online_message.php?mod=add">Onlayn Mesaj yaz</a><br/><br/>';

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`online_message`");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 6;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
$max = ceil($all_rows/$show_limit);

$query = mysql_query("SELECT `id`, `text`, `user_id`, `time`, `comments`, `likes` FROM `aloaz_db`.`online_message` ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$message_id = $row['id'];
	$message_text = $row['text'];
	$message_user_id = $row['user_id'];
	$message_time = $row['time'];
	$message_comments = $row['comments'];
	$message_likes = $row['likes'];
	
	$message_text = str_replace(array_keys($smilesArray), array_values($smilesArray), $message_text);
	
	$cnt_comments = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`online_message_comment` WHERE `message_id` = '".$message_id."'"), 0);
	
	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$message_user_id."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'udata'.$u_photo;
	
	if(date('d-m-Y', $message_time) == date('d-m-Y')) $date_str = date('H:i', $message_time);
	else if(date('d-m-Y', $message_time) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $message_time);
	else $date_str = date('d-m-Y H:i', $message_time);
	
	echo '<a href="/profile.php?uid='.$message_user_id.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
	if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="online_message.php?mod=read&id='.$message_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small>
	<a href="online_message.php?mod=read&amp;id='.$message_id.'"><img src="img/comment.png" alt="." style="vertical-align:middle; padding: 0 2px 0 5px" /> '.$message_comments.'</a>
	<a href="online_message.php?mod=likes&amp;id='.$message_id.'"><img src="img/like.png" alt="." style="vertical-align:middle; padding: 0 2px 0 5px" /> '.$message_likes.'</a>
	</span><br/>';
	echo $message_text.'<br/><br/>';
}

if($page > 1) echo '<a id="pageButon" href ="online_message.php?page='.($page-1).'">&lt;</a> ';
if($page < $max) echo '<a id="pageButon" href ="online_message.php?page='.($page+1).'">&gt;</a>';

if($page > 1 || $page < $max) echo '<br/>';

break;



case 'read':

$_message_id = intval($_GET['id']);

$query = mysql_query("SELECT * FROM `aloaz_db`.`online_message` WHERE `id` = '".$_message_id."';");

if(mysql_num_rows($query) == 0){
	echo 'Mesaj tapılmadı<br/>';
	break;
}

if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;

$row = mysql_fetch_array($query);
$message_id = $row['id'];
$message_user_id = $row['user_id'];
$message_text = $row['text'];
$message_time = $row['time'];
$message_text = str_replace(array_keys($smilesArray), array_values($smilesArray), $message_text);

if($message_user_id == $auth_uid){
	$_del = intval($_GET['del']);
	$_commentid = intval($_GET['commentid']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="online_message.php?mod=read&id='.$_message_id.'&amp;page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="online_message.php?mod=read&id='.$_message_id.'&amp;commentid='.$_commentid.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		mysql_query("DELETE FROM aloaz_db.`online_message_comment` WHERE `id` = '".$_commentid."' AND `message_id` = '".$_message_id."' LIMIT 1;");
		if(mysql_affected_rows() > 0) mysql_query("UPDATE aloaz_db.`online_message` SET `comments` = `comments` - 1 WHERE `id` = '".$_message_id."' LIMIT 1;");
	}
}

$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$message_user_id."';");
$u_row = mysql_fetch_array($u_query);
$u_login = $u_row['nickname'];
$u_sex = $u_row['sex'];
$u_photo = $u_row['profile_photo'];
if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';

if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
else $img_file = 'udata'.$u_photo;

if(date('d-m-Y', $message_time) == date('d-m-Y')) $date_str = date('H:i', $message_time);
else if(date('d-m-Y', $message_time) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $message_time);
else $date_str = date('d-m-Y H:i', $message_time);

echo '<a href="/profile.php?uid='.$message_user_id.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="online_message.php?mod=read&id='.$message_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
echo ' <small>'.$date_str.'</small>
</span><br/>';
echo '<b>Mesajı:</b> '.$message_text.'<br/>';

// yusif start
$file_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`online_message_comment` WHERE `message_id` = '".$_message_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 5;

if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
echo '<br/><img src="/img/comment.png" alt="Şerhler" style="vertical-align:middle;"> '.$all_rows;
echo ' <a href="online_message_addcom.php?id='.$_message_id.'">Şerh yaz</a><br /><br />';

$query = mysql_query("SELECT `id`, `comment`, `user_id`, `time` FROM aloaz_db.`online_message_comment` WHERE `message_id` = '".$_message_id."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo $__lng['sherh yazilmayib'].'<br/>';
}
while($row = mysql_fetch_array($query)){
	$comment_id = $row['id'];
	$comment = $row['comment'];
	$comment_uid = $row['user_id'];
	$comment_date = $row['time'];
	$comment = str_replace(array_keys($smilesArray), array_values($smilesArray), $comment);
 
	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$comment_uid."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'udata'.$u_photo;
	
	if(date('d-m-Y', $comment_date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $comment_date);
	else if(date('d-m-Y', $comment_date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $comment_date);
	else $date_str = date('d-m-Y H:i', $comment_date);
	
	echo '<a href="/profile.php?uid='.$comment_uid.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
	if($message_user_id == $auth_uid) echo '<span style="float:right; padding-right: 8px;"><a href="online_message.php?mod=read&id='.$_message_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small><br/>';
	echo $comment.'<br/><br/>';
}

echo '<br/><div class="pageNav">';

if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

if($page > 1) echo '<a href ="?mod=read&id='.$_message_id.'&amp;page='.($page-1).'">&lt;</a> ';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"?mod=read&id=".$_message_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"?mod=read&id=".$_message_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " <span>".$i."</span> ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"?mod=read&id=".$_message_id."&amp;page=".$i."\">".$i."</a> ";
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
		echo " <a href=\"?mod=read&id=".$_message_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " <span>".$max."</span> ";
	}
}

if($page < $max) echo '<a id="pageButon" href ="?mod=read&id='.$_message_id.'&amp;page='.($page+1).'">&gt;</a> ';
echo '</div><br/>';

break;






case 'likes':

$_message_id = intval($_GET['id']);

$query = mysql_query("SELECT * FROM `aloaz_db`.`online_message` WHERE `id` = '".$_message_id."';");

$row = mysql_fetch_array($query);
$message_id = $row['id'];
$message_user_id = $row['user_id'];
$message_text = $row['text'];
$message_time = $row['time'];
$message_text = str_replace(array_keys($smilesArray), array_values($smilesArray), $message_text);

if(mysql_num_rows($query) == 0){
	echo 'Mesaj tapılmadı<br/>';
	break;
}
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;

if($_GET['act'] == 'dislike'){
	mysql_query("DELETE FROM aloaz_db.`online_message_like` WHERE `like_id` = '".$auth_uid."' AND `user_id` = '".$message_user_id."' AND `message_id` = '".$message_id."' LIMIT 1;");
	if(mysql_affected_rows() > 0) mysql_query("UPDATE aloaz_db.`online_message` SET `likes` = `likes` - 1 WHERE `id` = '".$message_id."' LIMIT 1;");
}

if($_GET['act'] == 'like'){
	$checkQuery = mysql_query("SELECT `id` FROM aloaz_db.`online_message_like` WHERE `user_id` = '".$message_user_id."' AND `like_id` = '".$auth_uid."';");
	if(mysql_num_rows($checkQuery) == 0){
		mysql_query("INSERT INTO  aloaz_db.`online_message_like` SET `message_id` = '".$message_id."', `user_id` = '".$message_user_id."', `like_id` = '".$auth_uid."', `time` = '".time()."';");
		mysql_query("UPDATE aloaz_db.`online_message` SET `likes` = `likes` + 1 WHERE `id` = '".$message_id."' LIMIT 1;");
		
		setNotification($message_user_id,$paramsArray["NOTE_ONLINE_MESSAGE_LIKE"],time(),$auth_uid,$auth_login,0,$message_id);
	}
}

$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$message_user_id."';");
$u_row = mysql_fetch_array($u_query);
$u_login = $u_row['nickname'];
$u_sex = $u_row['sex'];
$u_photo = $u_row['profile_photo'];
if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';

if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
else $img_file = 'udata'.$u_photo;

if(date('d-m-Y', $message_time) == date('d-m-Y')) $date_str = date('H:i', $message_time);
else if(date('d-m-Y', $message_time) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $message_time);
else $date_str = date('d-m-Y H:i', $message_time);

echo '<a href="/profile.php?uid='.$message_user_id.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="online_message.php?mod=read&id='.$message_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
echo ' <small>'.$date_str.'</small>
</span><br/>';
echo '<b>Mesajı:</b> '.$message_text.'<br/><br/>';

$checkQuery = mysql_query("SELECT * FROM aloaz_db.`online_message_like` WHERE `user_id` = '".$message_user_id."' AND `like_id` = '".$auth_uid."';");
if(mysql_num_rows($checkQuery) > 0) echo 'Beyenmisiniz <a href="online_message.php?mod=likes&amp;act=dislike&amp;id='.$message_id.'">x</a><br/>';
else echo '<a href="online_message.php?mod=likes&amp;act=like&amp;id='.$message_id.'">Beyen</a><br/>';

$file_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`online_message_like` WHERE `message_id` = '".$_message_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 10;

if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;
echo '<br/>';

$query = mysql_query("SELECT `id`, `user_id`, `like_id`, `time` FROM aloaz_db.`online_message_like` WHERE `message_id` = '".$_message_id."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo 'İlk beyenen siz olun.<br/>';
}else{
	echo 'Cemi '.$all_rows.' nefer beyenib:<br/><br/>';
}
while($row = mysql_fetch_array($query)){
	$item_id = $row['id'];
	$item_uid = $row['user_id'];
	$item_like_id = $row['like_id'];
	$item_time = $row['time'];
 
	$u_query = mysql_query("SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id` = '".$item_like_id."';");
	$u_row = mysql_fetch_array($u_query);
	$u_login = $u_row['nickname'];
	$u_sex = $u_row['sex'];
	$u_photo = $u_row['profile_photo'];
	if($u_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
 	
	if(empty($u_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'udata'.$u_photo;
	
	if(date('d-m-Y', $item_time) == date('d-m-Y')) $date_str = date('H:i', $item_time);
	else if(date('d-m-Y', $item_time) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $item_time);
	else $date_str = date('d-m-Y H:i', $item_time);
	
	echo '<a href="/profile.php?uid='.$item_like_id.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
	if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="online_message.php?mod=read&id='.$_message_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small><br/>';
	echo $comment.'<br/><br/>';
}

echo '<br/><div class="pageNav">';

if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

if($page > 1) echo '<a href ="?mod=likes&id='.$_message_id.'&amp;page='.($page-1).'">&lt;</a> ';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"?mod=likes&id=".$_message_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"?mod=likes&id=".$_message_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " <span>".$i."</span> ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"?mod=likes&id=".$_message_id."&amp;page=".$i."\">".$i."</a> ";
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
		echo " <a href=\"?mod=likes&id=".$_message_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " <span>".$max."</span> ";
	}
}

if($page < $max) echo '<a id="pageButon" href ="?mod=likes&id='.$_message_id.'&amp;page='.($page+1).'">&gt;</a> ';
echo '</div><br/>';

break;


case 'add':

if(!isset($_POST['secnumber'])){
	$secnumber = rand(1000, 9999);
	$_SESSION['secnumber'] = $secnumber;
	
	echo '* '.$__lng['qeyri etik reklam yazilar olmaz'].'<br/><br/>';
	
	echo '<form method="post" action="online_message.php?mod=add">';
	echo 'Mesaj (maks: 200 simvol):<br/>';
	echo "<input type=\"text\" name=\"message\"/><br/>";
	echo '<input type="submit" value="'.$__lng['elave et'].'">';
	echo '<input type="hidden" name="id" value="'.$_id.'">';
	echo '<input type="hidden" name="secnumber" value="'.$secnumber.'">';
	echo '</form><br/>';
	
	echo 'Xidmetin deyeri: '.$paramsArray["onlineMessageCoin"].' bal<br/><br/>';
}
else{
	$_message = trim(htmlspecialchars(mysql_escape_string($_POST['message'])));
	$_message = str_replace('$', '$$', $_message);
	
	$_id = intval($_POST['id']);
	$secnumber = intval($_POST['secnumber']);
	
	$error = '';
	
	if($secnumber != $_SESSION['secnumber']) $error .= '- '.$__lng['ardicil elave olmaz'].'<br/>';
	if(empty($_message)) $error .= '- Mesaj yazılmayıb<br/>';
	
	if($auth_coins < $paramsArray["onlineMessageCoin"]){
		echo $__lng['hesabda bal yoxdur'].'.<br/><br/>';
		echo '<a href="buy.php">Bal almaq</a><br/><br/>';
		break;
	}

	if(!empty($error)){
		echo 'Aşağıdakı səhvlər baş verdi: <br/><br/>';
		echo '<span style="color: red">'.$error.'</span><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a></div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	
	if($auth_mysmile==1 and strpos($_message,".my.")!==false){
		$smile = mysql_fetch_assoc(mysql_query("SELECT `smile` FROM `aloaz_db`.`smiles` WHERE `user_id`='".$auth_uid."' ORDER BY `id` DESC LIMIT 1"));
		if($smile){
			$_message = str_replace(".my.",$smile["smile"],$_message);
		}	
	}
	
	
	mysql_query("INSERT INTO aloaz_db.`online_message` SET `user_id` = '".$auth_uid."', `login` = '".$auth_login."', `text` = '".$_message."', `time` = '".time()."'");
	if(mysql_affected_rows() > 0){
		echo $__lng['muveffeqiyyetle elave olundu'].'<br/>';
		
		mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`-".$paramsArray["onlineMessageCoin"]."  WHERE `id` = '".$auth_uid."' LIMIT 1;");
		// log coin
		mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$auth_uid."',`coins`='".$paramsArray["onlineMessageCoin"]."',`type`=1,`text`='".$paramsArray["LOG_ONLINE_MESSAGE"]."',`date`='".date("Y-m-d H:i:s")."';");
	}
	else{
		echo 'Error<br/>';
	}
}

break;


}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include 'inc/footer.php';
?>
