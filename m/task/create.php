<?
session_start();

include '../inc/func_n04.php';
include '../inc/functions_n04.php';
include '../inc/config.php';
include '../inc/lang/pack.php';

$title = 'AloChat';
include '../inc/header.php';

echo '<div class="mnav"><a href="../main.php">'.$title.'</a> » <a href="index.php">Müracietler</a> » Yeni müraciet yarat</div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname']; 
$user_status = $userrow["user_status"];
$adminlogin = false;
if($id==1129446 or $id==1129447){
	$adminlogin = true; 
}

if($user_status!=10 and $adminlogin==false){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=room">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=room">'.$__lng['qeyd ol'].'</a>', 2);
}


if(isset($_POST['submit']) ){
	$_name = checkData($_POST['name']);
	$_type = intval($_POST['type']);
	if(strlen($_name) < 3) $error .= '- Minimum 3 simvol olmalıdır<br/>';
	if(strlen($_name) > 35) $error .= '- Maximum 35 simvol ola biler<br/>';	
	
	$checkNameQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`task` WHERE `name` = '".$_name."' and `status`=0 LIMIT 1");
	if(mysql_num_rows($checkNameQuery) > 0) $error .= '- Bele bir müraciet yaradılıb<br/>';
	
	if(!empty($error)){
		echo '<span style="color: red">'.$error.'</span><br/>';
	}
	else{
		mysql_query("INSERT INTO `aloaz_db`.`task` SET `name` = '".$_name."', `uid` = '".$id."',  `type` = '".$_type."', `create_time` = '".time()."'");
		if(mysql_affected_rows()>0){
			echo 'Yeni müraciet yaradıldı <br/>';
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
echo 'Müracietin başılığı:<br/>';
echo '<input type="text" name="name" /><br/><br/>';

echo 'Müracietin tipi:<br/>';
echo '<select name="type">';
echo '<option value="1">İşlemir</option>';
echo '<option value="2">İstifadeçide problem</option>';
echo '<option value="3">Teklif</option>';
echo '</select><br/><br/>';
	
echo '<input type="submit" name="submit" value="'.$__lng['yarat'].'" /><br/>';
echo '</form>';

echo '<br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include '../inc/footer.php';
?>
