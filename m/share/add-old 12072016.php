<?
session_start();

include $_SERVER['DOCUMENT_ROOT'].'/inc/func_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions_n04.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/lang/pack.php';
$title = $__lng['paylash'];
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

echo '<div class="mnav"><a href="/main.php">AloChat</a> » '.$__lng['paylash'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `level`, `country_id`, `mysmile`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="../index.php?loc=share">'.$__lng['giris'].'</a> | <a href="../reg.php?loc=share">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$uid = $userrow['id'];
$u_level = $userrow['level'];
$mysmile = $userrow['mysmile'];
$u_country = 'az'; //$userrow['country'];

if(!isset($_POST['secnumber'])){
	$secnumber = rand(1000, 9999);
	$_SESSION['secnumber'] = $secnumber;
	
	echo '* '.$__lng['qeyri etik reklam yazilar olmaz'].'<br/><br/>';
	
	echo '<form name="form" method="post" enctype="multipart/form-data" action="?">';

	echo $__lng['shekil'].':<br/><input type="file" name="attach_file"><br/>';
	echo $__lng['qeyd'].':<br/>';
	echo '<textarea cols="25" rows="4" name="text"></textarea><br/>';
	echo $__lng['kimler gorsun'].':<br/>';
	echo '<select name="permission">';
	echo '<option value="0">'.$__lng['hami'].'</option>';
	echo '<option value="1">'.$__lng['yalniz dostlar'].'</option>';
	echo '</select><br/><br/>';
	echo '<input type="submit" value="'.$__lng['paylash'].'">';
	echo '<input type="hidden" name="secnumber" value="'.$secnumber.'">';
	echo '</form><br/>';
}
else{
	$_text = trim(htmlspecialchars(mysql_escape_string($_POST['text'])));
	$_text = str_replace('$', '$$', $_text);
	
	$_permission = intval($_POST['permission']);
	$secnumber = intval($_POST['secnumber']);
	
	if($_permission != 0) $_permission = 1;
	
	if(strlen($_text) < 1 && strlen($_FILES["attach_file"]['name']) < 3) $error .= $__lng['sekil secin yazin yazin'].'<br/>';
	
	if($secnumber != $_SESSION['secnumber']) $error .= $__lng['ardicil elave olmaz'].'<br/>';

	if(!empty($error)){
		echo $error.'<br/>';
		echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a></div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	$dateDir = date('Ym');
	
	/*
	//creating uploads dir
	if(!is_dir('uploads/'.$dateDir)){
		if(mkdir('uploads/'.$dateDir, 0777)){
			chmod('uploads/'.$dateDir, 0777);
		}
		else{
			exit('NEW DIRECTORY ERROR (uploads)');
		}
	}
	//creating thumbs dir
	if(!is_dir('thumbs/'.$dateDir)){
		if(mkdir('thumbs/'.$dateDir, 0777)){
			chmod('thumbs/'.$dateDir, 0777);
		}
		else{
			exit('NEW DIRECTORY ERROR (thumbs)');
		}
	}
	*/
	
	if($mysmile==1 and strpos($_text,".my.")!==false){
		$smile = mysql_fetch_assoc(mysql_query("SELECT `smile` FROM `aloaz_db`.`smiles` WHERE `user_id`='".$uid."' ORDER BY `id` DESC LIMIT 1"));
		if($smile){
			$_text = str_replace(".my.",$smile["smile"],$_text);
		}	
	}
	
	mysql_query("INSERT INTO aloaz_db.`share` SET `status`=1,`user_id` = '".$uid."', `text` = '".$_text."', `permission` = '".$_permission."', `country` = '".$u_country."', `time` = '".time()."'");
	
	$share_ins_id = mysql_insert_id();
	if(mysql_affected_rows() > 0){
		echo $__lng['muveffeqiyyetle elave olundu'].'<br/>';
		
		// $checkBalQuery = mysql_query("SELECT `id` FROM `aloaz_db`.`share_bal` WHERE `uid` = '".$uid."'");
		// if(mysql_num_rows($checkBalQuery) == 0){ 
			// mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins` + 10,  `all_coins` = `all_coins` + 10 WHERE `id` = '".$uid."' LIMIT 1");
			// mysql_query("INSERT INTO `aloaz_db`.`share_bal` SET `uid` = '".$uid."', `time` = '".time()."'");
			
			// $alochat_msg = $__lng['ilk paylasma hediyye mesaji'];			
			
			// $conversation_sql = mysql_query("SELECT id FROM aloaz_db.`conversation` WHERE (`user_one`='1' and `user_two`='".$uid."') or (`user_one`='".$uid."' and `user_two`='1')");
			// if(mysql_num_rows($conversation_sql) == 0){
				// $insert_conversation = mysql_query("INSERT INTO aloaz_db.`conversation` SET `user_one`='".$id."',`user_two`='".$uid."',`last_time`='".time()."',`last_reply`='".$alochat_msg."'");
				// $conversation_id = mysql_insert_id();
			// }else {
				// $conversation = mysql_fetch_assoc($conversation_sql);
				// $conversation_id = $conversation["id"]; 
			// }
			
			// mysql_query("INSERT INTO aloaz_db.`conversation_reply` SET `conversation_id`='".$conversation_id."',`user_id` = 1, `user_id_to` = '".$uid."', `from_nick` = 'AloChat', `reply` = '".$alochat_msg."', `time` = '".time()."';");
	 
		// }

	   $filen = $_FILES["attach_file"]['name']; //file name
	   
	   if(!empty($filen)){
			$exts = split("[/\\.]", strtolower($filen));
			$sc_n = count($exts)-1; 
			$ext = $exts[$sc_n];

			$hash = substr(md5(time().$filen.substr("abcdefghijklmnopqrstuvwxyz", 0, rand(5, 20))), 0, 15);

			$attach_name = $hash.".".$ext;
			$attach_thumbSmall = 's_'.$hash.".".$ext;
			
			$upload_file = "/home/admin/domains/alochat.com/public_html/images/share/uploads/".$dateDir."/".$attach_name.""; //generate the destination path
			
			$upload_dir = "/home/admin/domains/alochat.com/public_html/images/share/uploads/".$dateDir;
			if (!is_dir($upload_dir)) {
				mkdir($upload_dir, 0777, true);
			}
			
			if($ext != 'jpg' && $ext != 'jpeg' && $ext != 'gif' && $ext != 'png'){
				echo $__lng['sekil yuklemek olar'].'<br/>';
			}
			else{
				if(move_uploaded_file($_FILES["attach_file"]['tmp_name'],$upload_file)){
					mysql_query("UPDATE aloaz_db.`share` SET `attach` = '".$attach_name."' WHERE `id` = '".$share_ins_id."' LIMIT 1");
					//createthumb($upload_file, "thumbs/".$dateDir."/".$attach_thumbSmall."", 150, 120, 1); //small
					$upload_dir = "/home/admin/domains/alochat.com/public_html/images/share/thumbs/".$dateDir;
					if (!is_dir($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					}				
					
					createthumb2($upload_file, "/home/admin/domains/alochat.com/public_html/images/share/thumbs/".$dateDir."/".$attach_name."", 600, 400, 0); //medium
					$upload_dir = "/home/admin/domains/alochat.com/public_html/images/share/resized/".$dateDir;
					if (!is_dir($upload_dir)) {
						mkdir($upload_dir, 0777, true);
					}
					createthumb2($upload_file, "/home/admin/domains/alochat.com/public_html/images/share/resized/".$dateDir."/".$attach_name."", 220, 250, 0); //medium
				}
			}
		}
		unset($_SESSION['secnumber']);
	}
	else{
		echo 'Error<br/>';
	}
}

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
