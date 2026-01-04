<?
function sendSMS($msisdn, $smstext){
	$url = 'http://infomob.az/tools/sendsms/sms.php';
	$postData = "msisdn=$msisdn&smstext=$smstext&shortnum=9136&from=alo.az&key=".md5("$msisdn-Xp4E2cKoz0E")."";

	$ch = curl_init();  
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_POST, count($postData));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
	$output = curl_exec($ch);
	curl_close($ch);
	
	//if($output == 'success') return true; else return false;
	print_r($output);
}

echo sendSMS(994502365333, 'test 111');
?>
