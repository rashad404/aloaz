<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="friends.php">'.$__lng['dostlar'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `phone`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=friends">'.$__lng['giris'].'</a> | <a href="reg.php?loc=friends">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$phone = $userrow['phone'];

checkPhoneBan($phone); 

$unreadQuery = mysql_query("SELECT `from` FROM aloaz_db.`convesation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0 ORDER BY `time` DESC");
$count_unread = mysql_num_rows($unreadQuery);

if($count_unread > 0){
	$newMessageArray = array();
	while($unreadRow = mysql_fetch_array($unreadQuery)){
		$unread_uid = $unreadRow['from'];
		
		$newMessageArray[$unread_uid] = 'new';
	}

	echo '<a href="messages.php?mod=unread"><img src="img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['yeni mesaj var'].'<br/><br/>';
}

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user_friend` WHERE `user_1` = ".$id." OR `user_2` = ".$id."");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 8;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

 $friendsQuery = mysql_query("SELECT `user_1`, `user_2`, `ok`, `ok_time` FROM `aloaz_db`.`user_friend` WHERE `user_1` = ".$id." OR `user_2` = ".$id." ORDER BY `ok` ASC, `ok_time` DESC LIMIT ".$start.", ".$show_limit."");

if(mysql_num_rows($friendsQuery) == 0){
	echo $__lng['dost siyahisi bosdur'].'.<br/>';
}
else{

echo '<table width="100%" cellpadding="3" cellspacing="0">';

while($friendsRow = mysql_fetch_array($friendsQuery)){
	$friends_id = $friendsRow['user_1'];
	$friends_uid = $friendsRow['user_2'];
	$friends_ok2 = $friendsRow['ok'];
	
	if($friends_id != $id) $f_uid = $friends_id; else $f_uid = $friends_uid;
	if($friends_uid == $id && $friends_ok2 == 0) $new_friend = 'ok'; else $new_friend = 'no';
	
	if($friends_id == $id && $friends_ok2 != 1) $f_status = ''.$__lng['dost sorgusu gondermisiniz'].'.<span style="color: red">'.$__lng['teklife baxmayib'].'</span>'; else $f_status = '';
	
	$friendUserQuery = mysql_query("SELECT `nickname`, `sex`, `name`, `photo`, `time`, `il`,`gun`,`ay`,`status` FROM `chat_users` WHERE `id` = '".$f_uid."'");
 	$friendUserRow = mysql_fetch_array($friendUserQuery);
	$f_login = $friendUserRow['nickname'];
	$f_sex = $friendUserRow['sex'];
	$f_name = $friendUserRow['name'];
	$f_photo = $friendUserRow['photo'];
	$f_time = $friendUserRow['time'];
	$friend_birth_day = $friendUserRow['gun'];
	$friend_birth_month = $friendUserRow['ay'];
	$friend_birth_year = $friendUserRow['il'];
	$friend_status = $friendUserRow['status'];
	
	if(strlen($friend_status) > 50) $friend_status = substr($friend_status, 0, 50).'...';
	
	if($f_time > time()) $lastOnline = '<span style="font-size:11px; color: green;">Online</span>'; else $lastOnline = '<span style="font-size:11px;">'.date('Y-m-d H:i', ($f_time-600)).'</span>';
	
	if($f_sex==0){
		$fuser_sex_ = 'K';
		$fuser_sex_img ='man';
	}
	else{
		$fuser_sex_ = 'Q';
		$fuser_sex_img='woman';
	}
	
	$expPhoto = explode('|', $f_photo);
	$f_photo_name = $expPhoto[0];
	$f_photo_id = $expPhoto[1];
	
	if(!empty($f_photo_name)) $photoIds .= '-'.$f_photo_id.'';
	
	$friend_age = floor( (strtotime(date('Y-m-d')) - strtotime("$friend_birth_year-$friend_birth_month-$friend_birth_day")) / 31556926);
	
	if(empty($f_photo)) $img_file = 'img/'.$fuser_sex_img.'.gif';
	else $img_file = 'photos/files/thumbs/small/'.$f_sex.'/'.$f_photo_name;
	
	if(!empty($newMessageArray[$f_uid])) $newMessageImg = '<a href="messages.php?mod=messaging&amp;uid='.$f_uid.'&amp;back=friends"><img src="img/new_message.png" alt="msg" /></a>'; else $newMessageImg = '';
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td width="1%"><a href="profile.php?uid='.$f_uid.'&amp;back=friends"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7" /></a></td>';
	echo '<td width="80%" style="line-height: 17px"><a href="messages.php?mod=messaging&amp;uid='.$f_uid.'&amp;back=friends">'.$f_login.'</a> <span style="font-size:11px">('.$fuser_sex_.'/'.$friend_age.')<br/>'; 
	if($new_friend == 'ok') echo 'Dostluq teklifi:<br/><a class="button_gray" href="frequest.php?mod=confirm&amp;uid='.$f_uid.'">'.$__lng['qebul'].'</a> / <a class="button_gray" href="frequest.php?mod=del&amp;uid='.$f_uid.'">'.$__lng['imtina'].'</a>';
	else{
		if($f_status != '') echo $f_status.'<br/>'; else echo ''.$friend_status.'<br/>'.$lastOnline;
	}
	
	echo '</span></td>';
	echo '<td width="10%" style="text-align:center;border:0">'.$newMessageImg.'</td>';
	echo '</tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="friends.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="friends.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="friends.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="friends.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="friends.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="friends.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';
}

echo '<br/><form method="post" action="search.php">';
echo 'Loqin:<br/>';
echo '<input type="text" name="login" /><br/>';
echo '<input type="submit" name="submit" value="'.$__lng['axtar ve dost ol'].'" />';
echo '</form><br/>';

echo '</div>';
include 'inc/footer.php';

?>
