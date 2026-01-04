<?php 
error_reporting(0);
 session_start();
include '../inc/config.php';
include '../inc/func_n04.php';
include '../inc/functions.php'; 
include '../inc/params.php'; 
$activity = mysql_fetch_assoc(mysql_query('SELECT id FROM `aloaz_db`.`cron` WHERE `cron`="weekly_activity_coin" and `date`="'.date("Y-m-d").'"'));
if($activity){
	echo "var";
}else{
	if(1){ //date('H') == '00' && intval(date('i')) < 30	 
		$users_query = mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `weekly_activity`>0 and `sex`=0 ORDER BY `weekly_activity` DESC LIMIT 5");
		$i = 1;
		while($user=mysql_fetch_assoc($users_query)){
			$user_id =  $user["id"]; 
			$bonus_coin = 0;
			if($i==1){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_1"];
				$type = $i; 
			}elseif($i==2){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_2"];
				$type = $i;
			}elseif($i==3){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_3"];
				$type = $i;
			}elseif($i==4){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_4"];
				$type = $i;
			}elseif($i==5){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_5"];
				$type = $i;
			}elseif($i>5){
				break;
			}
			
			$new_coins = $user["coins"] + $bonus_coin; 
			$new_all_coins = $user["all_coins"] + $bonus_coin; 
			echo $type."-ci yer -> ".$user_id." idli user ->  qazandigi bal +".$bonus_coin.", neticede ".$new_coins." bal oldu <br />";

			echo "UPDATE `user` SET `coins`='".$new_coins."',`all_coins`='".$new_all_coins."' WHERE `id`='".$user_id."' LIMIT 1"; 			$updateUser = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`='".$new_coins."',`all_coins`='".$new_all_coins."' WHERE `id`='".$user_id."' LIMIT 1");
			if($updateUser){
				setNotification($user_id,$paramsArray["NOT_USER_ACTIVITY".$type],time()); 
				mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$user_id."',`user_id2`=1,`coins`='".$bonus_coin."',`type`=2,`text`='".$paramsArray["LOG_BONUS_COIN_FOR_ACTIVITY"]."',`date`='".date("Y-m-d H:i:s")."'");
			}

		$i++;
		} 
		
		
		$users_query = mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `weekly_activity`>0 and `sex`=1 ORDER BY `weekly_activity` DESC LIMIT 5");
		$i = 1;
		while($user=mysql_fetch_assoc($users_query)){
			$user_id =  $user["id"]; 
			$bonus_coin = 0;
			if($i==1){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_1"];
				$type = $i; 
			}elseif($i==2){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_2"];
				$type = $i;
			}elseif($i==3){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_3"];
				$type = $i;
			}elseif($i==4){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_4"];
				$type = $i;
			}elseif($i==5){
				$bonus_coin = $paramsArray["bonus_coin_for_activity_5"];
				$type = $i;
			}elseif($i>5){
				break;
			}
			
			$new_coins = $user["coins"] + $bonus_coin; 
			$new_all_coins = $user["all_coins"] + $bonus_coin; 
			echo $type."-ci yer -> ".$user_id." idli user ->  qazandigi bal +".$bonus_coin.", neticede ".$new_coins." bal oldu <br />";

			echo "UPDATE `user` SET `coins`='".$new_coins."',`all_coins`='".$new_all_coins."' WHERE `id`='".$user_id."' LIMIT 1"; 			$updateUser = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`='".$new_coins."',`all_coins`='".$new_all_coins."' WHERE `id`='".$user_id."' LIMIT 1");
			if($updateUser){
				setNotification($user_id,$paramsArray["NOT_USER_ACTIVITY".$type],time()); 
				mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$user_id."',`user_id2`=1,`coins`='".$bonus_coin."',`type`=2,`text`='".$paramsArray["LOG_BONUS_COIN_FOR_ACTIVITY"]."',`date`='".date("Y-m-d H:i:s")."'");
			}

		$i++;
		}
		
		
		$resetActivity = mysql_query("UPDATE `aloaz_db`.`user` SET `weekly_activity`=0 WHERE `weekly_activity`>0");
		$activityIsset = mysql_fetch_assoc(mysql_query('SELECT id FROM `aloaz_db`.`cron` WHERE `cron`="weekly_activity_coin"'));
		if($activityIsset){
			mysql_query("UPDATE `aloaz_db`.`cron` SET `date`='".date("Y-m-d")."',`datetime`='".date("Y-m-d H:i:s")."' WHERE `cron`='weekly_activity_coin'");
			echo "update";
		}else{
			mysql_query("INSERT INTO `aloaz_db`.`cron` SET cron='weekly_activity_coin',date='".date("Y-m-d")."',`datetime`='".date("Y-m-d H:i:s")."'");
			echo "insert";
		}
	}else{
		echo "Icaze yoxdur <br />  Sizin IP -> ".$_SERVER["REMOTE_ADDR"];
	}
	
} 

?>