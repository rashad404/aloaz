<?
error_reporting(0);
session_start();
include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav">'.$title.'</div>';
echo '<div class="layer">';

$checkAuth = checkAuthNew('`id`, `nickname`, `last_post`, `profile_photo`, `sex`, `phone`, `md5_pass`, `coins`, `msg_count`, `no_dating`, `country_id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=main">'.$__lng['giris'].'</a> | <a href="reg.php?loc=main">'.$__lng['qeyd ol'].'</a> | <a href="recover.php">'.$__lng['sifre berpasi'].'</a>' , 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$status = stripslashes($userrow['last_post']);
$sex = $userrow['sex'];
$photo = $userrow['profile_photo'];
$phone = $userrow['phone'];
$md5_pass = $userrow['md5_pass'];
$post = $userrow['msg_count'];
$points = $userrow['coins'];
$dating = $userrow['no_dating']==0?1:0;
$country = 'az';//$userrow['country_id'];

 
checkPhoneBan($phone);
updateOnline();

if(strlen($phone) != 12 && $country == 'az') echo '<div class="notif">'.$__lng['nomre tesdiqi teleb'].' - <a href="submitphone.php">'.$__lng['tesdiqle'].'</a></div>';

if(strlen($status) > 40) $status = substr($status, 0, 40).'...';

if($sex==0){
	$u_sex_l='K';
	$u_sex_img ='man';
}
else{
	$u_sex_l='Q';
	$u_sex_img='woman';
}

if(empty($photo)){
	$img_file = 'img/'.$u_sex_img.'.gif';
	$profileImg = '<a href="profile_edit.php?mod=photo"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7" /></a>';
} else{ 	
	$img_file = 'https://m.alo.az/udata'.$photo;
	$profileImg = '<a href="profile_edit.php?mod=photo"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a>';
}
 

if($dating == 1 && $id == 427062){
	include 'inc/pages/friends.php';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}

echo '<a href="http://m.alo.az/alochat.php">Yeni Versiyaya keç</a><br/><br/>';

echo '<table width="100%" cellpadding="2">';
echo '<tr><td>'.$profileImg.'</td>
<td width="100%"><a href="profile.php?uid='.$id.'">'.$login.'</a><br/>'; 
echo '<i>'.$status.'</i> <a href="status.php">'.$__lng['deyis'].'</a><br/>';
echo ''.$__lng['post'].': <a href="topposts.php">'.$post.'</a> ';
if($country == 'az') echo '/ '.$__lng['bal'].': <a href="pointserv.php">'.$points.'</a>';
echo '</td></tr>';
echo '</table><br/>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) $countUnreadNot = '+'.$count_unread.'';

$newFriendsQuery = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user_friend` WHERE `user_2` = ".$id." AND `ok` = '0'");
$newFriendsCount = mysql_result($newFriendsQuery, 0);

if($newFriendsCount > 0) $newFriendsQueryNot = '+'.$newFriendsCount.'';

$newguests_count = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user_visit` WHERE `visit_to` = '".$id."' AND `time` > '".(time()-72*3600)."' AND `seen` = '0'");
$newguests_count = mysql_result($newguests_count, 0);

if($newguests_count > 0) $countNewguests = '+'.$newguests_count.'';

if($country == 'az'){
	$query = mysql_query("SELECT * FROM `aloaz_db`.`news` WHERE `time` > '".(time()-3600*24*7)."' ORDER BY `time` DESC LIMIT 2;");
	if(mysql_num_rows($query)>0){
		while($row = mysql_fetch_array($query)){
			$news_id = $row['id'];
			$news_title = $row['title'];
			$news_body = $row['body'];
			$news_time = $row['time'];
			
			echo '- <a href="news.php?mod=read&amp;id='.$news_id.'">'.$news_title.'</a><br/>';
		}
		echo '<br/>';
	}
}

if($newFriendsCount > 0) echo '<a href="friends.php">'.$newFriendsCount.'</a> '.$__lng['dost teklifi var'].'<br/>';
if($count_unread > 0) echo '<a href="messages.php?mod=unread">'.$count_unread.'</a> '.$__lng['mesajin var'].'<br/>';

if($newFriendsCount > 0 || $count_unread > 0) echo '<br/>';

if($country == 'az') echo '<a href="http://metbuat.az/?ref=aloaz">En son xeberler - METBUAT.AZ</a><br/><br/>';

//if($country == 'az') $online_link = 'online.php'; else $online_link = 'online-global.php';
$online_link = 'online.php';

echo '<a href="main.php?refresh='.rand(11111,9999).'"><img src="img/refresh.png" alt="." /> '.$__lng['yenile'].'</a><br/>';
echo '<a href="'.$online_link.'"><img src="img/online_users.png" alt="." /> <b>'.$__lng['onlayn'].' ['.getOnline('all').']</b></a><br/>';
echo '<a href="room/index.php"><img src="img/room.png" alt="." /> '.$__lng['sohbet otaqlari'].'</a><br/>';
echo '<a href="messages.php?mod=unread"><img src="img/messages.png" alt="." /> '.$__lng['mesajlar'].'</a> '.$countUnreadNot.'<br/>';
echo '<a href="share/?filter=all"><img src="img/share.png" alt="." /> '.$__lng['paylas'].'</a><br/>';
echo '<a href="friends.php"><img src="img/friends.png" alt="." /> '.$__lng['dostlar'].'</a> '.$newFriendsQueryNot.'<br/>';
echo '<a href="search.php"><img src="img/search.png" alt="." /> '.$__lng['axtaris'].'</a><br/>';
echo '<a href="guests.php"><img src="img/guests.png" alt="." /> '.$__lng['qonaqlar'].'</a> '.$countNewguests.'<br/>';
echo '<a href="block.php"><img src="img/blocked_users.png" alt="." /> '.$__lng['qadaga qoyduqlarim'].'</a><br/>';
echo '<a href="pointserv.php"><img src="img/points.png" alt="." /> '.$__lng['bal xidmetleri'].'</a><br/>';
echo '<a href="profile_edit.php"><img src="img/profile.png" alt="." /> '.$__lng['profil deyis'].'</a><br/>';
echo '<a href="settings.php"><img src="img/settings.png" alt="." /> '.$__lng['aletler'].'</a><br/>';

$_ozunutanitnav = intval($_GET['ozunutanitnav']);

$ozunutanitQuery = mysql_query("SELECT `uid`, `login`, `status`, `photo_id` FROM `aloaz_db`.`chat_ozunutanit` WHERE `country` = '".$country."' ORDER BY `id` DESC LIMIT ".abs($_ozunutanitnav).", 1");
$ozunutanitRow = mysql_fetch_array($ozunutanitQuery);
$ozunutanit_uid = $ozunutanitRow['uid'];
$ozunutanit_login = $ozunutanitRow['login'];
$ozunutanit_status = $ozunutanitRow['status'];
$ozunutanit_photo_id = $ozunutanitRow['photo_id'];

$ozunutanit_user = mysql_fetch_assoc(mysql_query("SELECT `sex`,`profile_photo` FROM `aloaz_db`.`user` WHERE id='".$ozunutanit_uid."' limit 1"));
$ozunutanit_user_sex = $ozunutanit_user['sex'];
$ozunutanit_user_photo = $ozunutanit_user['profile_photo'];
if($ozunutanit_user_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';

if(empty($ozunutanit_user_photo)) $img_file = 'img/'.$sex_icon.'.gif';
else $img_file = 'https://m.alo.az/udata'.$ozunutanit_user_photo;
if(abs($_ozunutanitnav) > 4 ) $_ozunutanitnav = 0;
if(strlen($ozunutanit_status) > 50) $ozunutanit_status = substr($ozunutanit_status, 0, 50);

echo '<br/><div align="center">';

echo '<a href="topposts.php">'.$__lng['top 50 post'].'</a><br/><br/>';

echo $__lng['bookmark ucun daxil ol'].'.<br/>';
echo '<a href="bookmark.php?auth='.$id.'-'.$md5_pass.'&amp;t='.time().'"><img src="img/bookmark.png" alt="." /> '.$__lng['bookmark et'].'</a><br/><br/>';

echo '<table class="ozunutanit">';
echo '<tr align="center"><th colspan="2"><a href="main.php?mod=main&amp;ozunutanitnav='.($_ozunutanitnav-1).'">&lt;&lt;</a> <a href="pointserv.php?mod=ozunutanit">'.$__lng['ozunu tanit'].'</a> <a href="main.php?mod=main&amp;ozunutanitnav='.($_ozunutanitnav+1).'">&gt;&gt;</a></th></tr>';
echo '<tr>';
 echo '<td><a href="profile.php?uid='.$ozunutanit_uid.'"><img src="'.$img_file.'" alt="photo" style="border: 1px solid #d7d7d7;width:55px;height:55px;" /></a></td>';
echo '<td><a href="profile.php?uid='.$ozunutanit_uid.'">'.$ozunutanit_login.'</a><br/>'; 
echo '<small><i>'.$ozunutanit_status.'</i></small><br/>';
echo '</td></tr>';
echo '</table><br/>';
echo '</div>';

if($country == 'az'){
	echo '<a href="news.php"><img src="img/news.png" alt="." /> '.$__lng['yenilikler'].'</a><br/>';
	echo '<a href="links.php"><img src="img/links.png" alt="." /> '.$__lng['linkler'].'</a><br/>';
	echo '<a href="support.php"><img src="img/support.png" alt="." /> '.$__lng['bize yaz'].'</a><br/><br/>';
}
echo '<a href="logout.php"><img src="img/logout.png" alt="." /> '.$__lng['cixis'].'</a>';
echo '</div>';
?>
<?
include 'inc/footer.php';
?>
