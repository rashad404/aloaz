<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['status'];
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">AloChat</a> » '.$title.'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `last_post`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=status">'.$__lng['giris'].'</a> | <a href="reg.php?loc=status">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$status = $userrow['last_post'];

	//echo '<option value="01"'.($birth_month == '01' ? ' selected' : '').'>yanvar</option>';

if(!isset($_POST['submit'])){
	echo '<form action="status.php" method="POST">';
	echo $__lng['hazirki status'].':<br/><input type="text" name="typed" value="'.$status.'" /><br/>';
	echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" />';
	echo '</form>';
	
	echo '<br/>-- '.$__lng['ve ya'].' --<br/><br/>';
	
	echo '<form action="status.php" method="POST">';
	echo $__lng['standart statuslar'].':<br/>';
	echo '<select name="template">';
	echo '<option value="" > </option>';
	echo '<option value="'.$__lng['bekaram'].'" '.($status == ''.$__lng['bekaram'].'' ? ' selected' : '').'>'.$__lng['bekaram'].'</option>';
	echo '<option value="'.$__lng['mesgulam'].'" '.($status == ''.$__lng['mesgulam'].'' ? ' selected' : '').'>'.$__lng['mesgulam'].'</option>';
	echo '<option value="'.$__lng['evdeyem'].'" '.($status == ''.$__lng['evdeyem'].'' ? ' selected' : '').'>'.$__lng['evdeyem'].'</option>';
	echo '<option value="'.$__lng['mektebdeyem'].'" '.($status == ''.$__lng['mektebdeyem'].'' ? ' selected' : '').'>'.$__lng['mektebdeyem'].'</option>';
	echo '<option value="'.$__lng['kinodayam'].'" '.($status == ''.$__lng['kinodayam'].'' ? ' selected' : '').'>'.$__lng['kinodayam'].'</option>';
	echo '<option value="'.$__lng['isdeyem'].'" '.($status == ''.$__lng['isdeyem'].'' ? ' selected' : '').'>'.$__lng['isdeyem'].'</option>';
	echo '</select><br/>';
	echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" />';
	echo '</form>';
}
else{
	$_typed = trim(mysql_escape_string($_POST['typed']));
	$_template = trim(mysql_escape_string($_POST['template']));
	
	if($_typed != '') $_newstatus = $_typed;
	else if ($_template != '') $_newstatus = $_template;
	else $error = $__lng['status yazilmayib'];
	
	if(!empty($error)){
		echo '<span style="color: red;">'.$error.'</span><br/><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		echo '</div>';
		include 'inc/footer.php';
		exit;
	}
	
	if($_typed != '') $_newstatus = $_typed;
	else if ($_template != '') $_newstatus = $_template;
	
	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `last_post` = '".$_newstatus."' WHERE `id` = '".$id."' LIMIT 1;");
	if($update){
		echo $__lng['deyisdirildi'].'.<br/>';
	}else{
		echo 'Database error<br/>';
	}
}
echo '<br/></div>';
include 'inc/footer.php';

?>
