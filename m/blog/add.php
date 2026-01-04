<?phpsession_start();

$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="index.php">Bloq</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `level`');if($checkAuth == 'error'){	displayError('Yalnız qeydiyyatlı istifadeçiler istifade ede bilerler.<br/>'.	'Xahiş edirik istifadeçi adı ve şifrenizle sayta daxil olun.<br/><br/>'.	'<a href="index.php?loc=main">Daxil ol</a> | <a href="reg.php?loc=main">Qeyd ol</a>', 2);}
$userrow = mysql_fetch_array($checkAuth);
$uid = $userrow['id'];
$u_level = $userrow['level'];
if(!isset($_POST['secnumber'])){
	$secnumber = rand(1000, 9999);
	$_SESSION['secnumber'] = $secnumber;		echo '* Qeyri etik, reklam xarakterli ve ya kateqoriyaya aid olmayan yazılar yazmaq qadağandır<br/>';	echo '* Bloq sayını artırmaq üçün yazmayın. Menalı, maraqlı, metni qısa olmayan yazılar yazanlar mükafatlandırılacaq!<br/><br/>';
	
	echo '<form method="post" action="add.php">';	echo '<a href="addwithattach.php">Şekilli bloq yaz</a><br/><br/>';
	echo "Başlıq (max. 60):<br/>";
	echo "<input type=\"text\" name=\"title\" maxlength=\"60\"/><br/>";
	echo "Metn:<br/>";
	echo '<textarea cols="20" rows="5" name="body"></textarea><br/>';
	echo "Kateqoriya:<br/>";
	echo '<select name="catid">';
	$query = mysql_query("SELECT `id`, `name` FROM `blog_cat` ORDER BY `pos` ASC LIMIT 20;");
	while($row = mysql_fetch_array($query)){
		$catid = $row['id'];
		$catname = $row['name'];
		echo '<option value="'.$catid.'">'.$catname.'</option>';
	}
	echo '</select><br/>';
	echo '<input type="submit" value="Elave et">';
	echo '<input type="hidden" name="secnumber" value="'.$secnumber.'">';
	echo '</form>';
}
else{
	$title = trim(htmlspecialchars(mysql_escape_string($_POST['title'])));
	$title = str_replace('$', '$$', $title);
	$body = trim(htmlspecialchars(mysql_escape_string($_POST['body'])));
	$body = str_replace('$', '$$', $body);
	
	$catid = intval($_POST['catid']);
	$secnumber = intval($_POST['secnumber']);
	
	$error = '';
	
	if($secnumber != $_SESSION['secnumber']) $error .= "Ardıcıl elave etmek olmaz!<br/>";	//unset($_SESSION['secnumber']);	
	if(strlen($title) > 70) $error .= "Başlıq uzundur<br/>";
	if(strlen($title) < 3) $error .= "Başlıq qısadır<br/>";
	if(strlen($body) < 40) $error .= "Metn qısadır. Xahiş edirik bir az etraflı metn elave edin<br/>";

	if(!empty($error)){
		echo $error.'<br/>';
		echo '<a href="javascript:history.back(1)">« Geri</a></div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	if($u_level > 0) $ins_status = ", `status` = 1";
	mysql_query("INSERT INTO `blog_list` SET `uid` = '".$uid."', `catid` = '".$catid."', `name` = '".$title."', `body` = '".$body."', `date` = '".time()."'".$ins_status."");
	if(mysql_affected_rows() > 0){
		echo 'Müveffeqiyyetle elave olundu.<br/>';
	}
	else{
		echo 'Sehv baş verdi<br/>';
		//echo mysql_error();
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>