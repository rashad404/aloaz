<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func.php';
include '../inc/functions.php';
include('../photos/classes/photoResizer.php');
$i=1;

 $sql = mysql_query("select id,sex,changed_photo_url from chat_users where f_row=1 and changed_photo=1 and changed_photo_url!=''  limit 10");
while($row = mysql_fetch_assoc($sql)){
	$id = $row["id"];
	$sex = $row['sex'];
echo $i.')'.$id."<br />";	
 	$photo = str_replace('/thumbs/','/',$row['changed_photo_url']);
 	$filePath = 'images/user/'.$userId.'/'.$userId."_0.jpg";
	
	$photo_type  = 'jpg';
	$fileName = $id.'_'.time().'.'.$photo_type;
	$filePath = '../photos/files/'.$row["sex"].'/'.$fileName;
	
 	$file = file_get_contents('http://alochat.com'.$photo);
    file_put_contents($filePath,$file);
	
	$insert = mysql_query("INSERT INTO `chat_photos` SET `sex` = '".$sex."', `uid` = '".$id."', `filename` = '".$fileName."', `date` = '".time()."'");
	
	$photo_ins_id = mysql_insert_id();
	
	$update = mysql_query("UPDATE `chat_users` SET `photo` = '".$fileName."|".$photo_ins_id."',`changed_photo`=0,`changed_photo_url`='' WHERE `id` = '".$id."' LIMIT 1"); 
	
		createthumb("../photos/files/".$sex."/".$fileName."","../photos/files/thumbs/small/".$sex."/".$fileName."",55,60, 1);
		
		$image = new SimpleImage();
		$image->load("../photos/files/".$sex."/".$fileName."");
		$image->resize(250,250);
		$image->save('../photos/files/profile/'.$id.'.jpg', $image_type=IMAGETYPE_JPEG, $compression=80, $permissions=null);
		$i++;
}
?>