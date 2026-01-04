<?
error_reporting(0);
session_start();

$__posttopoint = 1000;

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$checkAuth = checkAuth('`id`, `nickname`, `coins`,`all_coins`, `point`, `profile_photo`, `msg_count`, `country_id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=pointserv">'.$__lng['giris'].'</a> | <a href="reg.php?loc=pointserv">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$point = $userrow['coins'];
$all_point = $userrow['all_coins'];
$xal = $userrow['point'];
$photo = $userrow['profile_photo'];
$post = $userrow['msg_count'];
$country = 'az';//$userrow['country_id'];

$expPhoto = explode('|', $photo);
$photoId = $expPhoto[1];

$mod = $_GET['mod'];

switch($mod){

default:

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal xidmetleri'].'</div>';
echo '<div class="layer">';

echo $__lng['hesabinizdaki ballar'].': '.$point.'<br/>';

echo '<a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].'</a><br/><br/>';


echo '- <a href="pointserv.php?mod=team">Vəzifə almaq</a><br/>';
echo '- <a href="pointserv.php?mod=renglinik">'.$__lng['rengli nik'].'</a> Yeni<br/>';
echo '- <a href="pointserv.php?mod=onlinerating">'.$__lng['irelide gorun'].'</a><br/>';
echo '- <a href="pointserv.php?mod=ozunutanit">'.$__lng['ozunu tanit'].'</a><br/>';
echo '- <a href="pointserv.php?mod=mysmile">Şexsi smayl</a><br/>';
echo '- <a href="pointserv.php?mod=changelogin">'.$__lng['loqini deyis'].'</a><br/>';
echo '- <a href="pointserv.php?mod=send_point">'.$__lng['bal gonder'].'</a><br/>';
echo '- <a href="pointserv.php?mod=posttopoint">'.$__lng['postla bal al'].'</a><br/>';
echo '- <a href="pointserv.php?mod=dellogin">'.$__lng['loqin sil hesab bagla'].'</a><br/>';

echo '<br/><a href="pointserv.php?mod=logs">Bal emeliyyatları arxivi</a><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;


case 'almaq';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';

/*
echo $__lng['sms ile bal al'].':<br/><br/>';
echo '10 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9136&amp;id='.$id.'&amp;bal=20">20 '.$__lng['bal al'].'</a><br/>';
echo '25 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9142&amp;id='.$id.'&amp;bal=50">50 '.$__lng['bal al'].'</a><br/>';
echo '60 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9143&amp;id='.$id.'&amp;bal=120">120 '.$__lng['bal al'].'</a><br/>';
echo '150 yox - <a href="http://mezmun.az/partner/services/point.php?pid=10&amp;shortnumber=9148&amp;id='.$id.'&amp;bal=300">300 '.$__lng['bal al'].'</a><br/><br/>';
*/

echo 'Portmanat ile bal al (Texniki problem var):<br/>';
echo '<form action="pointserv.php" method="POST">Kod: <input type="text" name="code" /><br/><input type="submit" name="code" value="Tesdiqle" /></form><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'logs';

?>
<style>
td {
  border-bottom:1pt solid #cdcdcd;
  padding:5px;
}
</style>
<?

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Sonuncu bal emeliyyatları</div>';
echo '<div class="layer">';
	$logs_query = mysql_query("SELECT * FROM `aloaz_db`.`coin_logs` WHERE user_id='".$id."' ORDER BY id DESC limit 6");
	
	if(mysql_num_rows($logs_query) == 0){
		echo 'Bal emeliyyatı tapılmadı<br/>';
	}
	else{
		echo '<table border="0" style="text-align:left; width: 100%; max-width: 600px"><tr style="font-weight:bold;"><td>#</td><td>Emeliyyat</td><td>Bal</td><td>Tarix</td></tr>';
		$i = 1;
		while($log = mysql_fetch_assoc($logs_query)){
			echo '<tr><td>'.$i.'</td><td>'.$paramsArray[$log["text"]].'</td><td>';
			$sym = $log["type"]==1?'-':'+'; 
			echo $sym.$log["coins"].'</td><td>'.$log["date"].'</td></tr>';
			$i++;
			
			// $sym = $log["type"]==1?'-':'+'; 
			// echo 'Tarix: '.$log["date"].'<br/>';
			// echo 'Emeliyyat: '.$paramsArray[$log["text"]].'<br/>';
			// echo 'Bal: '.$sym.$log["coins"].'<br/><br/>';
		}
		echo '</table>';
	}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'buy';

$portmanat = getPortmanat();
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';


echo 'Aşağıdakı üsullar vasitəsi ilə bal ala bilərsiniz:<br/>';
echo '<br />
<a class="payButton" href="pointserv.php?mod=pm_redirect" style="background-color: #818181; color: white; padding: 4px; border: 0px; font-size: 14px;">Portmanat Kodla bal al</a>
<br/><br/>
<a class="payButton" href="pointserv.php?mod=pm_hesab" style="background-color: #818181; color: white; padding: 4px; border: 0px; font-size: 14px;">Portmanat Hesabla bal al</a><br/>';

echo '<br/>Bal avtomatik olaraq əlavə olunur.<br/><br/>';
echo '1 AZN = 50 Bal<br/>';
echo '<small>Nümunə üçün 20 AZN -lik Portmanat kodunu yükləsəniz 1000 bal (20*50=1000) hesabınıza əlavə olunacaq.</small><br/><br/>';

echo '
<img src="img/million.jpg" alt="MilliÖN" height="120px" /><br/><br/>
<b>Portmanat kod almaq</b> üçün MilliÖN, eManat, Easypay aparatlarından Portmanat Kod almaq üçün Ödəmə kartları menyusuna daxil olunur, Portmanat Code düyməsi tapılıb vurulur, açılan ödəniş menyusunda kartın şifrə və seriya nömrəsinin SMS vasitəsilə mobil telefona göndərilməsindən ötrü mütləq olaraq işlətdiyiniz hər hansı mobil operatora məxsus telefon nomrə lazımi xanaya qeyd olunur, məbləğ aparata daxil edilir və təsdiqləmə düyməsi vurulur.<br/>
Əməliyyat başa çatdıqdan sonra ödəniş qəbzini götürməyi unutmayın. Çünki mobil telefonunuza gələn SMS ilə yanaşı, ödəniş qəbzinin də üzərində aldığınız Portmanat Kod və onun seriyası əks olunur.<br/>
<br/><img src="img/portmanat.png" alt="Portmanat" /><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'pm_redirect';

$portmanat = getPortmanat();
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';

$amount = 0;
$method = 'code';
if(isset($_POST["portmanat_submit"]) and isset($_POST["amount"]) and intval($_POST["amount"])>0){
	$method = 'account';
	$amount = intval($_POST["amount"]);
}
$date = date("Y-m-d H:i:s");

mysql_query("INSERT INTO `aloaz_db`.`transactions` SET `user_id`='".$id."',`amount`='".$amount."',`payment_method`='".$method."',`date`='".$date."',payment_status=0");
$order_id = mysql_insert_id();
?>
	Ödəmə səhifəsinə yönləndirilirsiniz...

	<form action='https://www.portmanat.az/checkout' method='post' id="portmanat-checkout" style="display: none">
		<input type='hidden' name='s_id' value='<?= $portmanat["portmanat_service_id"]?>'>
		<input type='hidden' name='o_id' value='<?= $order_id; ?>'>
		<input type='hidden' name='method' value='<?= $method?>'>
		<?php
		if($method == 'account'){
			echo "<input type='text' name='amount' value='".$amount."'>";
		}

		?>
		<input type='submit' value='Portmanat Kodla ödə'>
	</form>
	<script type="text/javascript">
		document.getElementById("portmanat-checkout").submit();
	</script>
<?php
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'pm_hesab';

$portmanat = getPortmanat();
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';
?>
	<form action="pointserv.php?mod=pm_redirect" method="post">
		Məbləğ (maksimum 400):<br>
		<input type="text" name="amount" value="1"><br>
		<input type="submit" name="portmanat_submit" value="Portmanat Hesabla ödə" style="background: #D9534F; border: none; color: #fff; margin-top: 5px; padding: 5px; font-size: 14px;">
	</form>
<?php
echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'pm_success';

	echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
	echo '<div class="layer">';
	echo "Ödəməniz uğurla tamamlandı<br/>";
break;

case 'pm_error';

	echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
	echo '<div class="layer">';
	echo "Ödəmə prosesinde xeta bas verdi<br/>";
break;

case 'onlinerating';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['irelide gorun'].'</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){

echo $__lng['xal sayi cox olarsa'].'<br/>';

$asa = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `point` > ".$xal." AND `last_activity`>".(time()-600).";");
$count_users = mysql_result($asa, 0);
$place=$count_users+1;
$all_user = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `last_activity`>".(time()-600).";");
$count_all_user = mysql_result($all_user, 0);

if($xal > 0) echo "".$__lng['xallarinizin sayi'].": <b>$xal</b>. ".$__lng['loqininizin onlaynda yeri'].": <b>$place</b><br/>";
else  echo "".$__lng['xallarinizin sayi'].": <b>$xal</b>. ".$__lng['loqininizin onlaynda yeri'].": <b>$place</b> - <b>$count_all_user</b><br/>";

if($place==1){
	echo $__lng['sizin loqin liderdir'].'<br/>';
}
else{
	echo $__lng['xallarin sayini artirmaqla'].'<br/>';
}

echo '<br/><form name="form" method="post" action="pointserv.php?mod=onlinerating">';
echo '<select name="xal" value="1">
<option value="1">1 '.$__lng['xal'].' (1 '.$__lng['bal'].')</option>
<option value="5">5 '.$__lng['xal'].' (5 '.$__lng['bal'].')</option>
<option value="10">10 '.$__lng['xal'].' (10 '.$__lng['bal'].')</option>
<option value="50">50 '.$__lng['xal'].' (50 '.$__lng['bal'].')</option>
<option value="100">100 '.$__lng['xal'].' (100 '.$__lng['bal'].')</option>
<option value="500">500 '.$__lng['xal'].' (500 '.$__lng['bal'].')</option>
<option value="1000">1000 '.$__lng['xal'].' (1000 '.$__lng['bal'].')</option>
</select><br/>';

echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" />';
echo '<input type="hidden" name="action" value="add" />';
echo '</form><br/>';

echo '<b>'.$__lng['qeyd'].':</b> '.$__lng['plus isaresi xallarin sayi'].'<br/>';
}
else{
$_xal = intval($_POST['xal']);

if($_xal<1){
	echo $__lng['minium 1 xal'].'.<br/>';
	break;
}

if($point < $_xal){
	echo $__lng['hesabda bal yoxdur'].'.<br/>';
	break;
}

$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`-'".$_xal."', `point` = `point`+".$_xal." WHERE `id` = '".$id."';");

if($update){
	echo $__lng['xaliniz artirildi'].'!<br/>';
	mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`='".$_xal."',`type`=1,`text`='".$paramsArray["LOG_ADD_POINT"]."',`date`='".date("Y-m-d H:i:s")."';");
} else echo 'Database Error [1158]<br/>';

$q = mysql_query("SELECT `point` FROM `aloaz_db`.`user` WHERE `id` = '".$id."';");

if(mysql_num_rows($q) != 0){
	$user = mysql_fetch_array($q);
	$xal = $user['point'];
}

$asa = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `point` > ".$xal.";");
$count_users = mysql_result($asa, 0);
$place=$count_users+1;

echo $__lng['xallarinizin sayi'].' <b>'.$xal.'</b>. '.$__lng['loqininizin onlaynda yeri'].': <b>'.$place.'</b><br/>';

}

break;

case 'mysmile':
		
 echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="pointserv.php?mod=mysmile">Şexsi smayl</a></div>';
	echo '<div class="layer">';

$smile_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`smiles` WHERE `user_id`='".$id."' ORDER BY `id` DESC LIMIT 1"));
if(!empty($changed_photo)){
	$expPhoto = explode('|', $changed_photo);
	$photoFileName = $expPhoto[0];
	$photoId = $expPhoto[1];
}  
if(isset($_GET['del'])){
	if($_GET['del'] != 1){
		echo $__lng['shekli silmeye eminsiniz'].'<br/>';
		echo '<a href="pointserv.php?mod=mysmile&amp;del=1">'.$__lng['beli sil'].'</a><br/><br/>';
	}
	else{  
		$smile = "img/smiles/".$smile_row["file"];  
		
		$unlink_smile = unlink($smile);  
		if($unlink_smile){
			$dir = 'mysmile.txt';
			$contents = file_get_contents($dir);
			$ser = unserialize($contents);
			//var_dump($ser); exit;
			unset($ser[0][$smile_row["smile"]]);
			$contents = serialize($ser); 
			file_put_contents($dir, $contents);
		
			echo $__lng['silindi'].'<br/><br/>';
 			mysql_query("DELETE FROM `aloaz_db`.`smiles` WHERE `user_id`='".$id."'");
 			mysql_query("UPDATE `aloaz_db`.`user` SET `mysmile`=0 WHERE `id`='".$id."'");
 			$delSuccess = true;
		}
	}
}

if(!isset($_POST['action'])){
	if($smile_row && !empty($smile_row["smile"]) && !$delSuccess){
		//$img_file = 'photos/preview.php?photo_id='.$photoId.'&amp;width=55&amp;height=60';
		$img_file = 'http://m.alo.az/img/smiles/'.$smile_row["file"].'?rand='.rand(11111,99999).'';
		if(!isset($_GET['del'])) $del_link = ' <a href="pointserv.php?mod=mysmile&amp;del=">'.$__lng['sekli sil'].'</a>';
	
		echo '<img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;min-width:32px;" /> '.$del_link.'<br/><br/>';
	}
	else{
		echo 'Şəxsi smaylınız yoxdur.<br/><br/>';
	}
	
	echo 'Yeni smayl seçin:<br/>';
	echo '<form action="pointserv.php?mod=mysmile" method="post" enctype="multipart/form-data">';
	echo '<input type="file" name="photo" /><br/>';
	
	echo '<input type="hidden" name="action" value="upload" />';
	echo '<input type="submit" value="'.$__lng['elave et'].'" /></form><br/>';
	
	echo '<b>.my.</b> yazaraq şəxsi smayldan istifadə edə bilərsiniz.<br/><br/>';
	
	echo 'Xidmetin deyeri: '.$paramsArray["mySmileCoin"].' bal<br/><br/>';
	echo $__lng['icaze verilen fayl formatlar'].': jpg, gif, png<br/>';
	echo $__lng['maksimum olculer'].': 100kb, 100x100px<br/>';
}
else{
	if($point < $paramsArray["mySmileCoin"]){
		echo $__lng['hesabda bal yoxdur'].'.<br/><br/>';
		echo '<a href="buy.php">Bal almaq</a><br/><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	
	$about = trim(mysql_escape_string($_POST['about']));
	
	if(!is_uploaded_file($_FILES['photo']['tmp_name'])){
		echo $__lng['shekil duzgun sechilmeyib'].'<br/><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	if($_FILES['photo']['type'] != image_type_to_mime_type(IMAGETYPE_GIF) && $_FILES['photo']['type'] != image_type_to_mime_type(IMAGETYPE_PNG) && $_FILES['photo']['type'] != image_type_to_mime_type(IMAGETYPE_JPEG)){
		echo $__lng['icaze verilmeyen sekil format'].'.<br />';
		break;
	}
	if(filesize($_FILES['photo']['tmp_name']) > 1024 * 3024){
		echo $__lng['sheklin hecmi max hecmi mb'].'.<br />';
		break;
	}
	
	$photo_size = getimagesize($_FILES['photo']['tmp_name']);
	if($photo_size[0] > 100 || $photo_size[0] > 100){
		echo $__lng['sheklin olchusu max hecmi px'].'.<br />';
		break;
	}

	$photo_type = substr($_FILES['photo']['type'], 6);
	if($photo_type != "gif" && $photo_type != "png" && $photo_type != "jpg" && $photo_type != "jpeg") {
		echo $__lng['icaze verilmeyen sekil format'].'.<br />';
		break;
	}

	$fileName = "s".$id.'.'.$photo_type;
	$smileName = ".s".$id.".";
	$smileFile = "s".$id;
	if($smile_row){ 
		$insert = mysql_query("UPDATE `aloaz_db`.`smiles` SET  `smile` = '".$smileName."',`file` = '".$fileName."' , `time` = '".time()."' WHERE `user_id` = '".$id."';");
			$photo_ins_id = $smile_row["id"];				
	}else{
 		$insert = mysql_query("INSERT INTO `aloaz_db`.`smiles` SET `user_id` = '".$id."', `smile` = '".$smileName."',`file` = '".$fileName."', `time` = '".time()."';"); 
		$photo_ins_id = mysql_insert_id();
	}
	
	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`-".$paramsArray["mySmileCoin"].",`mysmile`=1 WHERE `id` = '".$id."' LIMIT 1;");
	// log coin
	mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`='".$paramsArray["mySmileCoin"]."',`type`=1,`text`='".$paramsArray["LOG_OWN_SMILE"]."',`date`='".date("Y-m-d H:i:s")."';");
	
	$filename = "img/smiles/".$fileName;
	 
	if(move_uploaded_file($_FILES['photo']['tmp_name'], $filename)){
		echo "Şəxsi smayl əlavə olundu.<br/>";
		
		$dir = 'mysmile.txt';
		$contents = file_get_contents($dir);
		$ser = unserialize($contents);
		//var_dump($ser); exit;
		$ser[0][$smileName] =  $fileName;
		$contents = serialize($ser); 
		file_put_contents($dir, $contents);
		 
	}
	else{
		echo "Photo upload error (2)<br/>";
	}
}

break;


case 'renglinik':
		
 echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="pointserv.php?mod=renglinik">'.$__lng["rengli nik"].'</a></div>';
	echo '<div class="layer">';

$rengli_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`rengli` WHERE `user_id`='".$id."' and `ended`=0 ORDER BY `id` DESC LIMIT 1"));
 
if(!empty($changed_photo)){
	$expPhoto = explode('|', $changed_photo);
	$photoFileName = $expPhoto[0];
	$photoId = $expPhoto[1];
}  
if(isset($_GET['del'])){
	if($_GET['del'] != 1){
		echo $__lng['silmeye eminsiniz'].'<br/>';
		echo '<a href="pointserv.php?mod=renglinik&amp;del=1">'.$__lng['beli sil'].'</a><br/><br/>';
	}
	else{  
		$rengli_nik = "rn/tmp/".$rengli_row["file"];  
		
		$unlink_rengli_nik = unlink($rengli_nik);  
		if($unlink_rengli_nik){ 
		
			echo $__lng['silindi'].'<br/><br/>';
 			mysql_query("UPDATE `aloaz_db`.`rengli` SET `ended`=1 WHERE `user_id`='".$id."' ");
 			mysql_query("UPDATE `aloaz_db`.`user` SET `rnickname`='' WHERE `id`='".$id."'");
 			$delSuccess = true;
		}
	}
}

if(!isset($_POST['action'])){
	if($rengli_row && !empty($rengli_row["file"]) && !$delSuccess){
		//$img_file = 'photos/preview.php?photo_id='.$photoId.'&amp;width=55&amp;height=60';
		$img_file = 'http://m.alo.az/rn/tmp/'.$rengli_row["file"].'?rand='.rand(11111,99999).'';
		if(!isset($_GET['del'])) $del_link = ' <a href="pointserv.php?mod=renglinik&amp;del=1">Sil</a>';
		echo '<img src="'.$img_file.'" alt="man" /> '.$del_link.'<br/>';
		
		$rn_end_time = mysql_result(mysql_query("SELECT `end_time` FROM `aloaz_db`.`rengli` WHERE `user_id` = '".$id."'"), 0);
		echo date('d-m-Y H:i', $rn_end_time).' tarixine kimi aktivdir.<br/><br/>';
	}
	else{
		echo 'Rengli nikiniz yoxdur.<br/><br/>';
	}
	
	$values = json_decode($rengli_row['values'], true);
	
	echo 'Rengli nik hazırla:<br/><br/>';
	echo '<form action="pointserv.php?mod=renglinik" method="post" enctype="multipart/form-data">';

	echo '<label>Animasiya</label><br />';
	echo '<select name="animation">
		<option value="letter" '.($values['animation_type'] == 'letter' ? ' selected' : '').'>Tek tek herfler</option>
		<option value="flash" '.($values['animation_type'] == 'flash' ? ' selected' : '').'>Yanıb sönen</option>
	</select><br />';
	echo '<label>Font</label><br />';
	echo '<select name="font">'; 
	$fontArray = getNickFonts();
	foreach($fontArray as $font){
		if($font == $values['font']) $selected = ' selected'; else $selected = '';
		echo '<option value="'.$font.'" '.$selected.'>'.$font.'</option>';
	}
	echo '</select><br />';
	echo '<label>Font rengi</label><br />';
	echo '<select name="font_color">'; 
	$colorArray = getNickColors();
	foreach($colorArray  as $key=>$color){
		if($key == $values['font_color']) $selected = ' selected'; else $selected = '';
		echo '<option value="'.$key.'" '.$selected.'>'.$color.'</option>';
	}
	echo '</select><br />';
	echo '<label>Arxa fon rengi</label><br />';
	echo '<select name="bg_color">'; 
	$colorArray = getNickColors();
	foreach($colorArray  as $key=>$color){
		if($key == $values['bg_color']) $selected = ' selected'; else $selected = '';
		if($key == 'red' && !$rengli_row) $selected = ' selected';
		echo '<option value="'.$key.'" '.$selected.'>'.$color.'</option>';
	}
	echo '</select><br /><br />';
	echo '<input type="hidden" name="action" value="create" />';
	echo '<input type="submit" value="'.$__lng['elave et'].'" /></form><br/>';

	echo 'Rengli nik aktivleşdirmek üçün balansınızdan '.$paramsArray["rengliNickCoin"].' bal çıxılacaq ve 1 ay müddetinde aktiv olacaq.<br/>Aktiv olduğu müddetde rengli nikin formasını pulsuz deyişe bilersiniz.<br/>Rengli niki silseniz yeniden balla yaratmalı olacaqsınız.<br/>';
}
else{
	
	if(!$rengli_row && $point < $paramsArray["rengliNickCoin"]){
		echo $__lng['hesabda bal yoxdur'].'.<br/><br/>';
		echo '<a href="buy.php">Bal almaq</a><br/><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	$fontArray = getNickFonts();
	$colorArray = getNickColors();
	$font = trim(mysql_escape_string($_POST['font']));
	$font_color = trim(mysql_escape_string($_POST['font_color']));
	$bg_color = trim(mysql_escape_string($_POST['bg_color'])); 
	$animation_type = trim(mysql_real_escape_string($_POST["animation"]));
	
	$animation = new Imagick(); 
	$animation->setFormat('gif');
	
	$valuesArray = array('font_color' => $font_color, 'bg_color' => $bg_color, 'animation_type' => $animation_type, 'font' => $font);
	$valuesArrayJson = json_encode($valuesArray);

	$string = $login;
	
	//$bgColor = array_search($bg_color,$colorArray);
	if(!array_key_exists($bg_color, $colorArray)) $bgColor = 'black'; else $bgColor = $bg_color;
	
	//$fontColor = array_search($font_color,$colorArray);
	if(!array_key_exists($font_color, $colorArray)) $fontColor = 'white'; else $fontColor = $font_color;
	
	$fontName = $font;
 	$strlen = strlen($string);
	
	if($font_color == $bg_color){
		echo 'Fontun rengi ile fonun rengi eyni olmamalıdır.<br/><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	
	$height = 18;
	if(in_array($fontName, $fontArray)) $font = 'rn/font/'.$fontName.'.ttf'; else $font = 'rn/font/arial.ttf';
 
	$background = new ImagickPixel($bgColor);  
	$draw = new ImagickDraw(); 
	
	$fillcolor = new ImagickPixel($fontColor);
	$draw->setFillColor( $fillcolor );

	$draw->setFont($font);
	$fontMetrics = $animation->queryFontMetrics($draw, $string);
	$textWidth = $fontMetrics['textWidth'];
	
	$width = $textWidth+10;
	
	if($animation_type == 'letter'){
		for ($i = 0; $i <= strlen($string); $i++){
		$part = substr($string, 0, $i);

		$animation->newImage($width, $height, $background);
		$animation->annotateImage($draw, 5, 14, 0, $part);
		$animation->setImageDelay(30);
	}
	}elseif($animation_type == 'flash'){
		$animation->newImage($width, $height, $background);
		$animation->annotateImage($draw, $margin, 14, 0, '');
		$animation->setImageDelay(30);
	}
	
	//$draw->setTextDecoration(imagick::DECORATION_UNDERLINE);
	$animation->newImage($width, $height, $background);
	$animation->annotateImage($draw, 5, 14, 0, $string);
	$animation->setImageDelay(70);
	
	header('Content-Type: image/gif');
	$fileName = $id."-".rand(1000,9999).".gif";
	
	
	$fp = fopen('rn/tmp/'.$fileName, 'w');
	fwrite($fp, $animation->getImagesBlob());
	fclose($fp); 
	if($rengli_row){ 
		$rengli_nik = "rn/tmp/".$rengli_row["file"];
		$unlink_rengli_nik = unlink($rengli_nik);  
		mysql_query("UPDATE `aloaz_db`.`rengli` SET  `nickname` = '".$login."',`file` = '".$fileName."', `values` = '".$valuesArrayJson."' WHERE `user_id` = '".$id."' AND `id` = '".$rengli_row['id']."';"); 
		mysql_query("UPDATE `aloaz_db`.`user` SET `rnickname`='".$fileName."' WHERE `id` = '".$id."' LIMIT 1;");
		echo "Rengli nik deyişdirildi<br/>";
	}else{
		$start_time = time();
		$end_time = $start_time + 30*24*3600;
 		$insert = mysql_query("INSERT INTO `aloaz_db`.`rengli` SET `user_id` = '".$id."', `nickname` = '".$login."',`file` = '".$fileName."', `values` = '".$valuesArrayJson."', `start_time` = '".$start_time."', `end_time` = '".$end_time."';");  
		if($insert){
			$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`-".$paramsArray["rengliNickCoin"].",`rnickname`='".$fileName."' WHERE `id` = '".$id."' LIMIT 1;");
			// log coin
			mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`='".$paramsArray["rengliNickCoin"]."',`type`=1,`text`='".$paramsArray["LOG_RENGLI_NICK"]."',`date`='".date("Y-m-d H:i:s")."';");
			echo "Rengli nik elave olundu<br/>";
		}else{
			echo 'Xəta baş verdi';
		}
		
	}
	
	
	
	
}

break;


case 'ozunutanit';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['ozunu tanit'].'</div>';
echo '<div class="layer">';

if($_POST['submit'] == ''){
	echo $__lng['ozunu tanitdan istifade et'].'<br/>';
	echo $__lng['xidmetden son istifade edenin'].'<br/><br/>';
	echo $__lng['xidmetin deyeri'].': 10 '.$__lng['bal'].'<br/>';
	if($point < 10) echo $__lng['hesabda bal yoxdur'].'. <a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].'</a><br/>';
	echo '<br/>';
	echo $__lng['emeliyyati tesdiqleyin'].':<br/><br/>';

	echo '<form name="form" method="post" action="pointserv.php?mod=ozunutanit">';
	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/>';
	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{
	if($point < 10){
		echo $__lng['hesabda bal yoxdur'];
		break;
	}

	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`-10 WHERE `id` = '".$id."' LIMIT 1;");

	if($update){
		mysql_query("INSERT INTO `aloaz_db`.`chat_ozunutanit` SET `uid` = '".$id."' , `login` = '".$login."', `sex` = '".$sex."', `status` = '".$status."', `photo_id` = '".$photoId."', `country` = '".$country."', `time` = '".time()."';");
		if(mysql_affected_rows()>0) {
			echo $__lng['tebrik ozunu tanitdasiniz'].'<br/>';
			mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`=10,`type`=1,`text`='".$paramsArray["LOG_SET_VIP"]."',`date`='".date("Y-m-d H:i:s")."';");
			} else echo 'Databse error [1126]<br/>';
	}
	else{
		echo 'Databse error [1127]<br/>';
	}
}

break;

case 'team';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Vəzifə almaq</div>';
echo '<div class="layer">';

?>
<style>
td {
  border-bottom:1pt solid #cdcdcd;
}
</style>
<?

if($_POST['submit'] == ''){ 
 echo '<table style="text-align:center;" border="0">
	<tr style="background-color:#818181;color:#FFF">
		<td></td>
		<td style="padding:5px;"> '.$__lng["user_status_1"].' </td>
		<td style="padding:5px;"> '.$__lng["user_status_2"].' </td>
		<td style="padding:5px;"> '.$__lng["user_status_3"].' </td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Fərqləndirici ikon</td>
		<td><img src="http://m.alo.az/img/crown-bronze.png" alt="&check;" width="24px"></td>
		<td><img src="http://m.alo.az/img/crown-silver.png" alt="&check;" width="24px"></td>
		<td><img src="http://m.alo.az/img/crown-gold.png" alt="&check;" width="24px"></td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Otaqdakı yazıları silmek</td>
		<td>&check;</td>
		<td>&check;</td>
		<td>&check;</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Ban olunanlar siyahısı</td>
		<td>&check;</td>
		<td>&check;</td>
		<td>&check;</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Ban etmə müddəti</td>
		<td>30 dəq</td>
		<td>1 saat</td>
		<td>12 saat</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Ban açmaq</td>
		<td>x</td>
		<td>x</td>
		<td>&check;</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Elan yazmaq (1 gündə)</td>
		<td>1</td>
		<td>2</td>
		<td>3</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Gizli qonaq</td>
		<td>&check;</td>
		<td>&check;</td>
		<td>&check;</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Görünməz olmaq</td>
		<td>&check;</td>
		<td>&check;</td>
		<td>&check;</td>
	</tr>
	<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Ip və tel. modeli görmək</td>
		<td>&check;</td>
		<td>&check;</td>
		<td>&check;</td>
	</tr>
		<tr style="border-bottom:1px solid #000">
		<td style="text-align:left">Bal alanda bonus</td>
		<td>10%</td>
		<td>15%</td>
		<td>20%</td>
	</tr>
</table>';
 
 
	if($point < 10) echo $__lng['hesabda bal yoxdur'].'. <a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].'</a><br/>';
	echo '<br/>';
	echo "Status almaq üçün ".$__lng['emeliyyati tesdiqleyin'].':<br/><br/>';

	echo '<form name="form" method="post" action="pointserv.php?mod=team">';
	echo '<select name="type">
			<option value="1">'.$__lng["user_status_1"].' ('.$paramsArray["user_status_1_coins"].' Bal)</option>
			<option value="2">'.$__lng["user_status_2"].' ('.$paramsArray["user_status_2_coins"].' Bal)</option>
			<option value="3">'.$__lng["user_status_3"].' ('.$paramsArray["user_status_3_coins"].' Bal)</option>
	</select> ';
	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/>';
	echo '</form>';
	
	echo '<br/>QEYD:';
	echo '<br/>Vəzifə 1 ay müddətinə alınır. Müddət bitdikdən sonra eyni qaydada yenidən ala bilərsiniz.<br/>';
	echo 'Vəzifədən sui istifadə etmək, istifadəçini şəxsi səbəbə görə kənarlaşdırmaq qəti qadağandır.<br/>';
	echo 'Saytın istifadə şərtlərini, qaydalarını pozan istifadəçinin vəzifəsi vaxtından tez alına bilər.<br/>';
	
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{ 
	
	$user_type = intval($_POST["type"]);
	if($user_type==1){
		$coins = $paramsArray["user_status_1_coins"];
	}elseif($user_type==2){
		$coins =  $paramsArray["user_status_2_coins"];
	}elseif($user_type == 3){
		$coins =  $paramsArray["user_status_3_coins"];
	}else{
		echo $__lng['hesabda bal yoxdur'].'<br/>';
		echo '<a href="pointserv.php?mod=buy">Bal almaq</a><br/>';
		break;
	}
	
	if($point < $coins){
		echo $__lng['hesabda bal yoxdur'].'<br/>';
		echo '<a href="pointserv.php?mod=buy">Bal almaq</a><br/>';
		break;
	}
	
	$new_coins = $point - $coins;

	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = '".$new_coins."',`user_status`='".$user_type."' WHERE `id` = '".$id."' LIMIT 1;");

	if($update){
		$status = $user_type; // vip_user
		$begin_time = time();
		$end_time = $begin_time + (30*24*3600); // 1 heftelik
		$oldStatus = mysql_fetch_assoc(mysql_query("SELECT `id`  FROM `aloaz_db`.`user_status` WHERE `user_id`='".$id."' and `ended`=0 ORDER BY `id` DESC"));
		mysql_query("INSERT INTO `aloaz_db`.`user_status` SET `user_id` = '".$id."',`status` = '".$status."', `begin_time` = '".$begin_time."', `end_time` = '".$end_time."';");
		if(mysql_affected_rows()>0) {
			mysql_query("UPDATE `aloaz_db`.`user_status` SET `ended`=1,`end_time`='".time()."' WHERE `id`='".$oldStatus["id"]."'");	
			echo 'Tebrikler! Vəzifə aktivləşdirildi. <br/>'; 
			mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`='".$coins."',`type`=1,`text`='".$paramsArray["LOG_TEAM"]."',`date`='".date("Y-m-d H:i:s")."';");
			}else echo 'Database error [1126]<br/>';
	}
	else{
		echo 'Database error [1127]<br/>';
	}
}

break;

case 'changelogin';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['loqin deyismek'].'</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){
echo $__lng['xidmetin deyeri'].': 20 '.$__lng['bal'].'<br/><br/>';

echo '<form name="form" method="post" action="pointserv.php?mod=changelogin">';

echo $__lng['yeni loqin'].': (min:3)<br/>';
echo "<input name=\"new_login\" value=\"$login\" maxlength=\"20\"/><br/>";

echo $__lng['parolunuz'].":<br/>
<input type=\"text\" name=\"security_pass\" value=\"\" maxlength=\"20\"/><br/>";

echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" />';
echo '<input type="hidden" name="action" value="add" />';
echo '</form><br/>';

echo $__lng['loqin qaydalara uygun olsun'].'<br/><br/>';
}
else{
$security_pass = mysql_escape_string($_POST['security_pass']);

$new_login = htmlspecialchars(mysql_escape_string(trim($_POST['new_login'])));
$new_login = str_replace('$', '$$', $new_login);
$error = "";

$security_pass_q = mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `id` = '".$id."' AND `password` = '".$security_pass."';");
if(mysql_affected_rows() == 0){$error .= $__lng['parolu duz yaz'].".<br/>\n";}

if(preg_match("/[^A-Za-z0-9\@\*\(\)\!\-\~\_\[\]\=]+/",$new_login)) $error .= $__lng['loqinde qadaga simvol']."<br/>\n";
if(strlen($new_login) > 20) $error .= $__lng['loqin min max ola biler'].'<br/>';
if(detectBadWord($new_login)) $error = $__lng['loqinde qadagan olunmus soz'].'<br/>';
if(strlen($new_login) < 3) $error .= $__lng['loqin min max ola biler'].'<br/>';

if(empty($new_login)) $error .= $__lng['loqin yazilmayib'].'<br/>';

$q = mysql_query("SELECT `level` FROM `aloaz_db`.`user` WHERE `nickname` = '".$new_login."' AND `id` != '".$id."';");
if(mysql_num_rows($q) != 0) $error .= $__lng['basqa loqin sec'].'<br/>';

if($point < 20) $error .= $__lng['loqin deyismek ucun bal yoxdur'].'<br/>';

if(!empty($error)){
	echo $error;
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

$update = mysql_query("UPDATE `aloaz_db`.`user` SET `nickname` = '".$new_login."' ,`coins` = `coins`-20 WHERE `id` = '".$id."' LIMIT 1;");

if($update){
	$_SESSION['login'] = $new_login;
	echo $__lng['loqin deyisdirildi'].'<br/>';
	mysql_query("UPDATE `room` SET `login` = '".$new_login."' WHERE `uid` = '".$id."' LIMIT 1;");
	// log coin
	mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`=20,`type`=1,`text`='".$paramsArray["LOG_CHANGE_NICK"]."',`date`='".date("Y-m-d H:i:s")."';");
 }
else{
	echo "Error<br/>\n";
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}

}

break;


case 'kontur';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Kontur şifresi ile bal almaq</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){
echo 'Siz azercell kontur şifresini ve şifrenin neçə manatlıq olduğunu qeyd edirsiniz. Qısa müddət ərzində admin tərəfindən təsdiqlənib balansınız artırılacaq.<br/><br/>';

echo '<form name="form" method="post" action="pointserv2.php?mod=kontur">';

echo 'Şifrə : (13 simvol)<br/>';
echo "<input name='kontur' value='' maxlength='20'/><br/>";

echo "Manat:<br/>
<select name='amount'>
<option value='1'>1</option>
<option value='3'>3</option>
<option value='5'>5</option>
<option value='10'>10</option>
</select>";

echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" />';
echo '<input type="hidden" name="action" value="add" />';
echo '</form><br/>';

echo $__lng['loqin qaydalara uygun olsun'].'<br/><br/>';
}
else{
$kontur = intval($_POST['kontur']);

$amount = intval($_POST["amount"]);
$error = "";


if(strlen($kontur) != 13){$error .= "Şifrə 13 simvol olmalıdır.<br/>\n";}
if(!in_array($amount,[1,3,5,10])){$error .= "1,3,5,10 manatlıq ola bilər.<br/>\n";}

$check_kontur = mysql_query("SELECT id FROM `aloaz_db`.`kontur_coins` WHERE `kontur` = '".$kontur."';");
if(mysql_num_rows($check_kontur) > 0){$error .= "Bu şifrə artıq yüklənmişdir.<br/>\n";}
 
if(!empty($error)){
	echo $error;
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	break;
}

$insert = mysql_query("INSERT INTO `aloaz_db`.`kontur_coins` SET `kontur`='".$kontur."',`amount`='".$amount."',`user_id`='".$id."',`coins`=0,`insert_date`='".date("Y-m-d H:i:s")."',`status`=0");

if($insert){ 
	echo 'Azercell şifrəsi əlavə olundu. Tezliklə administrasiya tərəfindən şifrə yükləndikdən sonra balansınıza ballar əlavə ediləcək<br/>';
 }
else{
	echo "Error<br/>\n";
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}

}

break;



case 'send_point';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal gonder'].'</div>';
echo '<div class="layer">';

if(!isset($_POST['action'])){
	echo $__lng['hesabdaki ballar'].': '.$point.'<br/><br/>';

	echo '<form name="form" method="post" action="pointserv.php?mod=send_point">';

	echo $__lng['login'].":<br/>
	<input name=\"send_login\" maxlength=\"20\"/><br/>";

	echo $__lng['bal'].":<br/>
	<input type=\"text\" name=\"send_bal\" value=\"\" maxlength=\"20\"/><br/>";

	echo '<input type="submit" name="submit" value="'.$__lng['gonder'].'" />';
	echo '<input type="hidden" name="action" value="add" />';
	echo '</form><br/>';
	echo $__lng['bal komissiyasi tutulur'];
}
else{
	$send_login = trim(mysql_escape_string($_POST['send_login']));
	$send_bal_100 = intval($_POST['send_bal']);
	$send_bal = floor($send_bal_100*0.8);

	if(empty($send_login)){
		$error = $__lng['loqin qeyd olunmayib bal'].'.<br/>';
	}
	if(strtolower($login) == strtolower($send_login)){
		// $error = 'Özünüze bal göndere bilmezsiniz.<br/>';
	}
	if($send_bal_100<10){
		$error = $__lng['minium bal gondermek'].'.<br/>';
	}
	if($send_bal_100>10000){
		$error = $__lng['maksimum bal gondermek'].'.<br/>';
	}
	if($send_bal>$point){
		$error = $__lng['hesabda bal yoxdur'].'.<br/>';
	}
	
	$send_to_q = mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `nickname`='".$send_login."'");
	if(mysql_num_rows($send_to_q)==0){
		$error = $__lng['bal alacaq loqin tapilmadi'].'.<br/>';
	}else{
		$send_to_a = mysql_fetch_array($send_to_q);
		$send_to_id = $send_to_a['id'];
	}
	if(!empty($error)){
		echo '<span style="color:red;">'.$__lng['sehv'].':</span> '.$error;
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	$update1 = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`=`coins`-".$send_bal_100." WHERE `id`='".$id."'");
	$update2 = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`=`coins`+".$send_bal." WHERE `id`='".$send_to_id."'");
	setNotification($send_to_id,$paramsArray["NOT_USER_COIN"],time(),$id,$login,intval($send_bal),0);

	// log coin
	mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`user_id2`='".$send_to_id."',`coins`='".$send_bal_100."',`type`=1,`text`='".$paramsArray["LOG_SEND_COIN"]."',`date`='".date("Y-m-d H:i:s")."';");
	
	mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$send_to_id."',`user_id2`='".$id."',`coins`='".$send_bal."',`type`=2,`text`='".$paramsArray["LOG_RECEIVE_COIN"]."',`date`='".date("Y-m-d H:i:s")."';");
	
	echo 'Hesabınızdan '.$send_bal_100.' bal çıxıldı ve '.$send_login.' loginine '.$send_bal.' bal gönderildi.<br/>';
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
}

break;


case 'posttopoint';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['postla bal al'].'</div>';
echo '<div class="layer">';

if($_POST['submit'] == ''){
	echo $__lng['x posta x bal ala biler'].'<br/><br/>';
	
	echo $__lng['postlarinizin sayi'].': '.$post.'<br/><br/>';
	if($post < $__posttopoint) echo $__lng['postlar bal ucun kifayet etmir'].'<br/><br/>';
	
	echo '<form name="form" method="post" action="pointserv.php?mod=posttopoint">';
	//echo 'Postlar: <br/><input type="text" format="*N" size="7" name="posts" /><br/><br/>';
	echo $__lng['postlar'].': <br/><select name="posts" value="1">
	<option value="1000">1000 '.$__lng['post'].' (1 '.$__lng['bal'].')</option>
	<option value="2000">2000 '.$__lng['post'].' (2 '.$__lng['bal'].')</option>
	<option value="3000">3000 '.$__lng['post'].' (3 '.$__lng['bal'].')</option>
	<option value="4000">4000 '.$__lng['post'].' (4 '.$__lng['bal'].')</option>
	<option value="5000">5000 '.$__lng['post'].' (5 '.$__lng['bal'].')</option>
	<option value="6000">6000 '.$__lng['post'].' (6 '.$__lng['bal'].')</option>
	<option value="7000">7000 '.$__lng['post'].' (7 '.$__lng['bal'].')</option>
	<option value="8000">8000 '.$__lng['post'].' (8 '.$__lng['bal'].')</option>
	<option value="9000">9000 '.$__lng['post'].' (9 '.$__lng['bal'].')</option>
	<option value="10000">10000 '.$__lng['post'].' (10 '.$__lng['bal'].')</option>
	<option value="20000">20000 '.$__lng['post'].' (20 '.$__lng['bal'].')</option>
	</select><br/>';
	echo '<input type="submit" name="submit" value="'.$__lng['bal al'].'" /><br/>';
	echo '</form>';
}
else{
	$_posts = intval($_POST['posts']);
	
	if($_posts < 1){
		echo $__lng['postla bal ucun minimum'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($post < 1){
		echo $__lng['postla bal ucun minimum'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($_posts > 20000){
		echo 'ERROR<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($post < $__posttopoint){
		echo $__lng['postla bal ucun minimum'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	if($post < $_posts){
		echo $__lng['kifayet qeder post yoxdur'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	$checkQuery = mysql_query("SELECT `id` FROM `logs_buypoint` WHERE `time` > '".(time()-300)."' AND `uid` = '".$id."'");
	if(mysql_num_rows($checkQuery) > 0){
		echo $__lng['intervalla servisden istifade olar'].'<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}
	
	$addPoint = round($_posts/$__posttopoint);

 	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`+".$addPoint.", `all_coins` = `all_coins`+".$addPoint.", `msg_count` = `msg_count`-".$_posts." WHERE `id` = '".$id."' LIMIT 1;");

	if(mysql_affected_rows()>0){
		echo '<b>'.$addPoint.'</b> '.$__lng['x bal hesaba yuklenildi'].'<br/>';
		// log coin
		mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$id."',`coins`='".$addPoint."',`type`=2,`text`='".$paramsArray["LOG_BUY_COIN_POST"]."',`date`='".date("Y-m-d H:i:s")."';");
		mysql_query("INSERT INTO `logs_buypoint` SET `uid` = '".$id."', `amount` = '".$addPoint."', `from` = 'posttopoint', `time` = '".time()."', `date` = NOW();");
	}
	else{
		echo 'Databse error [7822]<br/>';
	}
	echo '<br/><a href="pointserv.php?mod=posttopoint">« '.$__lng['geri'].'</a><br/>';
}

break;


case 'dellogin';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['loqin sil hesab bagla'].'</div>';
echo '<div class="layer">';

if($_POST['del'] != 'ok'){
	if(intval($_GET['step'])==0){
		echo $__lng['loqin silseniz melumat itecek'].'<br/><br/>
		'.$__lng['loqini silmeye eminsiniz'].'<br/><br/>
		<a href="pointserv.php">'.$__lng['xeyr'].'</a> | <a href="pointserv.php?mod=dellogin&amp;step=1">'.$__lng['beli'].'</a><br/><br/>
		'.$__lng['loqin silinmesi ucun bal olmali'].'<br/>';
	}

	if(intval($_GET['step'])==1){
		echo '<span style="color: red"><b>'.$__lng['diqqet'].'!</b><br/>'.$__lng['sonuncu xeberdarliq'].'!</span><br/><br/>'.$__lng['loqin birdefelik silinesinmi'].'<br/><br/>';
		
		echo '<form name="form" method="post" action="pointserv.php?mod=dellogin">';
		echo '<input type="submit" name="submit" value="'.$__lng['beli'].'" />';
		echo '<input type="hidden" name="del" value="ok" />';
		echo '</form><br/>';
		
		echo '<a href="pointserv.php">'.$__lng['xeyr'].'</a><br/>';
	}
}
else{
	if($point < 50){
		echo $__lng['hesabda bal yoxdur'].'.<br/>';
		echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
		break;
	}

	$delete = mysql_query("DELETE FROM `aloaz_db`.`user` WHERE `id` = '".$id."' LIMIT 1;");
		
	if($delete){
			
		mysql_query("DELETE FROM `aloaz_db`.`share` WHERE `user_id` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`share_comment` WHERE `uid` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`share_like` WHERE `uid` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`conversation` WHERE `user_one` = '".$id."' OR `user_two` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`conversation_reply` WHERE `user_id` = '".$id."' OR `user_id_to` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`user_image` WHERE `user_id` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`user_image_resized` WHERE `user_id` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`user_image_thumb` WHERE `user_id` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`user_friend` WHERE `user_1` = '".$id."' OR `user_2` = '".$id."';");
		mysql_query("DELETE FROM `aloaz_db`.`chat_photos` WHERE `uid` = '".$id."';");
		

		echo $__lng['silindi'].'!<br/><br/>';
	}
	else{
		echo 'Database error [6698]<br/>';
	}
}

break;


}
if(!empty($mod)){
	echo '<br/><a href="pointserv.php">'.$__lng['bal xidmetleri'].'</a><br/>';
}
echo '</div>';
include 'inc/footer.php';
?>
