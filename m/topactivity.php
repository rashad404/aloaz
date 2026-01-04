<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$_period = checkData($_GET['period']);
$_gender = checkData($_GET['gender']);

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Top aktiv istifadeçiler</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('id');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=topactivity">'.$__lng['giris'].'</a> | <a href="reg.php?loc=topactivity">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
if(isset($_period)) $period_link = '&period='.$_period;


if($_period == 'week'){
	$order_by = ' `weekly_activity` ';
	$field = 'weekly_activity';
	
	echo '<a href="topactivity.php?period=day">Günlük</a> / ';
	echo ' Heftelik / ';
	echo '<a href="topactivity.php?period=month"> Aylıq </a> <br/>';
	
}elseif($_period == 'month'){
	$order_by = ' `monthly_activity` ';
	$field = 'monthly_activity';
	
	echo '<a href="topactivity.php?period=day">Günlük</a> / ';
	echo '<a href="topactivity.php?period=week">Heftelik</a> / ';
	echo ' Aylıq <br/>';

}
else{
	$order_by = ' `daily_activity` ';
	$field = 'daily_activity';

	echo 'Günlük / ';
	echo '<a href="topactivity.php?period=week"> Heftelik </a> / ';
	echo '<a href="topactivity.php?period=month"> Aylıq </a><br/>';
}
?>
Sıralama: <select name="forma" onchange="location = this.value;">
 <option value="<?='topactivity.php?gender=all'.$period_link; ?>" <?=$_gender=='all'?'selected="selected"':'' ?>>Ümumi</option>
 <option value="<?='topactivity.php?gender=woman'.$period_link; ?>" <?=$_gender=='woman'?'selected="selected"':'' ?>>Qızlar</option>
 <option value="<?='topactivity.php?gender=man'.$period_link; ?>" <?=$_gender=='man'?'selected="selected"':'' ?>>Oğlanlar</option> 
</select><br /><br />
<?php 
echo '
QEYD: Her heftenin bazar günü saat 23:59 -da hefte üzre ilk yeri tutan 5 kişi ve 5 qadın aktiv istifadeçi bal (1-ci yer: 100, 2-ci yer: 80, 3-cü yer: 60, 4 ve 5-ci yer 50 bal) qazanacaq. Aktiv olun qazanın!<br /><br />';
$where = '';
if($_gender=='man'){
	$where = 'WHERE sex=0';
}elseif($_gender=='woman'){
	$where = 'WHERE sex=1';
}
	
echo '<table width="100%" cellpadding="2">';

$show_limit = 10;
$all_rows = mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE ".$order_by." ORDER BY ".$order_by." DESC"));
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_post`, `msg_count`, `msg_count_day`,".$order_by." FROM `aloaz_db`.`user` ".$where." ORDER BY ".$order_by." DESC LIMIT ".$start.", ".$show_limit.";");

$num = $start;

while($row = mysql_fetch_array($query)){
	$num++;
	
	$uid_id = $row['id']; 
	$uid_sex = $row['sex'];
	$uid_login = $row['nickname'];
	$uid_name = $row['full_name'];
	$uid_photo = $row['profile_photo'];
	$uid_status = htmlspecialchars($row['last_post']);
	$uid_activity = '';
	
	$timedate = timeToDate($row[$field]);
	if($timedate["d"]>0){ 
		$uid_activity.= $timedate["d"]." gün, ";
	}
	if($timedate["H"]>0){ 
		$uid_activity.= $timedate["H"]." saat, ";
	}
	$uid_activity.= $timedate["i"]." deq, ".$timedate["s"]." san ";
	$uid_age = $row["age"];
	if(strlen($uid_status) > 50) $uid_status = substr($uid_status, 0, 50).'...';

	if($uid_sex==0){
		$uid_sex_='K';
		$uid_sex_img ='man';
	}
	else{
		$uid_sex_='Q';
		$uid_sex_img='woman';
	}

	//$uid_age = floor( (strtotime(date('Y-m-d')) - strtotime("$uid_birth_year-$uid_birth_month-$uid_birth_day")) / 31556926);

	if($num == 1) $num_bgcolor = '#f93904'; 
	else if($num == 2) $num_bgcolor = '#ed552c'; 
	else if($num == 3) $num_bgcolor = '#e3856b'; 
	else  $num_bgcolor = '#818181';
	
	if(empty($uid_photo)) $img_file = 'img/'.$uid_sex_img.'.gif';
	else $img_file = 'udata'.$uid_photo;
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td style="text-align: center; color: #fff; background: '.$num_bgcolor.';">'.$num.'</td><td><a href="profile.php?uid='.$uid_id.'"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a></td>
	<td width="100%" style="line-height: 17px"><a href="profile.php?uid='.$uid_id.'">'.$uid_login.'</a> <span style="font-size:11px">('; 
	echo $uid_sex_.'/'; 
	echo ''.$uid_age.')<br/>'.$uid_status.'</span><br/>';
	echo '<span style="font-size:11px;">Aktivlik: </span> <span style="font-size:12px; font-weight: bold; padding:0 5px; color: green;">'.$uid_activity.'</span>';
	echo '</td></tr>';
}
echo '</table>';

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="topactivity.php?period='.$_period.'&amp;page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="topactivity.php?period='.$_period.'&amp;page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="topactivity.php?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span>'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="topactivity.php?period='.$_period.'&amp;page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="topactivity.php?period='.$_period.'&amp;page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="topactivity.php?period='.$_period.'&amp;page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

echo '</div>';
include 'inc/footer.php';
?>