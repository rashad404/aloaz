<?php 	
error_reporting(0);
session_start();

if($_SERVER['REMOTE_ADDR'] != '185.22.155.185') exit('Auth error');


include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/lang/pack.php';
$insert_text = '';
$query = mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `rating`>0 ORDER BY `rating` DESC");
$i = 1;
if(mysql_num_rows($query)>0){
	while($user = mysql_fetch_assoc($query)){
	echo $user["nickname"]." - ".$user["rating"]."<br />";
	if($i<6){
			if($i<=3){
				if($i==1) {$user_type = 3; $new_coins = $user["coins"];} //$paramsArray["bonus_coin_for_rating_1"]
				elseif($i==2) {$user_type = 2; $new_coins = $user["coins"];}//$paramsArray["bonus_coin_for_rating_2"];}
				elseif($i==3) {$user_type = 1; $new_coins = $user["coins"];}//$paramsArray["bonus_coin_for_rating_3"];}
				
				$update = mysql_query("UPDATE `aloaz_db`.`user` SET `coins` = '".$new_coins."',`user_status`='".$user_type."' WHERE `id` = '".$user["id"]."' LIMIT 1;");
				if($update){
					$status = $user_type; // vip_user
					$begin_time = time();
					$end_time = $begin_time + (30*24*3600); // 1 ayliq
					mysql_query("INSERT INTO `aloaz_db`.`user_status` SET `user_id` = '".$user["id"]."',`status` = '".$status."', `begin_time` = '".$begin_time."', `end_time` = '".$end_time."';");
					setNotification($user["id"],$paramsArray["NOT_USER_STATUS".$user_type],time());

				}
			}
			$insert_text .= ',`n'.$i.'_user`='.$user["id"];
			$insert_text .= ',`n'.$i.'_rating`='.$user["rating"];
	}else{
		break;
	}
	$i++;
	}
	mysql_query("INSERT INTO `aloaz_db`.`rating_stats` SET `date`='".date("Y-m-d H:i:s")."'".$insert_text);
	mysql_query("UPDATE `aloaz_db`.`user` SET `rating`=0 WHERE `rating`>0");
}

?>