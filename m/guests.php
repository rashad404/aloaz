<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['qonaqlar'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=guests">'.$__lng['giris'].'</a> | <a href="reg.php?loc=guests">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

 $all_rows = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user_visit` WHERE `visit_to` = '".$id."' AND `time` > '".(time()-72*3600)."'");
$all_rows = mysql_result($all_rows, 0);

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT `id`, `visit_from`,`seen`,`time` FROM `aloaz_db`.`user_visit` WHERE `visit_to` = '".$id."' AND `time` > '".(time()-72*3600)."' ORDER BY `time` DESC LIMIT ".$start.", ".$show_limit.";");

if(mysql_num_rows($query) == 0){
	echo $__lng['ziyaret eden olmayib'].'<br/>';
	echo '</div>';
	include 'inc/footer.php';
	exit;
}

echo $__lng['ziyaret edenler'].'<br/><br/>';

while($row = mysql_fetch_array($query)){
	$v_id = $row['id'];
	$visitor_id = $row['visit_from'];
	$user = mysql_fetch_assoc(mysql_query('SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id`='.$visitor_id));
	$visitor = $user["nickname"];
	$view = $row['seen'];
	$time = $row['time'];
	
	if($view == 0){
		mysql_query("UPDATE `aloaz_db`.`user_visit` SET `seen` = '1' WHERE `id` = ".$v_id." LIMIT 1");
		$btag_open = '<b>';
		$btag_close = '</b>';
	}
	else{
		$btag_open = '';
		$btag_close = '';
	}
	
	echo '<a href="profile.php?uid='.$visitor_id.'">'.$visitor.'</a><br/>';
	echo $btag_open.'<span style="font-size:11px">'.$__lng['son ziyaret'].' '.date('d-m-Y H:i', $time).'</span>'.$btag_close.'<br/><br/>';
}

echo '<br/><div class="pageNav">';

$interval = 3;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="guests.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="guests.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="guests.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="guests.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="guests.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span>'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="guests.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';

if(intval(date('H')) == 10 && intval(date('i')) < 10){
	mysql_query("DELETE FROM `aloaz_db`.`user_visit` WHERE `time` < '".(time()-3600*24*4)."'");
}

echo '</div>';
include 'inc/footer.php';
?>
