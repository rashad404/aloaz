<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/inc/func_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/lang/pack.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/smiles.php';

foreach ($smilesArray as $key => $value) {
	$smilesArray[$key] = '<img src="/img/smiles/'.$value.'" alt="'.$key.'" />';
}

$title = $__lng['paylash'];
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);

$checkAuth = checkAuth('`id`, `nickname`');
if($checkAuth != 'error'){
	$userrow = mysql_fetch_array($checkAuth);
	$id = $userrow['id'];
}

$query = mysql_query("SELECT `id`, `user_id` FROM aloaz_db.`share` WHERE `id` = '".$_id."';");
$share_id = mysql_result($query, 0, 'id');
$share_uid = mysql_result($query, 0, 'user_id');

echo '<div class="mnav"><a href="/index.php">AloChat</a> » <a href="index.php">'.$__lng['paylash'].'</a> » '.$__lng['sherhler'].'</div>';
echo '<div class="layer">';

// DELETING COMMENT
if($share_uid == $id){
	$_del = intval($_GET['del']);
	$_commentid = intval($_GET['commentid']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="comments.php?id='.$_id.'&amp;page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="comments.php?id='.$_id.'&amp;commentid='.$_commentid.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		mysql_query("DELETE FROM aloaz_db.`share_comment` WHERE `id` = '".$_commentid."' AND `sid` = '".$share_id."' LIMIT 1;");
	}
}

echo '<a href="view.php?id='.$_id.'">« '.$__lng['geri'].'</a><br/><br/>';

$file_count = mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`share_comment` WHERE `sid` = '".$_id."';");
$all_rows = mysql_result($file_count, 0);

$show_limit = 5;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `comment`, `uid`, `time` FROM aloaz_db.`share_comment` WHERE `sid` = '".$_id."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
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
	else $img_file = 'http://alochat.com'.$u_photo;
	
	if(date('d-m-Y', $comment_date) == date('d-m-Y')) $date_str = $__lng['bugun'].' '.date('H:i', $comment_date);
	else if(date('d-m-Y', $comment_date) == date('d-m-Y', strtotime('-1 day'))) $date_str = $__lng['dunen'].' '.date('H:i', $comment_date);
	else $date_str = date('d-m-Y H:i', $comment_date);
	
	echo '<a href="/profile.php?uid='.$comment_uid.'"><img src="'.$img_file.'" alt="." width="30" height="35" style="border: 1px solid #d7d7d7; vertical-align:middle"/> '.$u_login.'</a>';
	if($share_uid == $id) echo '<span style="float:right; padding-right: 8px;"><a href="comments.php?id='.$_id.'&amp;page='.$page.'&amp;del=1&amp;commentid='.$comment_id.'">'.$__lng['sil'].'</a></span>';
	echo ' <small>'.$date_str.'</small><br/>';
	echo $comment.'<br/><br/>';
}

echo '<br/><div class="pageNav">';

if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

if($page > 1) echo '<a href ="?id='.$_id.'&amp;page='.($page-1).'">&lt;</a> ';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"?id=".$_id."&amp;page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
		}
		else{
			echo " <span>".$i."</span> ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"?id=".$_id."&amp;page=".$i."\">".$i."</a> ";
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
		echo " <a href=\"?id=".$_id."&amp;page=".$max."\">".$max."</a> ";
	}
	else{
		echo " <span>".$max."</span> ";
	}
}

if($page < $max) echo '<a id="pageButon" href ="?id='.$_id.'&amp;page='.($page+1).'">&gt;</a> ';
echo '</div><br/>';

echo '<br/><a href="addcomment.php?id='.$_id.'">'.$__lng['sherh yaz'].'</a><br/>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
