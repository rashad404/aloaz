<?
include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';

$minus_bal_m = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `chat_users` WHERE `hhh` < 0"), 0);
$minus_bal = mysql_result(mysql_query("SELECT COUNT(`id`) FROM aloaz_db.`user` WHERE `coins` < 0"), 0);

echo '<span style="font-size: 24px">0 -dan az bali olanlar: <br/><br/>';
echo 'm.alo.az: <b>'.$minus_bal_m.'</b><br/>';
echo 'alochat.com: <b>'.$minus_bal.'</b></span><br/>';
?>