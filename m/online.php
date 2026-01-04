<?php
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';
include 'inc/smiles.php';

$title = 'AloChat';
include 'inc/header.php';

$f_gender = checkData($_GET['f_gender']);
$_country = strtolower(checkData($_GET['country']));

if($f_gender == 'men') $ins_sex = " AND `sex` = '0' ";
else if($f_gender == 'women') $ins_sex = " AND `sex` = '1'";
else $ins_sex = "";

echo '<div class="mnav"><a href="main.php">'.$title.'</a> '.$country_name.' » '.$__lng['onlayn'].':'.getOnline('all').'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `country_id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=online">'.$__lng['giris'].'</a> | <a href="reg.php?loc=online">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$country = 'az';//strtolower($userrow['country']);

if(strlen($_country) == 2){
	$ins_country = " AND `country` = '".$_country."'";
	$country_name = countryCodeName($_country);
}
else{
	$ins_country = " AND `country` = '".$country."'";
}
$ins_country = '';

if($country == 'az'){
	$online_love = getOnline('all');
	$online_love_sex_woman = getOnline('women');
	$online_love_sex_man = getOnline('men');
}
else{
	$online_love = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `last_activity` > '".time()."' AND `invisible`=0 AND `no_dating` = 1 ".$ins_country.""), 0); 
	$online_love_sex_woman = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `last_activity` > '".time()."' AND `invisible`=0 AND `sex` = 1 AND `no_dating` = 1 ".$ins_country.""), 0);
	$online_love_sex_man = $online_love - $online_love_sex_woman;
	
	if($country_name == '') $country_name = countryCodeName($country);
}

//$globalOnline = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `last_activity` > '".(time()-10*60)."' AND `invisible`=0 AND `no_dating` = 1"), 0);

//echo '<div class="mnav"><a href="main.php">'.$title.'</a> '.$country_name.' » '.$__lng['onlayn'].':'.$online_love.'</div>';
//echo '<div class="layer">';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

//ONLINE IN CHAT
$sex = intval($_GET['sex']);
if($sex!=1 && $sex!=2){$sex=0;}
if($sex==1){$sex_m_w_sql=" AND `sex`=0";}
if($sex==2){$sex_m_w_sql=" AND `sex`=1";}
$order = intval($_GET['order']);
if($order!=0 && $order!=1){$order=0;}
if($order==0){$order_sql="ORDER BY `point` DESC, `last_activity` DESC";}
if($order==1){$order_sql="ORDER BY `last_activity`";}

 	updateOnline();

	//$online = time();
//$update = mysql_query("UPDATE `aloaz_db`.`user` SET `last_activity` = '".$online."', `place` = 15, `ip` = '".getenv('REMOTE_ADDR')."', `ua` = '".htmlspecialchars(getenv('HTTP_USER_AGENT'))."' WHERE `id` = '".$id."';"); 


if(date('H') == '00' && intval(date('i')) < 10){
	//updateXal();
	//updatePosts();
}

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'" alt="'.$key.'" />';
}

//if($country == 'az') echo '<a href="http://metbuat.az/?ref=aloaz">METBUAT.AZ : Saatın aktual xeberleri</a><br/><br/>';

echo '<a href="online_message.php">Onlayn Mesaj</a>: ';
$onlineMessageQuery = mysql_query("SELECT `id`, `text`, `user_id`, `login`, `comments`, `likes` FROM `aloaz_db`.`online_message` ORDER BY `id` DESC LIMIT 1");
while($onlineMessageRow = mysql_fetch_array($onlineMessageQuery)){
	$onMessage_id = $onlineMessageRow['id'];
	$onMessage_text = $onlineMessageRow['text'];
	$onMessage_uid = $onlineMessageRow['user_id'];
	$onMessage_login = $onlineMessageRow['login'];
	$onMessage_comments = $onlineMessageRow['comments'];
	$onMessage_likes = $onlineMessageRow['likes'];
	
	$onMessage_text = str_replace(array_keys($smilesArray), array_values($smilesArray), $onMessage_text);
	
	echo $onMessage_text.' 
	(<a href="online_message.php?mod=likes&amp;act=like&amp;id='.$onMessage_id.'">Beyen</a>
	<a href="online_message.php?mod=read&amp;id='.$onMessage_id.'"><img src="img/comment.png" alt="." style="vertical-align:middle; padding: 0 2px 0 5px" /> '.$onMessage_comments.'</a>
	<a href="online_message.php?mod=likes&amp;id='.$onMessage_id.'"><img src="img/like.png" alt="." style="vertical-align:middle; padding: 0 2px 0 5px" /> '.$onMessage_likes.'</a>)
	İmza: <a href="profile.php?uid='.$onMessage_uid.'&amp;back=online">'.$onMessage_login.'</a>';
}
echo '<br/><br/>';

if($country != 'az') echo '<a href="online-global.php">Global online ['.$globalOnline.']</a>';

// echo '<table width="100%"><tr>
// <td align="center"><a href="online.php?f_gender=all&amp;country='.$_country.'"><img src="img/users'; echo $f_gender=='all' || empty($f_gender) ? '-s' : '-s-g'; echo '.gif" alt="all" /></a></td>
// <td align="center"><a href="online.php?f_gender=men&amp;country='.$_country.'"><img src="img/man'; echo $f_gender=='men' ? '-s' : '-s-g'; echo '.gif" alt="Men" /></a></td>
// <td align="center"><a href="online.php?f_gender=women&amp;country='.$_country.'"><img src="img/woman'; echo $f_gender=='women' ? '-s' : '-s-g'; echo '.gif" alt="Women" /></a></td>
// </tr></table>';

if($f_gender=='all' or empty($f_gender)){
	echo 'Onlayn: <span style="font-weight:bold;font-size:14px;">'.$online_love.'</span> nefer<br/>';
}else{
	//echo 'Online: <a href="online.php?f_gender=all&amp;country='.$_country.'"><span style="font-weight:bold;font-size:14px;">'.$globalOnline.'</span></a> nefer<br/>';
}
if($f_gender=='men'){
	echo 'K: '.$online_love_sex_man.' | ';
}else{
	echo 'K: <a href="online.php?f_gender=men&amp;country='.$_country.'">'.$online_love_sex_man.'</a> | ';
}
if($f_gender=='women'){
	echo 'Q: '.$online_love_sex_woman.'<br/>';
}else{
	echo 'Q: <a href="online.php?f_gender=women&amp;country='.$_country.'">'.$online_love_sex_woman.'</a><br/>';
}
echo '<br/>';

echo '<a class="button blue" href="messages.php?mod=unread">'.$__lng['mesajlar'].' ['.$count_unread.']</a> ';
echo '<a class="button blue" href="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;refresh='.rand(11111,99999).'">'.$__lng['yenile'].'</a><br/>';
echo '<br/>';

$unread_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`conversation_reply` WHERE `user_id_to` = '".$id."' AND `read` = 0");
$count_unread = mysql_result($unread_count, 0);

if($count_unread > 0) echo '<a href="messages.php?mod=unread"><img src="img/message.png" alt="." /> '.$count_unread.'</a> '.$__lng['mesajin var'].'<br/><br/>';

if($country == 'az') echo '* <a href="pointserv.php?mod=onlinerating">'.$__lng['irelide gorun'].'</a><br/><br/>';

echo '<table cellpadding="2">';

if($f_gender == 'men') $all_rows = $online_love_sex_man; else if($f_gender == 'women') $all_rows = $online_love_sex_woman; else $all_rows = $online_love;

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

//$query = mysql_query("SELECT `id`,`nickname`,`rnickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `point`, `only_friend`,`user_status` FROM `aloaz_db`.`user` WHERE `last_activity` > '".(time()-600)."' AND `invisible`=0 AND `no_dating` = 1 $ins_sex $sex_m_w_sql $ins_country $order_sql LIMIT ".$start.", ".$show_limit.";");
$query = mysql_query("SELECT `id`,`nickname`,`rnickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `point`, `only_friend`,`user_status` FROM `aloaz_db`.`user` WHERE `last_activity` > '".(time()-11600)."' AND `invisible`=0 AND `no_dating` = 1 $ins_sex $sex_m_w_sql $ins_country $order_sql LIMIT ".$start.", ".$show_limit.";");
echo mysql_error();


while($row = mysql_fetch_array($query)){
	$onuser_id = $row['id'];
	$onuser_age = $row['age'];
	$onuser_sex = $row['sex'];
	$onuser_login = $row['nickname'];

	if($row["rnickname"]!=""){
		$onuser_login = '<img src="rn/tmp/'.$row["rnickname"].'" style="vertical-align:middle" alt="'.$row["nickname"].'"/>';
	}
	
	$onuser_name = $row['full_name'];
	$onuser_user_status = $row['user_status'];
	$onuser_photo = $row['profile_photo'];
	$onuser_status = htmlspecialchars(stripslashes($row['last_post']));
	$onuser_point = $row['point'];
	$onuser_friend = $row['only_friend'];
	
	$vip_img = '';
	if($onuser_user_status>0){
		if($onuser_user_status==1) $vip_img = '<img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" >';
		elseif($onuser_user_status==2) $vip_img = '<img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" >';
		elseif($onuser_user_status==3) $vip_img = '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" >';
	} 
	if($onuser_friend == 1) $lock_img = '<img src="img/lock.png" alt="." style="float:right; padding-right: 10px" />'; else $lock_img = '';
	
	if(strlen($onuser_status) > 50) $onuser_status = mb_substr($onuser_status,0,50,"utf-8"); 
	
	$expPhoto = explode('|', $onuser_photo);
	$photoName = $expPhoto[0];
	$photoId = $expPhoto[1];

	if($onuser_sex==0){
		$onuser_sex_=$__lng['k'];
		$onuser_sex_img ='man';
	}
	else{
		$onuser_sex_=$__lng['q'];
		$onuser_sex_img='woman';
	}
 
	
	if(empty($onuser_photo)) $img_file = 'img/'.$onuser_sex_img.'.gif';
	else $img_file = 'udata'.$onuser_photo;
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td><a href="profile.php?uid='.$onuser_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a></td>
	<td width="300px"><a href="profile.php?uid='.$onuser_id.'&amp;back=online">'.$onuser_login.'</a> <span style="font-size:11px">('; 
	echo $onuser_sex_.'/'; 
	echo ''.$onuser_age.') '.$vip_img.$lock_img.'<br/>'.$onuser_status.'</span><br/>';
	if($onuser_point > 0) echo '<span style="font-size:11px; color: green;">+ '.$onuser_point.' '.$__lng['xal'].'</span>';
	echo '</td></tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;page='.$i.'">'.$i.'</a> ';
			}
			else{
				echo ' <span>'.$i.'</span> ';
			}
		}
		
	}
}
if($page <= $max - $interval) echo '... ';

if($max > $interval){
	if($max != $page){
		echo ' <a href="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="online.php?f_gender='.$f_gender.'&amp;country='.$_country.'&amp;page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

echo '</div>';
include 'inc/footer.php';
?>
