<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Şəkil əlavə etmək </div>';
echo '<div class="layer">';

$checkAuth = checkAuth();
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=profile_edit">'.$__lng['giris'].'</a> | <a href="reg.php?loc=profile_edit">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id']; 
$name = $userrow['full_name'];  
$sex = $userrow['sex'];
$about = $userrow['about']; 
$photo = $userrow['profile_photo'];  

$mod = checkdata($_GET['mod']);


if(!isset($_POST['action'])){ 
		
	echo '<b>'.$__lng['qeyri etik shekil olmaz'].'</b><br/>'.$__lng['eks halda girise qadaga'].'<br/><br/>';
	
	echo '<form action="add_photo.php" method="post" enctype="multipart/form-data">';
	echo $__lng['shekil sech'].':<br/>';
	echo '<input type="file" name="photo" /><br/><br />';	
	echo '<input type="checkbox" name="set_profile" value="1" checked/>Profil foto olsun <br /><br />';
	echo '<input type="hidden" name="action" value="upload" />';
	echo '<input type="submit" value="'.$__lng['elave et'].'" /></form><br/>';
	echo $__lng['icaze verilen fayl formatlar'].': jpg, gif, png<br/>';
	echo $__lng['maksimum olculer'].': 3 mb, 3200x3200 px<br/>';
}
else{ 
	
	$_set_profile = intval($_POST["set_profile"]); 
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
	
	if (!is_dir($pathRoot.$path)) {
		mkdir($pathRoot.$path, 0777, true);
	}
	
	
	$path_resized = '/images/user/' . $id . '/resized/' ;
	$user_image_resized = $path_resized . $fileName;
	if (!is_dir($pathRoot.$path_resized)) {
		mkdir($pathRoot.$path_resized, 0777, true);
	}
	
	
	$path_thumbs = '/images/user/' . $id . '/thumbs/' ;
	$user_image_thumbs = $path_thumbs . $fileName;
	if (!is_dir($pathRoot.$path_thumbs)) {
		mkdir($pathRoot.$path_thumbs, 0777, true);
	}
	
	
	$insert = mysql_query("INSERT INTO `aloaz_db`.`user_image` SET `user_id`='".$id."',`path`='".$user_image."',`add_date`='".time()."'");
	$photo_ins_id = mysql_insert_id(); 
	$insert = mysql_query("INSERT INTO `aloaz_db`.`user_image_resized` SET `user_id`='".$id."',`path`='".$user_image_resized."',`add_date`='".time()."'");
	$insert = mysql_query("INSERT INTO `aloaz_db`.`user_image_thumb` SET `user_id`='".$id."',`path`='".$user_image_thumbs."',`add_date`='".time()."'");  
	if($_set_profile==1){ 
	   mysql_query("UPDATE `aloaz_db`.`user` SET profile_photo='".$user_image_thumbs."',profile_photo_id='".$photo_ins_id."',changed_photo=0 where id='".$id."' limit 1");
	} 
	
	if(copy($_FILES['photo']['tmp_name'], $pathRoot.$user_image)){
		
		echo $__lng['shekil elave olundu'].'<br/>';
		createthumb($pathRoot.$user_image,$pathRoot.$user_image_thumbs,120,120, 1);
		
		include('photos/classes/photoResizer.php');
		$image = new SimpleImage();
		$image->load($pathRoot.$user_image);
		$image->resizeToWidth(480);
		$image->save($pathRoot.$user_image_resized, $image_type=IMAGETYPE_JPEG, $compression=80, $permissions=null);
		
		
		 
	}
	else{
		echo "Photo upload error (2)<br/>";
	}
}
$photoCount = mysql_fetch_assoc(mysql_query("SELECT count(`id`) as c FROM `aloaz_db`.`user_image` WHERE `user_id`='".$id."'"));
echo '<br /><a href="photos/index.php">Şekillerim ('.$photoCount["c"].')</a>'; 
echo '</div>';
include 'inc/footer.php';
?>
