<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['profil deyismek'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=profile_edit">'.$__lng['giris'].'</a> | <a href="reg.php?loc=profile_edit">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$md5_pass = $userrow['md5_pass'];
$name = $userrow['full_name'];
$birthday = $userrow['birthday'];
$weight = $userrow['weight'];
$height = $userrow['height']; 
$sex = $userrow['sex'];
$about = $userrow['about'];
$bal = $userrow['coins'];
$photo = $userrow['profile_photo'];
$changed_photo = $userrow['changed_photo_url'];
$country = strtolower($userrow['country_id']);

$mod = checkdata($_GET['mod']);

switch($mod){

default:
if(isset($_POST['submit'])){	
	$_name = checkData($_POST['name']);
	$_birth_day = checkData($_POST['birth_day']);
	$_birth_month = checkData($_POST['birth_month']);
	$_birth_year = checkData($_POST['birth_year']);
	$_weight = intval($_POST['weight']);
	$_height = intval($_POST['height']);	
 	$_about = checkData($_POST['about']);
	$_country = checkData($_POST['country']);
	$_sex = checkData($_POST["sex"]);
	
	if($_sex !=0 and $_sex!=1) $error = '- '.$__lng['cins duzgun daxil olunmayib'].'.<br/>';
	if($_birth_year > date('Y') -8) $error = '- '.$__lng['tevellud duzgun daxil olunmayib'].'.<br/>';
	if($_birth_year < date('Y') -70) $error = '- '.$__lng['tevellud duzgun daxil olunmayib'].'.<br/>';
	if((intval($_birth_day) < 1 || intval($_birth_day) > 31) || strlen($_birth_day) != 2) $error = '- '.$__lng['tevellud duzgun daxil olunmayib'].'.<br/>';
	if((intval($_birth_month) < 1 || intval($_birth_month) > 12) || strlen($_birth_month) != 2) $error = '- '.$__lng['tevellud duzgun daxil olunmayib'].'.<br/>';
	if(($_weight > 0 && $_weight < 10) || $_weight > 200) $error .= '- '.$__lng['cheki duzgun daxil olunmayib'].'.<br/>';
	if(($_height > 0 && $_height < 120) || $_height > 230) $error .= '- '.$__lng['boy duzgun daxil olunmayib'].'.<br/>';
	
	$_birthday = "$_birth_year-$_birth_month-$_birth_day";
	$age = floor((time() - strtotime($_birthday)) / (24*3600*365));
	
	if(!empty($error)){
		echo '<span style="color: red;">'.$error.'</span><br/>';
	}
	else{
		if(!empty($_name)) $ins_sql .= " `full_name` = '".$_name."', ";
		if(!empty($_birth_day)) $ins_sql .= " `birthday` = '".$_birthday."', ";
		 $ins_sql .= " `sex` = '".$_sex."', ";
		if(!empty($age)) $ins_sql .= " `age` = '".$age."', ";
		//if(!empty($_weight))
			$ins_sql .= " `weight` = '".$_weight."', ";
//		if(!empty($_height))
			$ins_sql .= " `height` = '".$_height."', ";
		if(!empty($_about)) $ins_sql .= " `about` = '".$_about."', ";
		if(!empty($_country)){
			$country = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`country` WHERE code='".strtoupper($_country)."'"));
			if($country){
				$ins_sql .= " `country_id` = '".$country["id"]."', ";
			}
			
		}   
		mysql_query("UPDATE `aloaz_db`.`user` SET ".$ins_sql." `last_activity` = '".(time())."' WHERE `id` = '".$id."' LIMIT 1;");
		if(mysql_affected_rows()>0){
			echo $__lng['deyisdirildi'].'<br/>';
			echo '</div>';
			include 'inc/footer.php';
			exit;
		}
	}
}

echo '<a href="profile_edit.php?mod=photo">'.$__lng['shekil elave et'].'</a> | ';
echo '<a href="profile_edit.php?mod=pass">'.$__lng['shifre deyish'].'</a><br/><br/>';

echo '<form action="profile_edit2.php" method="POST">';

$birth_day = date('d', strtotime($birthday));
$birth_month = date('m', strtotime($birthday));
$birth_year = date('Y', strtotime($birthday));
echo $__lng['ad'].':<br/>';
echo '<input type="text" name="name" value="'.$name.'" /><br/>';
echo $__lng['tevellud'].':<br/>';
echo '<select name="birth_day">';
for($i=1; $i<=31; $i++){
	if($i < 10) $i = '0'.$i;
	echo '<option value="'.$i.'"'.($birth_day == $i ? ' selected' : '').'>'.$i.'</option>';
}
echo '</select>';
echo '-';
echo '<select name="birth_month">';



echo '<option value="01"'.($birth_month == '01' ? ' selected' : '').'>'.$__lng['yanvar'].'</option>';
echo '<option value="02"'.($birth_month == '02' ? ' selected' : '').'>'.$__lng['fevral'].'</option>';
echo '<option value="03"'.($birth_month == '03' ? ' selected' : '').'>'.$__lng['mart'].'</option>';
echo '<option value="04"'.($birth_month == '04' ? ' selected' : '').'>'.$__lng['aprel'].'</option>';
echo '<option value="05"'.($birth_month == '05' ? ' selected' : '').'>'.$__lng['may'].'</option>';
echo '<option value="06"'.($birth_month == '06' ? ' selected' : '').'>'.$__lng['iyun'].'</option>';
echo '<option value="07"'.($birth_month == '07' ? ' selected' : '').'>'.$__lng['iyul'].'</option>';
echo '<option value="08"'.($birth_month == '08' ? ' selected' : '').'>'.$__lng['avqust'].'</option>';
echo '<option value="09"'.($birth_month == '09' ? ' selected' : '').'>'.$__lng['sentyabr'].'</option>';
echo '<option value="10"'.($birth_month == '10' ? ' selected' : '').'>'.$__lng['oktyabr'].'</option>';
echo '<option value="11"'.($birth_month == '11' ? ' selected' : '').'>'.$__lng['noyabr'].'</option>';
echo '<option value="12"'.($birth_month == '12' ? ' selected' : '').'>'.$__lng['dekabr'].'</option>';
echo '</select>';
echo '-';
echo '<input type="text" name="birth_year" format="*N" maxlength="4" size="4" value="'.$birth_year.'" /><br/>';

$man_selected = $woman_selected = '';
if($sex==0) $man_selected = 'selected';
elseif($sex==1) $woman_selected = 'selected';
echo $__lng['cins'].':<br/>';
echo '<select name="sex">
	<option value="0" '.$man_selected.'>Kişi</option>
	<option value="1" '.$woman_selected.'>Qadın</option>
</select><br />';

echo $__lng['cheki kq'].':<br/>';
echo '<input type="text" name="weight" format="*N" maxlength="3" size="4" value="'.$weight.'" /><br/>';

echo $__lng['boy sm'].':<br/>';
echo '<input type="text" name="height" format="*N" maxlength="3" size="4" value="'.$height.'" /><br/>';

echo $__lng['haqqinda'].':<br/>';
echo '<input type="text" name="about" value="'.$about.'" /><br/>';

echo $__lng['olke'].':<br/>';
echo '<select name="country">';
$countryListQuery = mysql_query("SELECT DISTINCT(`country_code`), `country_name` FROM `ip_tables` WHERE `country_code` != 'a1' ORDER BY `country_name`");
while($countryListRow = mysql_fetch_array($countryListQuery)){
	$countryCodes = strtolower($countryListRow['country_code']);
	$countryName = $countryListRow['country_name'];
	echo '<option value="'.$countryCodes.'"'.($countryCodes == $country ? ' selected' : '').'>'.$countryName.'</option>';
}
echo '</select><br/><br/>';

echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" />';
echo '</form>';

if($country == 'az') echo '<br/>'.$__lng['hesabdaki ballar'].': '.$bal;
echo '<br/>';

break;


case 'photo':

if(!empty($changed_photo)){
	$expPhoto = explode('|', $changed_photo);
	$photoFileName = $expPhoto[0];
	$photoId = $expPhoto[1];
}  
if(isset($_GET['del'])){
	if($_GET['del'] != 1){
		echo $__lng['shekli silmeye eminsiniz'].'<br/>';
		echo '<a href="profile_edit.php?mod=photo&amp;del=1">'.$__lng['beli sil'].'</a><br/><br/>';
	}
	else{  
		$upload_file_thumb = "/home/aloaz/public_html/alochat.com/public_html".$photo;
		$upload_file_resized = "/home/aloaz/public_html/alochat.com/public_html".$photo;
		$upload_file_original = "/home/aloaz/public_html/alochat.com/public_html".$photo;
		
		$unlink_thumb = unlink($upload_file_thumb);
		$unlink_resized = unlink($upload_file_resized);
		$unlink_original = unlink($upload_file_original);
		
		$unlink1 = unlink("photos/files/".$sex."/".$photoFileName."");
		$unlink2 = unlink("photos/files/thumbs/small/".$sex."/".$photoFileName."");
		if($unlink1 || $unlink2 || $unlink_thumb || $unlink_resized || $unlink_original){
			echo $__lng['silindi'].'<br/><br/>';
			mysql_query("UPDATE `aloaz_db`.`user` SET `profile_photo` = '',`profile_photo_id`=0,`changed_photo_url`='' WHERE `id` = '".$id."' LIMIT 1;");
			//mysql_query("UPDATE `aloaz_db`.`chat_users` SET `photo` = '',`thumb`=0 WHERE `id` = '".$id."' LIMIT 1;");
			$delSuccess = true;
		}
	}
}

if(!isset($_POST['action'])){ 
	if(!empty($photo) && !$delSuccess){ 
		//$img_file = 'photos/preview.php?photo_id='.$photoId.'&amp;width=55&amp;height=60';
		$img_file = 'http://alochat.com'.$photo;
		if(!isset($_GET['del'])) $del_link = ' <a href="profile_edit.php?mod=photo&amp;del=">'.$__lng['sekli sil'].'</a>';
		echo '<img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /> '.$del_link.'<br/><br/>';
	}
	
	echo '<b>'.$__lng['qeyri etik shekil olmaz'].'</b><br/>'.$__lng['eks halda girise qadaga'].'<br/><br/>';
	echo $__lng['shekil sech'].':<br/>';
	echo '<form action="profile_edit.php?mod=photo" method="post" enctype="multipart/form-data">';
	echo '<input type="file" name="photo" /><br/>';
	
	echo '<input type="hidden" name="action" value="upload" />';
	echo '<input type="submit" value="'.$__lng['elave et'].'" /></form><br/>';
	echo $__lng['icaze verilen fayl formatlar'].': jpg, gif, png<br/>';
	echo $__lng['maksimum olculer'].': 3 mb, 3200x3200 px<br/>';
}
else{
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
	if($photo_size[0] > 6400 || $photo_size[0] > 6400){
		echo $__lng['sheklin olchusu max hecmi px'].'.<br />';
		break;
	}

	$photo_type = substr($_FILES['photo']['type'], 6);
	if($photo_type != "gif" && $photo_type != "png" && $photo_type != "jpg" && $photo_type != "jpeg") {
		echo $__lng['icaze verilmeyen sekil format'].'.<br />';
		break;
	}

	$fileName = $id.'_'.time().'.'.$photo_type;
	
	$insert = mysql_query("INSERT INTO `chat_photos` SET `sex` = '".$sex."', `uid` = '".$id."', `filename` = '".$fileName."', `about` = '".$about."', `date` = '".time()."';");
	
	$photo_ins_id = mysql_insert_id();
	
	//$update = mysql_query("UPDATE `chat_users` SET `photo` = '".$fileName."|".$photo_ins_id."',changed_photo=1 WHERE `id` = '".$id."' LIMIT 1;");
	$update = mysql_query("UPDATE `aloaz_db`.`user` SET `changed_photo_url` = '".$fileName."|".$photo_ins_id."',changed_photo=1 WHERE `id` = '".$id."' LIMIT 1;");
	
	mysql_query("UPDATE `aloaz_db`.`chat_ozunutanit` SET `photo_id` = '".$photo_ins_id."' WHERE `uid` = '".$id."';");
	
	if(!empty($photo)){
		$expPhoto = explode('|', $photo);
		$photoName = $expPhoto[0];
		$photoId = $expPhoto[1];
	}
	
	if(copy($_FILES['photo']['tmp_name'], "photos/files/".$sex."/".$fileName."")){
		if(!empty($photoFileName)){
			unlink("photos/files/".$sex."/".$photoFileName."");
			unlink("photos/files/thumbs/small/".$sex."/".$photoFileName."");
		}
		echo $__lng['shekil elave olundu'].'<br/><br/>';
		createthumb("photos/files/".$sex."/".$fileName."","photos/files/thumbs/small/".$sex."/".$fileName."",55,60, 1);
		
		include('photos/classes/photoResizer.php');
		$image = new SimpleImage();
		$image->load("photos/files/".$sex."/".$fileName."");
		$image->resize(250,250);
		$image->save('photos/files/profile/'.$id.'.jpg', $image_type=IMAGETYPE_JPEG, $compression=80, $permissions=null);
		
		$url  = 'http://alochat.com/alo/alo-image/'.$id;	 		
		$text = file_get_contents($url);
		//var_dump($text);
		 
	}
	else{
		echo "Photo upload error (2)<br/>";
	}
}

break;


case 'pass':

if(!isset($_POST['submit'])){
	echo '<form action="profile_edit.php?mod=pass" method="POST">';
	echo $__lng['hazirki sifre'].':<br/><input type="text" name="old_pass" /><br/>';
	echo $__lng['yeni sifre'].':<br/><input type="text" name="new_pass" /><br/>';
	echo '<input type="submit" name="submit" value="'.$__lng['deyis'].'" />';
	echo '</form>';
}
else{
	$_old_pass = checkData($_POST['old_pass']);
	$_new_pass = checkData($_POST['new_pass']);
	
	if(md5($_old_pass) != $md5_pass) $error = $__lng['hazirki sifre duzgun daxil olunmayib'].'.<br/>';
	if(strlen($_new_pass) < 6) $error = $__lng['sifre simvoldan az olmamali'].'.<br/>';
	if(strlen($_new_pass) > 30) $error = $__lng['sifre simvoldan cox olmamali'].'.<br/>';

	
	if(!empty($error)){
		echo '<span style="color: red;">'.$error.'</span><br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		break;
	}
	
	mysql_query("UPDATE `aloaz_db`.`user` SET `password` = '".$_new_pass."', `md5_pass` = '".md5($_new_pass)."',`changed_pass` = 1 WHERE `id` = '".$id."' AND `password` = '".$_old_pass."' LIMIT 1;");
 	
	if(mysql_affected_rows() > 0){
		$_SESSION['password'] = md5(trim($_new_pass));
		echo $__lng['sifre deyisdirildi'].'.<br/>';
		
		$url  = 'http://alochat.com/alo/change-pass/'.$id;	 		
		$text = file_get_contents($url);
		
	}else echo 'Error 5566';
}

break;


case 'image':
 


if(!isset($_POST['action'])){ 
		
	echo '<b>'.$__lng['qeyri etik shekil olmaz'].'</b><br/>'.$__lng['eks halda girise qadaga'].'<br/><br/>';
	echo $__lng['shekil sech'].':<br/>';
	echo '<form action="profile_edit2.php?mod=image" method="post" enctype="multipart/form-data">';
	echo '<input type="file" name="photo" /><br/>';
	
	echo '<input type="hidden" name="action" value="upload" />';
	echo '<input type="submit" value="'.$__lng['elave et'].'" /></form><br/>';
	echo $__lng['icaze verilen fayl formatlar'].': jpg, gif, png<br/>';
	echo $__lng['maksimum olculer'].': 3 mb, 3200x3200 px<br/>';
}
else{ 
	
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
	if($photo_size[0] > 6400 || $photo_size[0] > 6400){
		echo $__lng['sheklin olchusu max hecmi px'].'.<br />';
		break;
	}

	$photo_type = substr($_FILES['photo']['type'], 6);
	if($photo_type != "gif" && $photo_type != "png" && $photo_type != "jpg" && $photo_type != "jpeg") {
		echo $__lng['icaze verilmeyen sekil format'].'.<br />';
		break;
	}
	
	$fileName = $id.'_'.time().'.'.$photo_type;
	
	$pathRoot = '/home/aloaz/public_html/alochat.com/public_html';

	
	$path = '/images/user/' . $id . '/' ;
	$user_image = $path . $fileName;
	
	$path_resized = '/images/user/' . $id . '/resized/' ;
	$user_image_resized = $path_resized . $fileName;
	
	$path_thumbs = '/images/user/' . $id . '/thumbs/' ;
	$user_image_thumbs = $path_thumbs . $fileName;
	
	$insert = mysql_query("INSERT INTO `aloaz_db`.`user_image` SET `user_id`='".$id."',`path`='".$user_image."',`add_date`='".time()."'");
	$photo_ins_id = mysql_insert_id(); 
	$insert = mysql_query("INSERT INTO `aloaz_db`.`user_image_resized` SET `user_id`='".$id."',`path`='".$user_image_resized."',`add_date`='".time()."'");
	$insert = mysql_query("INSERT INTO `aloaz_db`.`user_image_thumb` SET `user_id`='".$id."',`path`='".$user_image_thumbs."',`add_date`='".time()."'"); 
	
	if(copy($_FILES['photo']['tmp_name'], $pathRoot.$user_image)){
		
		echo $__lng['shekil elave olundu'].'<br/><br/>';
		createthumb($pathRoot.$user_image,$pathRoot.$user_image_thumbs,120,120, 1);
		
		include('photos/classes/photoResizer.php');
		$image = new SimpleImage();
		$image->load($pathRoot.$user_image);
		$image->resizeToWidth(360);
		$image->save($pathRoot.$user_image_resized, $image_type=IMAGETYPE_JPEG, $compression=80, $permissions=null);
		
		
		 
	}
	else{
		echo "Photo upload error (2)<br/>";
	}
}

break;
}
echo '</div>';
include 'inc/footer.php';
?>
