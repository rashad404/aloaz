<?php
include '../../inc/config.php';
include '../../inc/params.php';
$manat_coin = 50;
if (!empty($_POST)) {
    $p_id = 10575;        //partnyor ID-ni bura yazın
    $s_id = 11943;        //xidmət ID-ni bura yazın
    $key = 'a5V15ePld';    //xidmət şifrəsini bura yazın
    $o_id = $_POST['o_id'];
    $tr_id = $_POST['transaction'];
    $method = $_POST['method'];
    $amount = $_POST['amount'];
    $test = $_POST['test'];
    $hash_from = $_POST['hash'];

    $coins = round($amount) * 50;

    $hash = strtoupper(md5($p_id . $s_id . $o_id . $tr_id . $key));
    $transaction = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`transactions` WHERE payment_status=0 and id='".$o_id."'"));
    $user = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `id`='".$transaction["user_id"]."' limit 1;"));
    if ($hash == $hash_from) //Əgər dogrudursa 1, yoxsa 0 qaytarılmalı
    {
        if ($test == '0')//Əgər test rejimi söndürülübsə
        {
            //Baza əməliyyatlarını burada qeyd edin.
            //(Məsələn: Hesab artirılsın, bal yüklənsin və s.)
            $date = date("Y-m-d H:i:s");

            $bonus = 0;
            if($user["user_status"]==1) $bonus = round(($coins*$paramsArray["user_status_bonus_1"])/100);
            elseif($user["user_status"]==2) $bonus = round(($coins*$paramsArray["user_status_bonus_2"])/100);
            elseif($user["user_status"]==3) $bonus = round(($coins*$paramsArray["user_status_bonus_3"])/100);

            $coins = $coins + $bonus;
			
			if($transaction["amount"]==0 or $transaction["amount"]==$amount){
				$t_amount = $amount;
			}
            mysql_query("UPDATE `aloaz_db`.`transactions` SET payment_status=1,update_date='".$date."',`amount`='".$t_amount."',coins='".$coins."' where id='".$o_id."' limit 1");
            mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET user_id='".$transaction["user_id"]."',`coins`='".$coins."',`type`=2,text='buy_coin_portmanat',`date`='".$date."';");

            $user_new_coins = $user["coins"] + $coins;
            $user_new__all_coins = $user["all_coins"] + $coins;
            mysql_query("UPDATE `aloaz_db`.`user` SET `coins`='".$user_new_coins."',`all_coins`='".$user_new__all_coins."' WHERE id='".$user["id"]."';");

        }
        echo '1';
    } else {
        mysql_query("UPDATE `aloaz_db`.`transactions` SET payment_status=2,update_date='".$date."' where id=" . $o_id);

        echo '0';
    }
}else{
    echo "0";
}
?>