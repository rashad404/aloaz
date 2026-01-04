<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bildirisler'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=guests">'.$__lng['giris'].'</a> | <a href="reg.php?loc=guests">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

$_del = intval($_GET['del']);
 
if($_del == 1){
	echo '<div class="notif" align="center">';
	echo $__lng['silmeye eminsiniz'].'<br/>';
	echo '<a href="notifications.php">'.$__lng['xeyr'].'</a> / ';
	echo '<a href="notifications.php?del=2">'.$__lng['beli'].'</a><br/>';
	echo '</div>';
}
if($_del == 2){
	mysql_query("DELETE FROM `aloaz_db`.`notification` WHERE `user_id` = '".$id."' AND `read` != 0;");
}


if(isset($_GET["p"]) and (intval($_GET["p"])==0 OR intval($_GET["p"])==1)) {
	$_COOKIE["not_read_filter"] = intval($_GET["p"]);
}else {
	if(!isset($_COOKIE["not_read_filter"]))
		$_COOKIE["not_read_filter"] = 0;
}

$not_read_filter =  $_COOKIE["not_read_filter"];
?>
<select class="form-control" onchange="location = this.options[this.selectedIndex].value;" style="height: auto !important;padding: 3px;width: 148px;">
	<option value="notifications.php?p=0" <?php if($not_read_filter==0) echo 'selected';else echo ''; ?>>Bütün bildirişlər</option>
	<option value="notifications.php?p=1" <?php if($not_read_filter==1) echo 'selected';else echo ''; ?>>Baxılmayanlar</option>
</select> <a href="notifications.php?p=<?=$not_read_filter;?>&amp;refresh=<?=rand(11111,99999);?>">Yenilə</a> | 
<a href="notifications.php?del=1">Sil</a>
<br />
<?php
$where = '';
 if(intval($not_read_filter)==1) $where = " AND `read`=0"; 


$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`notification` WHERE `user_id` = '".$id."' ".$where." ORDER BY `time` DESC");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`,`user_id`,`user_id_from`,`username`,`type`,`share_id`,`coin`,`read`,`time`,`image` FROM `aloaz_db`.`notification` WHERE `user_id` = '".$id."' ".$where." ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");
if(mysql_num_rows($query) == 0){
	echo '<br/>Baxılmamış bildirişiniz yoxdur<br/><br/>';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}
 
echo '<table width="100%" cellpadding="3" cellspacing="0" style="margin-top: 10px">';
while($row = mysql_fetch_assoc($query)){
  // notifications
	
	$text = '';
	$link = '';
	if($row["type"]==$paramsArray["NOT_ALOCHAT_COIN"]){
		$text = 'Alochat sizə <a href="pointserv.php?mod=logs">bal</a> hədiyyə etdi';
		//$link = Url::to(["/coins"."?ref=notification"]);
	}elseif($row["type"] == $paramsArray["NOT_SHARE_COMMENT"]){
		$text = 'Sizin <a href="share/view.php?id='.$row["share_id"].'">paylaşıma</a> rəy bildirdi';
		//$link = Url::to(["/profile/post/".$row["share_id"]."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_SHARE_LIKE"]){
		$text = 'Sizin <a href="share/view.php?id='.$row["share_id"].'">paylaşımı</a> bəyəndi';
		
		//$link = Url::to(["/profile/post/".$row["share_id"]."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_COIN"]){
		$text =' sizə '.$row["coin"].'<a href="pointserv.php?mod=logs">bal</a> hədiyyə etdi';
		//$link = Url::to(["/coins"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_FRIEND"]){
		$text = ' sizə <a href="friends.php">dostluq</a> göndərdi';
		//$link = Url::to(["/profile/friend"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_FRIEND_REQUEST_CONFIRM"]){
		$text = ' sizin <a href="friends.php">dostluq təklifinizi</a> qəbul etdi';
		//$link = Url::to(["/u/".$row["user_id_from"]."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_FRIEND_REQUEST_REMOVE"]){
		$text = ' sizin <a href="friends.php">dostluq</a> sorğunuzu sildi';
		//$link = Url::to(["/u/".$row["user_id_from"]."?ref=notification"]);


	}elseif($row["type"] == $paramsArray["NOT_USER_GIFT"]){
		$text = ' sizə hədiyyə göndərdi';
		//$link = Url::to(["/gift/".$row["user_id"]."?ref=notification"]);


	}elseif($row["type"] == $paramsArray["NOT_USER_LIKE"]){
		$text = ' sizin <a href="profile.php?uid='.$id.'">profili</a> bəyəndi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_VISIT"]){
		$text = ' sizin <a href="guests.php">profili</a> ziyarət etdi';
		//$link = Url::to(["/profile/visitors"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_IMAGE_COMMENT"]){
		$text = ' sizin şəklinizə rəy bildirdi';
		//$link = Url::to(["/profile/image/".$notification["share_id"]."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_IMAGE_LIKE"]){
		$text = ' sizin şəklinizi bəyəndi';
		//$link = Url::to(["/profile/image/".$notification["share_id"]."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_STATUS1"]){
		$text = ' sizə <a href="team-panel.php">VIP vezifesi</a> verdi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_STATUS2"]){
		$text = ' sizə <a href="team-panel.php">MODER vezifesi</a> verdi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_STATUS3"]){
		$text = ' sizə <a href="team-panel.php">BOSS vezifesi verdi</a> verdi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);

	}elseif($row["type"] == $paramsArray["NOT_USER_ACTIVITY1"]){
		$text = ' sizə <a href="topactivity.php">Heftelik Aktivlik reytinqinde</a> qalib geldiyinize göre (1-ci yer) hediyye ballar verildi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);
	}elseif($row["type"] == $paramsArray["NOT_USER_ACTIVITY2"]){
		$text = ' sizə <a href="topactivity.php">Heftelik Aktivlik reytinqinde</a> qalib geldiyinize göre (2-ci yer) hediyye ballar verildi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);
	}elseif($row["type"] == $paramsArray["NOT_USER_ACTIVITY3"]){
		$text = ' sizə <a href="topactivity.php">Heftelik Aktivlik reytinqinde</a> qalib geldiyinize göre (3-ci yer) hediyye ballar verildi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);
	}elseif($row["type"] == $paramsArray["NOT_USER_ACTIVITY4"]){
		$text = ' sizə <a href="topactivity.php">Heftelik Aktivlik reytinqinde</a> qalib geldiyinize göre (4-ci yer) hediyye ballar verildi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);
	}elseif($row["type"] == $paramsArray["NOT_USER_ACTIVITY5"]){
		$text = ' sizə <a href="topactivity.php">Heftelik Aktivlik reytinqinde</a> qalib geldiyinize göre (5-ci yer) hediyye ballar verildi';
		//$link = Url::to(["/profile/like"."?ref=notification"]);
	}
	elseif($row["type"] == $paramsArray["NOTE_ONLINE_MESSAGE_LIKE"]){
		$text = 'Sizin <a href="online_message.php?mod=likes&amp;id='.$row["share_id"].'">onlayn mesajı</a> beyendi';
	}elseif($row["type"] == $paramsArray["NOTE_ONLINE_MESSAGE_COMMENT"]){
		$text = 'Sizin <a href="online_message.php?mod=read&amp;id='.$row["share_id"].'">onlayn mesaja</a> rey bildirildi';
	}
	else{
		$text = 'bilidiriş';
	}
	
	// notifications
 	$n_id = $row['id'];
	$user_id_from = $row['user_id_from'];
	$user = mysql_fetch_array(mysql_query('SELECT `nickname`, `sex`, `profile_photo` FROM `aloaz_db`.`user` WHERE `id`='.$user_id_from));
	$user_notif = $user["nickname"];
	$user_n_sex = $user['sex'];
	$user_profile_photo = $user['profile_photo'];
	
	$view = $row['seen'];
	$time = $row['time']; 
	
	if($user_n_sex==0){
		$user_n_sex_ = 'K';
		$user_n_sex_img ='man';
	}
	else{
		$user_n_sex_ = 'Q';
		$user_n_sex_img='woman';
	}
	
	if(empty($user_profile_photo)) $img_file = 'img/'.$user_n_sex_img.'.gif';
	else $img_file = 'udata'.$user_profile_photo;
    $img_file = 'udata'.$user_profile_photo;
	
	if($row["read"] == 0){
		mysql_query("UPDATE `aloaz_db`.`notification` SET `read` = '2' WHERE `id` = ".$n_id." LIMIT 1");
		$btag_open = '<b>';
		$btag_close = '</b>';
	}
	else{
		$btag_open = '';
		$btag_close = '';
	}
 	//echo '<a href="profile.php?uid='.$user_id_from.'">'.$user_notif.'</a><br/>';
	//echo '<div id="small-size">'.$btag_open.''.date('d-m-Y H:i', $time).'<br/>'.$text.''.$btag_close.'</div><br/>';
	
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td width="1%"><a href="profile.php?uid='.$user_id_from.'&amp;back=notifications"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px; height:60px;" /></a></td>';
	echo '<td width="80%" style="line-height: 5px"><a href="profile.php?uid='.$user_id_from.'">'.$user_notif.'</a><br/>'; 
	echo '<div id="small-size" style="padding-top: 10px; line-height: 15px">'.$btag_open.''.date('d-m-Y H:i', $time).''.$btag_close.'<br/>'.$text.'</div><br/>';
	echo '</td>';
	echo '</tr>';
}
echo '</table>';
echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="notifications.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="notifications.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="notifications.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="notifications.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="notifications.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="notifications.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

if(intval(date('H')) == 10 && intval(date('i')) < 10){
	mysql_query("DELETE FROM `aloaz_db`.`user_visit` WHERE `time` < '".(time()-3600*24*4)."'");
}

echo '</div>';
include 'inc/footer.php';
?>
