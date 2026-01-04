<?
error_reporting(0);
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="support.php">Müraciet</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError('Yalnız qeydiyyatlı istifadeçiler bloq yarada bilerler.<br/>'.
	'Xahiş edirik istifadeçi adı ve şifrenizle sayta daxil olun.<br/><br/>'.
	'<a href="login.php?loc=blog">Giriş</a> | <a href="http://server-saytim.net/chat/registration.php?loc=blog">Qeydiyyat</a>', 2);

}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

switch($_GET['mod']){

case 'add':
	if($_POST['submit'] == ''){
		echo '<form name="form" method="post" action="support.php?mod=add">';
		echo 'Müraciet növü: ';
		echo '<select name="type" value="1">
		<option value="1">Sual</option>
		<option value="2">Teklif</option>
		<option value="3">Şikayet</option>
		<option value="0">Diger</option>
		</select><br/><br/>';
		echo 'Başlıq:<br/>';
		echo '<input type="text" name="title" style="width: 200px;" /><br/><br/>';
		echo 'Müraciet metni:<br/>';
		echo '<textarea name="body" style="width: 200px;"></textarea><br/><br/>';
		echo '<input type="submit" name="submit" value="Gönder" /><br/>';
		echo '</form><br/>';
	}
	else{
		$_type = intval($_POST['type']);
		$_title = checkData($_POST['title']);
		$_body = checkData($_POST['body']);
		
		if(empty($_title)) $error .= '- Başlıq qeyd olunmayıb.<br/>';
		if(empty($_body)) $error .= '- Müraciet metni qeyd olunmayıb.<br/>';
		
		$checkQuery = mysql_query("SELECT `id` FROM `chat_support` WHERE `uid` = '".$id."' AND `answer` = '';");
		if(mysql_num_rows($checkQuery) > 0){
			$error .= '- Cavablandırılmamış sorğu var. Cavablandırıldıqdan sonra yeniden müraciet ede bilersiniz.<br/>';
		}
		
		if(!empty($error)){
			echo 'Aşağıdakı sehvler baş verdi:<br/>';
			echo '<span style="color: red;">'.$error.'</span><br/>';
			echo '<a href="javascript:history.back(1)">« Geri</a><br/>';
			break;
		}
		
		mysql_query("INSERT INTO `chat_support` SET `uid` = '".$id."', `type` = '".$_type."', `title` = '".$_title."', `body` = '".$_body."', `time` = '".time()."'");
		if(mysql_affected_rows()>0){
			echo 'Müracietiniz müveffeqiyyetle gönderildi.';
		}
		else{
			echo 'Texniki xeta. [DB5996]';
		}
	}
	
break;


default:

echo '<br/><a href="support.php?mod=add" class="button">Yeni Müraciet</a><br/><br/>';

$query = mysql_query("SELECT * FROM `chat_support` WHERE `uid` = '".$id."' ORDER BY `time` DESC LIMIT 10;");

if(mysql_num_rows($query) == 0){
	echo 'Müraciet tapılmadı<br/>';
	break;
}

while($row = mysql_fetch_array($query)){
	$supp_title = $row['title'];
	$supp_body = $row['body'];
	$supp_answer = $row['answer'];
	$supp_time = $row['time'];
	
	echo '<div class="support">';
	echo '<span style="font-size:11px">'.date('d-m-Y H:i', $supp_time).'</span><br/>';
	echo 'Başlıq: '.$supp_title.'<br/>';
	echo 'Müraciet: '.$supp_body.'<br/><hr />';
	echo 'Cavab: ';
	if($supp_answer != '') echo '<span style="color:green">'.$supp_answer.'</span><br/>'; else echo '<span style="color:red">Hele cavab yoxdur</span><br/>';
	echo '</div>';
}

break;
}

echo '</div><br/>';
include 'inc/footer.php';
?>
