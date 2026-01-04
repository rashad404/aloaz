<?
include '../inc/config.php';
include '../inc/functions.php';
include '../inc/func.php';
include '../inc/header.php';

//$azercell = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `provider` = 'azercell' ;"), 0);

$period = checkData($_GET['period']);
if($period == 'today'){
	$ins_time = " AND `time` > '".strtotime(date("Y-m-d 00:00:00", time()))."'";
	$period_txt = 'Bugün';
}elseif($period == 'yesterday'){
	$ins_time = " AND `time` > '".strtotime("- 1 days")."'";
	$period_txt = 'Dünən';
}
else if($period == '7days'){
	$ins_time = " AND `time` > '".strtotime("- 7 days")."'";
	$period_txt = '7 gün';
}
else if($period == '30days'){
	$ins_time = " AND `time` > '".strtotime("- 30 days")."'";
	$period_txt = '30 gün';
}
else if($period == '60days'){
	$ins_time = " AND `time` > '".strtotime("- 60 days")."'";
	$period_txt = '60 gün';
}
else{
	$ins_time = "";
	$period_txt = 'Cemi';
}
echo '<div class="mnav">Statistika</div>';
echo '<div class="layer">';
echo '<a href="?period=today">Bugün</a> | <a href="?period=yesterday">Dünən</a> | <a href="?period=7days">7 gün</a> | <a href="?period=30days">30 gün</a> | <a href="?period=60days">60 gün</a> | <a href="?period=all">Cemi</a><br/><br/>';

echo 'Operatorlar üzre statistika ('.$period_txt.'):<br/>';
$query = mysql_query("SELECT `provider`, COUNT(`provider`) FROM `chat_users` WHERE `provider` != '' ".$ins_time." GROUP BY `provider`"); 
while($row = mysql_fetch_array($query)){
	echo $row['provider'].' - '.$row['COUNT(`provider`)'].' users<br/>';
}

echo '<br/>Aktiv istifadeçiler ('.$period_txt.'):<br/>';
$users_today = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `id` > 0 ".$ins_time." ;"), 0);
echo $users_today.'<br/>';

echo '<br/>App online:<br/>';
$onFromApp = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `onfrom` = 'app' AND `time` > '".time()."';"), 0);
$onFromApp_dating = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `dating`=0 AND `onfrom` = 'app' AND `time` > '".time()."';"), 0);
$onFromApp_no_dating = $onFromApp - $onFromApp_dating;
echo $onFromApp.' ('.$onFromApp_dating.' + '.$onFromApp_no_dating.')<br/>';

$regsToday = mysql_fetch_array(mysql_query("SELECT COUNT(id) from `chat_users` WHERE `created_at` > '".strtotime(date("Y-m-d 00:00:00", time()))."';"));
$regsToday = $regsToday[0];

echo '<br/>Bugün qeyd olanlar: '.$regsToday.'<br/>';

$q = mysql_query("SELECT `id`, `nickname`,`regfrom`,`sex`,`phone` FROM `chat_users` WHERE `created_at` > '".strtotime(date("Y-m-d 00:00:00", time()))."' ORDER BY `id` DESC LIMIT 300;");

if(mysql_num_rows($q) == 0){
	echo 'Bugün qeydiyyatdan keçen yoxdur.<br/>';
}
while($row = mysql_fetch_array($q)){
	$uid = $row['id'];
	$nickname = $row['nickname'];
	$regfrom = $row['regfrom'];
	$sex = $row['sex'];
	$phone = $row['phone'];
	
	$phone = substr($phone, 0, 5);

echo "<a href='../profile.php?uid=$uid'>$nickname</a> $sex "; 
if($regfrom == 'android') echo '<span style="color: red">'.$regfrom.'</span>'; else echo $regfrom;
echo ' '.$phone.'<br/>';
}

echo '</div>';
include '../inc/footer.php';
?>
