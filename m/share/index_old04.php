<?
session_start();

include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/lang/pack.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/smiles.php';

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'.png" alt="'.$key.'" />';
}

$title = 'AloChat - '.$__lng['paylash'];
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj, Paylaş';
$meta_description = 'AloChat üzvlerinin paylaşdıqları en maraqlı şekil ve yazılar. Sen de maraqlı anları bizimle paylaş!';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_filter = checkData($_GET['filter']);
$_uid = intval($_GET['uid']);
$_country = strtolower(checkData($_GET['country']));

echo '<div class="mnav"><a href="/main.php">AloChat</a> » <a href="index.php?filter='.$_filter.'&amp;uid='.$_uid.'">'.$__lng['paylash'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `nickname`, `country`');
if($checkAuth != 'error'){
	$userrow = mysql_fetch_array($checkAuth);
	$id = $userrow['id'];
	$country = strtolower($userrow['country']);
	
	//$ins_country = " AND `country` = '".$country."'";
}

//if(strlen($_country) == 2) $ins_country = " AND `country` = '".$_country."'";

if($_filter == '' && $_uid == 0) $_filter = 'all';

$friendsQuery = mysql_query("SELECT `id`, `uid`, `ok1`, `ok2` FROM `chat_friends` WHERE (`id` = ".$id." OR `uid` = ".$id.") AND `ok1` = '1' AND `ok2` = '1' ORDER BY `id` ASC LIMIT 100");
while($friendsRow = mysql_fetch_array($friendsQuery)){
	$friends_id = $friendsRow['id'];
	$friends_uid = $friendsRow['uid'];
	if($friends_id != $id) $f_uid = $friends_id; else $f_uid = $friends_uid;
	
	$ins_fuid .= " OR `user_id` = '".$f_uid."'";
}
$ins_fuid = substr($ins_fuid, 4);

if($_filter == 'all'){
	if($ins_fuid == '') $ins_filter = " `permission` = '0'";
	else $ins_filter = " `permission` = '0' OR (".$ins_fuid.")";
}
if($_filter == 'friends'){

	$ins_filter = $ins_fuid;
}
if($_filter == 'my'){
	$ins_filter = " `user_id` = '".$id."' ";
}

if($_uid > 0){
	$checkFriendQuery = mysql_query("SELECT `uid` FROM `chat_friends` WHERE (`id` = '".$id."' AND `uid` = '".$_uid."') OR (`id` = '".$_uid."' AND `uid` = '".$id."');");
	if(mysql_num_rows($checkFriendQuery) == 0) $checkFriend = false; else $checkFriend = true;
	if(!$checkFriend && $_uid != $id) $ins_permission = " AND `permission` = '0'";
	$ins_filter = " `user_id` = '".$_uid."' ".$ins_permission."";
}

if($_filter == 'all') $file_count = mysql_query("SELECT COUNT(DISTINCT(`user_id`)) FROM aloaz_db.`share` WHERE status=1 and ".$ins_filter." ".$ins_country.";");
else $file_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`share` WHERE status=1 and ".$ins_filter.";");
$all_rows = mysql_result($file_count, 0);

// DELETE A SHARE
$_del = intval($_GET['del']);
$_sid = intval($_GET['sid']);

if($_del == 1){
	echo '<div class="notif" align="center">';
	echo 'Silmek istediyinize eminsiniz?<br/>';
	echo '<a href="?filter='.$_filter.'&amp;uid='.$_uid.'&amp;page='.intval($_GET['page']).'">'.$__lng['xeyr'].'</a> / ';
	echo '<a href="?filter='.$_filter.'&amp;uid='.$_uid.'&amp;page='.intval($_GET['page']).'&amp;del=2&amp;sid='.$_sid.'">'.$__lng['beli'].'</a><br/>';
	echo '</div>';
}
if($_del == 2){
	$query = mysql_query("SELECT `attach`, `time` FROM aloaz_db.`share` WHERE `id` = '".$_sid."';");
	$row = mysql_fetch_array($query);
	$attach = $row['attach'];
	$date = $row['time'];
	
	mysql_query("DELETE FROM aloaz_db.`share` WHERE `id` = '".$_sid."' AND `user_id` = '".$id."' LIMIT 1;");
	if(mysql_affected_rows() > 0){
		mysql_query("DELETE FROM aloaz_db.`share_comment` WHERE `sid` = '".$_sid."';");
		mysql_query("DELETE FROM aloaz_db.`share_like` WHERE `sid` = '".$_sid."';");
		unlink('/home/admin/domains/alochat.com/public_html/images/share/uploads/'.date('Ym', $date).'/'.$attach.'');
		unlink('/home/admin/domains/alochat.com/public_html/images/share/thumbs/'.date('Ym', $date).'/'.$attach.'');
		unlink('/home/admin/domains/alochat.com/public_html/images/share/resized/'.date('Ym', $date).'/'.$attach.'');
	}
}

// Submit Like
$_like = intval($_GET['like']);
if($_like > 0 and intval($id)>0){
	$checkLikeQuery = mysql_query("SELECT `id` FROM aloaz_db.`share_like` WHERE `uid` = '".$id."' AND `sid` = '".$_like."';");
	if(mysql_num_rows($checkLikeQuery) == 0){
		mysql_query("INSERT INTO aloaz_db.`share_like` SET `uid` = '".$id."', `sid` = '".$_like."', `time` = '".time()."',`from` = 'alo_index'");
		mysql_query("UPDATE aloaz_db.`share` SET `like_count` = `like_count` + 1 WHERE `id` = '".$_like."'");
		$like_txt = ' <span style="color: green">'.$__lng['beyendiniz'].'</span>';
	}
}

if($country != 'az') echo '<a href="index-global.php">Global shares</a><br/><br/>';

echo '<form action="index.php" method="get" ><a href="add.php" class="button">+ '.$__lng['paylash'].'</a> ';
if($_uid == 0 && $id != ''){
echo '<select name="filter" style="height: 23px;" onchange="javascript: submit()">
<option '.($_filter == 'all' ? 'selected="selected"' : '').' value="all">'.$__lng['butun paylasilanlar'].'</option>
<option '.($_filter == 'friends' ? 'selected="selected"' : '').' value="friends">'.$__lng['dostlarin paylasdiqlari'].'</option>
<option '.($_filter == 'my' ? 'selected="selected"' : '').' value="my">'.$__lng['oz paylasdirqlarim'].'</option>
</select>';
}
echo '</form>';

if($all_rows == 0){
	echo '<br/>'.$__lng['paylasan olmayib'].'<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	echo '</div>';
	include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
	exit;
}

echo '<br/>';

$show_limit = 8;
if(isset($_GET['page'])) $page = checkData($_GET['page']);
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

if($_filter == 'all') $query = mysql_query("SELECT * FROM (
  SELECT *
  FROM aloaz_db.`share` WHERE status=1 and ".$ins_filter." ".$ins_country."
  ORDER BY time DESC
) AS share_list GROUP BY user_id ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
else $query = mysql_query("SELECT * FROM aloaz_db.`share` WHERE status=1 and ".$ins_filter." ".$ins_country." ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");

$i = 1;
while($row = mysql_fetch_array($query)){
	$shareId = $row['id'];
	$uid = $row['user_id'];
	$text = stripslashes($row['text']);
	$attach = $row['attach'];
	$permission = $row['permission'];
	$share_likes = $row['like_count'];
	$date = $row['time'];
	
	if($permission == 1) $permissionImg = '<img src="/img/share_friends_gray.gif" alt="'.$__lng['yalniz dostlarla paylasib'].'" />'; else $permissionImg = '<img src="/img/share_public_gray.gif" alt="'.$__lng['hamiyla paylasib'].'" />';
	
	if(date('d-m-Y', $date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $date);
	else if(date('d-m-Y', $date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $date);
	else $date_str = date('d-m-Y H:i', $date);
	
	if(strlen($text) > 200){
		$s = substr($text, 0, 200);
		$text = replaceLatin_E(substr($s, 0, strrpos($s, ' '))).' ...';
	}
	
	$text = str_replace("\n", '<br/>', $text);
	$text = str_replace(array_keys($smilesArray), array_values($smilesArray), $text);
	
	$count_comms = mysql_result(mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`share_comment` WHERE `sid` = '".$shareId."';"), 0);
	
	echo '<div class="content">';
	
	$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
	$u_login = mysql_result($u_query, 0);
	echo '<a href="/profile.php?uid='.$uid.'">'.$u_login.'</a> '.$__lng['paylashdi'];
	if($uid == $id) echo '<span style="float:right; padding-right: 8px;"><a href="?filter='.$_filter.'&amp;uid='.$uid.'&amp;page='.$page.'&amp;del=1&amp;sid='.$shareId.'">'.$__lng['sil'].'</a></span>';
	
	echo '<br/><small>'.$date_str.'</small> '.$permissionImg.'<br/>';
	
	if(!empty($attach)) echo '<a href="view.php?id='.$shareId.'"><img src="http://alochat.com/images/share/resized/'.date('Ym',$date).'/'.$attach.'" alt="." style="padding:5px 0 5px 0;" /></a><br/>';
	
	echo ''.$text.'<br/>';
	echo '<img src="/img/comment.png" alt="'.$__lng['sherhler'].'" style="vertical-align:middle;"/> '.$count_comms.' <a href="index.php?filter='.$_filter.'&amp;uid='.$_uid.'&amp;page='.intval($_GET['page']).'&amp;like='.$shareId.'"><img src="/img/like.png" alt="'.$__lng['beyen'].'" style="vertical-align:middle;"/></a> '.$share_likes.' '.($_like == $shareId ? $like_txt : '').' <a href="view.php?id='.$shareId.'">'.$__lng['etrafli'].'</a><br/>';
	echo '</div>';
}
echo mysql_error();
echo '<br/><div class="pageNav">';

if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

if($page > 1) echo '<a href ="?filter='.$_filter.'&amp;uid='.$_uid.'&amp;country='.$_country.'&amp;page='.($page-1).'">&lt;</a> ';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"?filter=".$_filter."&amp;uid=".$_uid."&amp;country=".$_country."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"?filter=".$_filter."&amp;uid=".$_uid."&amp;country=".$_country."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"?filter=".$_filter."&amp;uid=".$_uid."&amp;country=".$_country."&amp;page=".$i."\">".$i."</a> ";
			}
			else{
				echo ' <span>'.$i.'</span> ';
			}
		}
		
	}
}
if($page <= $max - 5) echo '... ';

if($max > $interval){
	if($max != $page){
		echo " <a href=\"index.php?filter=".$_filter."&amp;uid=".$_uid."&amp;country=".$_country."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " <span>".$max."</span> ";
	}
}

if($page < $max) echo '<a id="pageButon" href ="index.php?filter='.$_filter.'&amp;uid='.$_uid.'&amp;country='.$_country.'&amp;page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';


?>
