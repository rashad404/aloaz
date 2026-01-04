<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">'.$__lng['sohbet otaqlari'].'</a> » '.$__lng['yeni otaq yarat'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$country = 'az';17;// $userrow['country'];

if(isset($_POST['submit']) ){
	$_name = checkData($_POST['name']);
	$_view = intval($_POST['view']);
	if(strlen($_name) < 3) $error .= '- '.$__lng['min otaq adi sehvi'].'<br/>';
	if(strlen($_name) > 35) $error .= '- '.$__lng['max otaq adi sehvi'].'<br/>';
	
	$checkLimitQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room` WHERE `uid` = '".$id."' LIMIT 1");
	if(mysql_num_rows($checkLimitQuery) > 0) $error .= '- '.$__lng['otaq limiti sehvi'].'<br/>';
	
	$checkNameQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`room` WHERE `name` = '".$_name."' LIMIT 1");
	if(mysql_num_rows($checkNameQuery) > 0) $error .= '- '.$__lng['otaq var basqasin sec'].'<br/>';
	
	if(!empty($error)){
		echo '<span style="color: red">'.$error.'</span><br/>';
	}
	else{
		mysql_query("INSERT INTO `aloaz_db`.`room` SET `name` = '".$_name."', `uid` = '".$id."', `login` = '".$login."', `view` = '".$_view."', `country` = '".$country."', `time` = '".time()."'");
		if(mysql_affected_rows()>0){
			echo$__lng['yeni otaq yaradildi'].' <br/>';
		}
		else{
			echo 'Database error.<br/>';
		}
		echo '</div>';
		include '../inc/footer.php';
		exit;
	}
}

echo '<form method="post" action="create.php">';
echo $__lng['otaq adi'].':<br/>';
echo '<input type="text" name="name" /><br/><br/>';

echo $__lng['yazilari oxumaq icazesi'].':<br/>';
echo '<select name="view">';
echo '<option value="0">'.$__lng['hami'].'</option>';
echo '<option value="1">'.$__lng['yalniz uzvler'].'</option>';
echo '</select><br/><br/>';
	
echo '<input type="submit" name="submit" value="'.$__lng['yarat'].'" /><br/>';
echo '</form>';

echo '<br/>
'.$__lng['otaq acmaq pulsuzdur'].'<br/>
'.$__lng['otaq adi etik olmalidir'].'<br/><br/>
'.$__lng['otaqda yazismaq ucun uzv ol'].'<br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include '../inc/footer.php';
?>
