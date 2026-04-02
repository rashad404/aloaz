<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/lang/pack.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/smiles.php';

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'.png" alt="'.$key.'" />';
}

$_id = intval($_GET['id']);

$query = mysql_query("SELECT * FROM aloaz_db.`share` WHERE `id` = '".$_id."';");
$row = mysql_fetch_array($query);

$shareId = $row['id'];
$uid = $row['user_id'];
$text = replaceLatin_E(stripslashes($row['text']));
$read = $row['read_count'];
$attach = $row['attach'];
$permission = $row['permission'];
$date = $row['time'];

$shortText = $text;
if(strlen($shortText) > 100){
	$s = substr($shortText, 0, 100);
	$shortText = replaceLatin_E(substr($s, 0, strrpos($s, ' ')));
}

if(date('d-m-Y', $date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $date);
else if(date('d-m-Y', $date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $date);
else $date_str = date('d-m-Y H:i', $date);

$text = str_replace("\n", '<br/>', $text);

$title = $__lng['paylash'].' - '.$shortText.'';
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj, Paylaş';
$meta_description = 'AloChat - '.$__lng['paylash'].': '.$shortText.'';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="/main.php">AloChat</a> » <a href="index.php">'.$__lng['paylash'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth != 'error'){
	$userrow = mysql_fetch_array($checkAuth);
	$id = $userrow['id'];
}


// DELETING COMMENT
if($uid == $id){
	$_del = intval($_GET['del']);
	$_commentid = intval($_GET['commentid']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="view.php?id='.$_id.'&amp;page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="view.php?id='.$_id.'&amp;commentid='.$_commentid.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		mysql_query("DELETE FROM aloaz_db.`share_comment` WHERE `id` = '".$_commentid."' AND `sid` = '".$shareId."' LIMIT 1;");
	}
}

// Submit Like
$_like = intval($_GET['like']);
if($_like > 0){
	$checkLikeQuery = mysql_query("SELECT `id` FROM aloaz_db.`share_like` WHERE `uid` = '".$id."' AND `sid` = '".$_like."';");
	if(mysql_num_rows($checkLikeQuery) == 0){
		mysql_query("INSERT INTO aloaz_db.`share_like` SET `uid` = '".$id."', `sid` = '".$_like."', `time` = '".time()."'");
		mysql_query("UPDATE aloaz_db.`share` SET `like_count` = `like_count` + 1 WHERE `id` = '".$_like."'");
		$like_txt = ' <span style="color: green">'.$__lng['beyendiniz'].'</span>';
	}
}

if($permission == 1) $permissionImg = '<img src="/img/share_friends_gray.gif" alt="'.$__lng['yalniz dostlarla paylasib'].'" />'; else $permissionImg = '<img src="/img/share_public_gray.gif" alt="'.$__lng['hamiyla paylasib'].'" />';

$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
$u_login = mysql_result($u_query, 0);
echo '<a href="../profile.php?uid='.$uid.'">'.$u_login.'</a> '.$__lng['paylashdi'].'<br/>';
echo '<small>'.$date_str.'</small> '.$permissionImg.'<br/><br/>';

if(!empty($attach)){
	echo '<a href="https://m.alo.az/udata/images/share/uploads/'.date('Ym',$date).'/'.$attach.'"><img src="https://m.alo.az/udata/images/share/resized/'.date('Ym',$date).'/'.$attach.'" alt="." /></a><br/><br/>';
}

$text = str_replace(array_keys($smilesArray), array_values($smilesArray), $text);
echo $text.'<br/><br/>';

$count_com = mysql_result(mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`share_comment` WHERE `sid` = '".$shareId."';"), 0);
$count_likes = mysql_result(mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`share_like` WHERE `sid` = '".$shareId."';"), 0);

echo $__lng['baxilib'].': '.$read.'<br/>';

echo '<img src="/img/comment.png" alt="'.$__lng['sherhler'].'" style="vertical-align:middle;"/> <a href="comments.php?id='.$shareId.'">'.$count_com.'</a> <a href="?id='.$shareId.'&amp;like='.$shareId.'"><img src="/img/like.png" alt="'.$__lng['beyen'].'" style="vertical-align:middle;"/></a> '.$count_likes.' '.($_like == $shareId ? $like_txt : '').' <a href="addcomment.php?id='.$shareId.'">'.$__lng['sherh yaz'].'</a><br/><br/>';


$query = mysql_query("SELECT `id`, `comment`, `uid`, `time` FROM aloaz_db.`share_comment` WHERE `sid` = '".$_id."' ORDER BY `time` DESC LIMIT 5;");
if(mysql_num_rows($query) == 0){
	echo $__lng['sherh yazilmayib'].'<br/>';
}
while($row = mysql_fetch_array($query)){
	$comment_id = $row['id'];
	$comment = $row['comment'];
	$comment_uid = $row['uid'];
	$comment_date = $row['time'];
	
	$comment = str_replace(array_keys($smilesArray), array_values($smilesArray), $comment);
	
	$u_query = mysql_query("SELECT `nickname`, `sex`, `photo` FROM `chat_users` WHERE `id` = '".$comment_uid."';");
	$u_row = mysql_fetch_array($u_query);
	$c_login = $u_row['nickname'];
	$c_sex = $u_row['sex'];
	$c_photo = $u_row['photo'];
	if($c_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	
	$expPhoto = explode('|', $c_photo);
	$photoName = $expPhoto[0];
	$photoId = $expPhoto[1];
	
	if(empty($c_photo)) $img_file = '/img/'.$sex_icon.'.gif';
	else $img_file = '/photos/files/thumbs/small/'.$c_sex.'/'.$photoName.'';
	
	if(date('d-m-Y', $comment_date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $comment_date);
	else if(date('d-m-Y', $comment_date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $comment_date);
	else $date_str = date('d-m-Y H:i', $comment_date);
	
	echo '<a href="/profile.php?uid='.$comment_uid.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$c_login.'</a>';
	if($uid == $id) echo '<span style="float:right; padding-right: 8px;"><a href="view.php?id='.$_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small><br/>';
	echo $comment.'<br/><br/>';
}
if($count_com > 5) echo '<a href="comments.php?id='.$shareId.'">'.$__lng['butun sherhleri oxu'].'</a><br/>';

mysql_query("UPDATE aloaz_db.`share` SET `read_count` = `read_count` + 1 WHERE `id` = '".$_id."' LIMIT 1;");

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>

