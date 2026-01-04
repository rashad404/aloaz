<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['aletler'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `only_friend`, `no_dating`, `post_run`,`room_refresh`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=settings">'.$__lng['giris'].'</a> | <a href="reg.php?loc=settings">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$friends = $userrow['only_friend'];
$dating = $userrow['no_dating'];
$post_run = $userrow['post_run'];
$room_refresh = $userrow['room_refresh'];

$mod = checkdata($_GET['mod']);

switch($mod){

default:
$refresh_times = ["0" => "Deaktiv","10" => "10 san","20" => "20 san","30" => "30 san","60" => "1 deq"]; 
if(!isset($_POST['submit'])){
	echo '<form action="settings.php" method="POST">';
	echo '<b>'.$__lng['istifade profili'].':</b><br/><br/>';
	echo '<label><input type="checkbox" name="dating" '.($dating == 1 ? ' checked="checked"' : '').' /> '.$__lng['tanisliq'].'</label><br/>';
	echo '  <span style="font-size:small; color: gray">'.$__lng['tanisliq meqsedi ucun'].'</span><br/><br/>';
	
	echo '<label><input type="checkbox" name="friends" '.($friends == 1 ? ' checked="checked"' : '').' /> '.$__lng['yalniz dostlar'].'</label><br/>';
	echo '  <span style="font-size:small; color: gray">'.$__lng['yalniz dostlar yaza bilecek'].'</span><br/><br/>';
	
	echo '<b>'.$__lng['elave secenekler'].':</b><br/><br/>';
	
	echo '<label><input type="checkbox" name="post_run" '.($post_run == 0 ? ' checked="checked"' : '').' /> '.$__lng['postlar aktiv'].'</label><br/>';
	echo '  <span style="font-size:small; color: gray">'.$__lng['post hesablanmasi ucun'].'</span><br/><br/>';
	
	echo '<label> Otaq yenileme vaxtı</label><br/><select name="room_refresh">';
	foreach($refresh_times as $k=>$v){
		$selected_time = '';
		if($room_refresh == $k){
			$selected_time = 'selected';
		}
		echo '<option value="'.$k.'" '.$selected_time.'>'.$v.'</option>';
	} 
	echo '</select><br />';
	echo '  <span style="font-size:small; color: gray"> Otaqda avtomatik yenileme vaxtı.</span><br/><br/>';
	
	echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" />';
	echo '</form><br/>';
}
else{
	$_friends = checkData($_POST['friends']);
	$_dating = checkData($_POST['dating']);
	$_post_run = checkData($_POST['post_run']);
	$_room_refresh = intval(checkData($_POST['room_refresh']));
	
	if(isset($_friends)) {
		if($_friends == 'on') $ins_friends = " `only_friend` = '1', "; else $ins_friends = " `only_friend` = '0', ";
	}
	if(isset($_dating)) {
		if($_dating == 'on') $ins_dating = " `no_dating` = '1', "; else $ins_dating = " `no_dating` = '0', ";
	}
	
	if(isset($_room_refresh)) {
		$ins_roomrefresh = " `room_refresh` = ".$_room_refresh.", ";
	}
	
	if(isset($_post_run)) {
		if($_post_run == 'on') $ins_postrun = " `post_run` = '0', "; else $ins_postrun = " `post_run` = '1', ";
	}
	
	mysql_query("UPDATE `aloaz_db`.`user` SET ".$ins_friends." ".$ins_dating." ".$ins_roomrefresh." ".$ins_postrun." `last_activity` = '".time()."' WHERE `id` = '".$id."' LIMIT 1;");
	if(mysql_affected_rows()>0){
		 echo $__lng['deyisdirildi'].'<br/>';
	}
}

break;
}

echo '</div>';
include 'inc/footer.php';
?>
