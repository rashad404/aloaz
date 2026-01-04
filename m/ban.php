<?
error_reporting(0);
session_start();

//exit;
include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/lang/pack.php';
$title = 'Alochat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="ban.php">'.$__lng['ban siyahisi'].'</a></div>';
echo '<div class="layer">';
$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=block">'.$__lng['giris'].'</a> | <a href="reg.php?loc=block">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$user_status = $userrow['user_status'];
if(intval($user_status) == 0){
	displayError($__lng['vip istifadeciler daxil ola biler'].'<br/>'.
	'<a href="pointserv.php?mod=vipuser">'.$__lng['vip user ol'].'</a>', 2);
}

$_b_login = checkData($_POST['b_login']);
if(!empty($_b_login)){
	$query_login_ban = mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `nickname` = '".$_b_login."' and `user_status`!=10");
	$login_ban_id = mysql_result($query_login_ban, 0, 0);
}

if($login_ban_id > 0) $_uid = $login_ban_id; else $_uid = checkData($_GET['uid']);

$mod = checkData($_GET['mod']);
switch($mod){
	
default:

echo $__lng['ban olanlar'].':<br/><br/>';

$_active_bans = intval($_GET['active']);

if($_active_bans == 1){
	$sql_where = "(`end_time` > '".time()."' or `begin_time`=0) and `ended`=0";
	echo '<a href="?active=0">Bütün</a> | Aktiv banlar<br/>';
}
else{
	$sql_where = "`id` > 0";
	echo 'Bütün | <a href="?active=1">Aktiv banlar</a><br/>';
	
}
echo '<br/>';

$all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`blocks` WHERE ".$sql_where."");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT * FROM `aloaz_db`.`blocks` WHERE ".$sql_where." ORDER BY `blocked_time` DESC LIMIT ".$start.", ".$show_limit.";");

if(mysql_num_rows($query) == 0){
	echo $__lng['ban yoxdur'].'<br/>';
	echo '</div>';
	break;
}

$ban_reasons = getReasons();

while($row = mysql_fetch_array($query)){
	$uid = $row['user_id'];
	$from_id = $row['from_id'];
	$end_time = $row['end_time'];
	$begin_time = $row['begin_time'];
	$ended = $row['ended'];
	$minute = round($row["time"]/60);
	
	$userQuery = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = '".$uid."'");
	$uidLogin = mysql_result($userQuery, 0);
	
	$fromUserQuery = mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id` = '".$from_id."'");
	$fromLogin = mysql_result($fromUserQuery, 0);
	
	$remove_text = '';
	
	if($user_status>2 && $ended == 0 && ($end_time > time() or $begin_time == 0)) $remove_text = ' - <a href="ban.php?mod=del&amp;uid='.$uid.'">'.$__lng['ban legvi'].'</a>';
	echo '<a href="profile.php?uid='.$uid.'">'.$uidLogin.'</a> (<span style="font-size: small">'.$minute.' dəq</span>) '.$remove_text.' <br/>';

	$today_day = date("d");
	$yesterday_day = $today_day - 1;
	$today_month = date("m");
	$today_year = date("Y");
	
	echo '<span style="font-size: 14px">';
	if($today_day == date("d",$row["blocked_time"]) and $today_month == date("m",$row["blocked_time"]) and $today_year == date("Y",$row["blocked_time"])){
		$ban_date = $__lng["bugun"]." ".date("H:i",$row["blocked_time"]);
	}elseif($yesterday_day == date("d",$row["blocked_time"]) and $today_month == date("m",$row["blocked_time"]) and $today_year == date("Y",$row["blocked_time"]))
	{
		$ban_date = $__lng["dunen"]." ".date("H:i",$row["blocked_time"]);
	}else{
		$ban_date = date("d-m-Y H:i",$row["blocked_time"]);
	}
	echo '<b>Ban edən:</b> '.$fromLogin.'<br/>';
	echo "<b>Tarix:</b> ".$ban_date." | <b>Səbəb:</b> ".$ban_reasons[$row["reason"]]."<br /><b>Açıqlama:</b> ".$row["description"]."<br /><br />";
	
	echo '</span>';
}

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="ban.php?page='.($page-1).'&amp;active='.$_active_bans.'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="ban.php?page=1&amp;active='.$_active_bans.'">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="ban.php?page='.$i.'&amp;active='.$_active_bans.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="ban.php?page='.$i.'&amp;active='.$_active_bans.'">'.$i.'</a> ';
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
		echo ' <a href="ban.php?page='.$max.'&amp;active='.$_active_bans.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="ban.php?page='.($page+1).'&amp;active='.$_active_bans.'">&gt;</a> ';

echo '</div><br/>';

break;


case 'request':


$checkUserQuery = mysql_query("SELECT `id`,`user_status` FROM `aloaz_db`.`user` WHERE `id` = '".$_uid."';");
if(mysql_num_rows($checkUserQuery) == 0){
	echo $__lng['istifadeci tapilmadi'];
	break;
}
$ban_user = mysql_fetch_assoc($checkUserQuery);

if($user_status<10 and $ban_user["user_status"]>0 and $id!=1129446){
	echo $__lng['ban ede bilmezsiniz'];
	break;
}
$checkBlockQuery = mysql_query("SELECT `end_time` FROM `aloaz_db`.`blocks` WHERE (`end_time` > '".time()."' or `begin_time`=0) and `user_id` = '".$_uid."' and `ended`=0;");
if(mysql_num_rows($checkBlockQuery) > 0 AND $user_status!=10){
	echo $__lng['bu istifadeci bandadir'];
	break;
}

$block_confirm = intval($_POST['block_confirm']);
if($block_confirm == 0){
	echo $__lng['ban etmeye eminsiniz'].'<br/><br/>';
	if($user_status==1){
		$max_ban_time = 15;
	}elseif($user_status==2){
		$max_ban_time = 30;
	}elseif($user_status==3){
		$max_ban_time = 60;
	}elseif($user_status==10){
		$max_ban_time = 60;
	}
	?>
	
	<form action="ban.php?mod=request&uid=<?= $_uid; ?>" method="post">
		Ban vaxtı: 
		<?php if(intval($user_status)==10){?>		
		<select name="time">
			<?php for($i=5;$i<=$max_ban_time;$i= $i+5): ?>
			<option value="<?= ($i*60)?>"><?= $i?> dəq</option>
			<?php endfor; ?>
			<option value="86400"> 1 gün</option>
			<option value="604800"> 1 hefte</option>
			<option value="2592000"> 1 ay</option>
			<option value="31536000"> 1 il</option>
		</select>
		<?php }else{?>
		<select name="time">
			<?php foreach($paramsArray["user_status_ban_array_".$user_status] as $key=>$value): ?>
			<option value="<?= $key?>"><?= $value?> </option>
			<?php endforeach; ?>
		</select>
		<?php } ?>
		<br /><br />
		<?= $__lng["sebeb"]; ?>: <select name="reason">
			<?php 
				$reasons = getReasons();
				foreach($reasons as $key=>$reason){
					echo '<option value="'.$key.'">'.$reason.'</option>';
				}
			?> 
		</select>
		<br /><br />
		Açıqlama: <input type="text" name="description">
		<input type="hidden" name="block_confirm" value="1">
		<br />
		<br />
		<input type="submit" name="submit" value="Təsdiqlə">
	</form>
	<?php
	//echo $__lng['beli eminem'].'. <a href="ban.php?mod=request&amp;uid='.$_uid.'&amp;block_confirm=1">'.$__lng['tesdiqle'].'</a><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

$block_time = intval($_POST["time"]); // 15 deq
if(($block_time>$paramsArray["user_status_max_ban_time_".$user_status]) and $user_status!=10){
	echo "Düzgün daxil edin!!!-1";
}else{
	$block_reason = htmlspecialchars($_POST["reason"]);
	$block_description = htmlspecialchars($_POST["description"]);
	$block_ip = htmlspecialchars(getenv('REMOTE_ADDR'));
	$block_ua = htmlspecialchars(getenv('HTTP_USER_AGENT'));
	mysql_query("INSERT INTO `aloaz_db`.`blocks` SET `from_id` = '".$id."', `user_id` = '".$_uid."', `time`='".$block_time."',`blocked_time` = '".time()."',`ip`='".$block_ip."',`ua`='".$block_ua."',`reason`='".$block_reason."',`description`='".$block_description."';");
	mysql_query("UPDATE `aloaz_db`.`user` SET `block_time`='".$block_time."',`block_begin_time`=0 WHERE id='".$_uid."' LIMIT 1");
	if(mysql_affected_rows()>0){
		echo $__lng['ban oldu'].'<br/>';
	}
	else{
		echo 'Database error<br/>';
	}
}


break;


case 'del':

if(intval($user_status)<3){
	echo $__lng['ban aca bilmezsiniz'].'<br/><br/>';
 	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

$del_confirm = intval($_GET['del_confirm']);
if($del_confirm == 0){
	echo $__lng['ban legvine eminsiniz'].'<br/><br/>';
	echo $__lng['beli eminem'].'. <a href="ban.php?mod=del&amp;uid='.$_uid.'&amp;del_confirm=1">'.$__lng['qadaga legvi'].'</a><br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}
$checkBlockQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`blocks` WHERE (`end_time` > '".time()."' or `begin_time`=0) and `ended`=0 and `user_id` = '".$_uid."' ORDER BY `id` DESC;");
if(mysql_num_rows($checkBlockQuery) == 0){
	echo $__lng['qadaga qoyulmayib'].'.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
	break;
}
$blocked_row = mysql_fetch_assoc($checkBlockQuery);
mysql_query("UPDATE `aloaz_db`.`blocks` SET ended=1 WHERE id='".$blocked_row["id"]."' limit 1;");

if(mysql_affected_rows()>0){
		mysql_query("UPDATE `aloaz_db`.`user` SET `block_time`=0,`block_begin_time`=0 WHERE `id`='".$_uid."' limit 1");
	echo $__lng['qadaga legv olundu'].'.<br/><a href="ban.php">« '.$__lng["ban olanlar"].'</a><br/>';
}
else{
	echo 'Database error<br/>';
}

break;

}

echo '</div>';
include 'inc/footer.php';

?>
