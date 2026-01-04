<?
ob_start();
session_start();
error_reporting(0);


$__posttopoint = 300;

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';


$user_query = checkOnlyAuth('*',true);
if(mysql_num_rows($user_query)==0){
	header("Location:main.php"); exit();
} 

$userrow = mysql_fetch_array($user_query);
$id = $userrow['id'];
$login = $userrow['nickname'];
$point = $userrow['coins'];
$all_point = $userrow['all_coins'];
$xal = $userrow['point'];
$photo = $userrow['profile_photo'];
$post = $userrow['msg_count'];
$country = 'az';//$userrow['country_id'];
$block_time = $userrow["block_time"];
 
$expPhoto = explode('|', $photo);
$photoId = $expPhoto[1];

$mod = $_GET['mod'];

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Ban ləğvi</div>';
echo '<div class="layer">';
 
$checkAdminBlockQuery = mysql_query("SELECT id FROM `aloaz_db`.`blocks` WHERE `user_id`='".$id."' and `from_id`='".$paramsArray["adminId"]."' and `ended`=0"); 
if(mysql_num_rows($checkAdminBlockQuery)>0){
	echo "<b>Siz ADMIN tərəfindən uzaqlaşdırılmısınız.Bu səbəbdən qadağanı ləğv edə bilməzsiniz</b><br />";
	echo '</div>';
	include 'inc/footer.php';
	die();
}
 
	if($_POST['submit'] == ''){
	echo 'Sayta girişinizə qoyulmuş qadağanı balansınızdan '.$paramsArray["removeBan"].' bal çıxılaraq ləğv edə bilərsiniz.<br/>';
	if($point < $paramsArray["removeBan"]) echo '<br/>'.$__lng['hesabda bal yoxdur'].'. <a href="buy.php">+ '.$__lng['bal almaq'].'</a><br/>';
	echo '<br/>';
	echo $__lng['emeliyyati tesdiqleyin'].':<br/><br/>';

	echo '<form name="form" method="post" action="remove-ban.php">';
	echo '<input type="submit" name="submit" value="'.$__lng['tesdiqle'].'" /><br/><br/>';
	echo '</form>';
	
	echo $__lng['xidmetin deyeri'].': '.$paramsArray["removeBan"].' '.$__lng['bal'].'<br/>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	}
	else{
		if($point < $paramsArray["removeBan"]){
			echo $__lng['hesabda bal yoxdur'].'<br/>';
			echo '<br/><a href="buy.php">+ '.$__lng['bal almaq'].'</a><br/>';
			echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
		}
		else{
			$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = `coins`-".$paramsArray["removeBan"].",`block_time`=0,`block_begin_time`=0 WHERE `id` = '".$id."' LIMIT 1;");
			
			if($update){
				$checkBlockQuery = mysql_query("UPDATE  `aloaz_db`.`blocks` SET ended=1 WHERE (`end_time` > '".time()."' or `begin_time`=0) and `ended`=0 and `user_id` = '".$id."' ORDER BY `id` DESC;");
				header("Location:main.php");
			}
			else{
				echo 'Databse error [1127]<br/>';
			}
		}
	}

 
echo '</div>';
include 'inc/footer.php';
?>
