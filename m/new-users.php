<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="new-users.php">Yeni istifadəçilər</a></div>';
echo '<div class="layer">';

$admin_status = 0;
$checkAuth = checkAuth('`id`, `coins`,`user_status`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=toprating">'.$__lng['giris'].'</a> | <a href="reg.php?loc=toprating">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$coins = $userrow['coins'];
if($userrow["user_status"]==10)
	$admin_status = 1;


echo '<table width="100%" cellpadding="2" cellspacing="0">';

$order_by = ' `rating` '; 

$show_limit = 15;
$all_rows = mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `created_at` > '".strtotime(date('Y-m-d 00:00:00'))."'"));
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

if($admin_status == 1){
	$_del = intval($_GET['del']);
	$_user_id = intval($_GET['user_id']);

	if($_del == 1){
		echo '<div class="notif" align="center">';
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="new-users.php?page='.$page.'">'.$__lng['xeyr'].'</a> / ';
		echo '<a href="new-users.php?user_id='.$_user_id.'&amp;page='.$page.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
		echo '</div>';
	}
	if($_del == 2){
		$userrow = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `id`='".$_user_id."' LIMIT 1"));
		if($userrow["created_at"]>strtotime(date('Y-m-d 00:00:00'))){
			$delete = mysql_query("DELETE FROM `aloaz_db`.`user` WHERE `id` = '".$_user_id."' LIMIT 1;");

			if($delete){
				mysql_query("DELETE FROM `aloaz_db`.`share` WHERE `user_id` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`share_comment` WHERE `uid` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`share_like` WHERE `uid` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`conversation` WHERE `user_one` = '".$_user_id."' OR `user_two` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`conversation_reply` WHERE `user_id` = '".$_user_id."' OR `user_id_to` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`user_image` WHERE `user_id` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`user_image_resized` WHERE `user_id` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`user_image_thumb` WHERE `user_id` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`user_friend` WHERE `user_1` = '".$_user_id."' OR `user_2` = '".$_user_id."';");
				mysql_query("DELETE FROM `aloaz_db`.`chat_photos` WHERE `uid` = '".$_user_id."';");
				echo $__lng['silindi'].'!<br/><br/>';
			}
			else{
				echo 'Database error [6698]<br/>';
			}
		}else{
			echo "sile bilmezsiniz<br />";
		}
		//mysql_query("DELETE FROM aloaz_db.`news_comment` WHERE `id` = '".$_commentid."' AND `news_id` = '".$_news_id."' LIMIT 1;");
	}
}


echo 'Bugün qeyd olan istifadəçilər: '.$all_rows.' nəfər<br/><br/>';

$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day`,`rating` FROM `aloaz_db`.`user` WHERE `created_at` > '".strtotime(date('Y-m-d 00:00:00'))."' ORDER BY `created_at` DESC LIMIT ".$start.", ".$show_limit.";");

//$num = $start;
$num = $all_rows;
if($page > 1) $num = $all_rows - $show_limit*($page-1);

$num = $num + 1;

while($row = mysql_fetch_array($query)){
	$num--;
	
	$uid_id = $row['id']; 
	$uid_sex = $row['sex'];
	$uid_login = $row['nickname'];
	$uid_name = $row['full_name'];
	$uid_photo = $row['profile_photo'];
	$uid_status = $row['last_post'];
	$uid_post = $row['msg_count'];
	$uid_rating = $row["rating"];
	$uid_post_day = $row['msg_count_day'];
	$uid_age = $row["age"];
	$msg_count = $row["msg_count"];
	if(strlen($uid_status) > 50) $uid_status = substr($uid_status, 0, 50).'...';

	if($uid_sex==0){
		$uid_sex_='K';
		$uid_sex_img ='man';
	}
	else{
		$uid_sex_='Q';
		$uid_sex_img='woman';
	}

	if(empty($uid_photo)) $img_file = 'img/'.$uid_sex_img.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$uid_photo;
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; 
	echo '><td>'.$num.') <a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a> <span style="font-size: 13px">('.$uid_sex_.'/'.$uid_age.') Post: '.$msg_count.'</span>';
if($admin_status == 1) echo '<span style="float:right; padding-right: 8px;"><a href="new-users.php?page='.$page.'&amp;del=1&amp;user_id='.$uid_id.'">'.$__lng['sil'].'</a></span>';	
	echo '</td></tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="?period='.$_period.'&amp;page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="?period='.$_period.'&amp;page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="?period='.$_period.'&amp;page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="?period='.$_period.'&amp;page='.($page+1).'">&gt;</a> ';

echo '</div>';

echo '</div>';
include 'inc/footer.php';
?>