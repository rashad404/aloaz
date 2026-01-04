<?
include '../../inc/config.php';

/* Qisa nomreler ve tarifleri:
9136 – 0,50 azn
9142 – 1,00 azn
9143 - 2,00 azn
9148 – 5,00 azn
*/

$key = mysql_escape_string($_POST["key"]);
$id = intval($_POST["id"]);
$shortnumber = intval($_POST["shortnumber"]);
$smstext = mysql_escape_string($_POST["smstext"]);

if($key != "c2b6c02a593bfcb10a70de10c5d39dcf") exit; // Tehlukesizlik ucun vacibdir. Mezmun.az -da qeyd etdiyiniz key burdaki ile eyni olmalidir.
if(!empty($smstext) && $id == 0){
	$exp = explode("-", $smstext);
	$id = $exp[1];
	$id = preg_replace("/[^0-9]/","",$id);
}

$bal_array = array(
"9136" => "20",  	//10 bali deyise bilersiniz
"9142" => "50",  	//25 bali deyise bilersiniz
"9143" => "120",  	//60 bali deyise bilersiniz
"9148" => "300");	//150 bali deyise bilersiniz
$bal = $bal_array[$shortnumber];
if($bal < 1) exit;
if($id < 1) exit;

if($subs > 1){
	$bal_bonus = $bal + ($bal*20/100)*($subs-1);
	if($bal_bonus > $bal*2) $bal_bonus = $bal*2;
	$bal = intval($bal_bonus);
}

// bu sorgunu oz skriptinize uygunlasdirmalisiniz

mysql_query("UPDATE `chat_users` SET `hhh` = `hhh` + ".$bal.", `iii` = `iii` + ".$bal." WHERE `id`= ".$id." LIMIT 1");
if(mysql_affected_rows() > 0) echo $bal." bal hesabiniza yuklenildi. Tesekkur edirik!";

?>