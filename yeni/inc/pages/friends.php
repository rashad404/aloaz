<?
echo '<table width="100%" cellpadding="2">';
echo '<tr><td>'.$profileImg.'</td>
<td width="100%"><a href="profile.php?uid='.$id.'">'.$login.'</a><br/>'; 
echo '<small><i>'.$status.'</i> <a href="status.php">Deyiş</a><br/>';
echo '</td></tr>';
echo '</table>';

echo '</div>';
echo '<div class="graynav">Dostlar - <a href="settings.php">Tanışlıq deaktiv</a></div>';
echo '<div class="layer">';

$unread_count = mysql_query("SELECT COUNT(`lid`) FROM `chat_messages` WHERE `to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) $countUnreadNot = '+'.$count_unread.'';

echo '<a href="messages.php?mod=unread"><img src="img/messages.png" alt="." /> Mesajlar</a> '.$countUnreadNot.'<br/><br/>';

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `chat_friends` WHERE`id` = ".$id." OR `uid` = ".$id."");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 8;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$friendsQuery = mysql_query("SELECT `id`, `uid`, `ok1`, `ok2` FROM `chat_friends` WHERE `id` = ".$id." OR `uid` = ".$id." ORDER BY `ok2` ASC, `act_time` DESC LIMIT ".$start.", ".$show_limit."");

if(mysql_num_rows($friendsQuery) == 0){
	echo 'Dost siyahısı boşdur.<br/>';
}

echo '<table width="100%" cellpadding="3" cellspacing="0">';

while($friendsRow = mysql_fetch_array($friendsQuery)){
	$friends_id = $friendsRow['id'];
	$friends_uid = $friendsRow['uid'];
	$friends_ok2 = $friendsRow['ok2'];
	
	if($friends_id != $id) $f_uid = $friends_id; else $f_uid = $friends_uid;
	if($friends_uid == $id && $friends_ok2 == 0) $new_friend = 'ok'; else $new_friend = 'no';
	
	if($friends_id == $id && $friends_ok2 != 1) $f_status = 'Tesdiq gözleyir'; else $f_status = '';
	
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
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td width="1%"><a href="profile.php?uid='.$f_uid.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7" /></a></td>';
	echo '<td width="80%" style="line-height: 17px"><a href="messages.php?mod=messaging&amp;uid='.$f_uid.'&amp;back=friends">'.$f_login.'</a> <span style="font-size:11px">('.$fuser_sex_.'/'.$friend_age.')<br/>'; 
	if($new_friend == 'ok') echo 'Dostluq teklifi:<br/><a class="button_gray" href="frequest.php?mod=confirm&amp;uid='.$f_uid.'">Qebul</a> / <a class="button_gray" href="frequest.php?mod=del&amp;uid='.$f_uid.'">Imtina</a>';
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

if($page > 1) echo '<a href ="main.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="main.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="main.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="main.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="main.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="main.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

echo '<br/><form method="post" action="search.php">';
echo 'Loqin:<br/>';
echo '<input type="text" name="login" /><br/>';
echo '<input type="submit" name="submit" value="Axtar &amp; Dost ol" />';
echo '<input type="hidden" name="similarity" value="1" />';
echo '</form><br/>';
?>