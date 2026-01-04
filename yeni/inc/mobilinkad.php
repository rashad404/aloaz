<?php
/**
* Info		: mobilink.az Publisher Code (PHP)
* Version	: 20140719
* Copyright	: mobilink.az, All rights reserved
*/

$mobilink_params = array(
 'publisher_id'		=> '1', 
 'ad_type'		=> 'Text+Image', 
 'allow_adult_ads'	=> 'No', // else No
 'keywords'		=> '' 
);

//Bu funksiyani kopyalayib istediyiniz sehifeye qoyub reklami gostere bilersiniz.
//echo mobilink_ad($mobilink_params);

/************************************************
* Ashagidaki kodlari deyishmek qeti qadagandir! *
************************************************/

function mobilink_ad($mobilink_params){
$protocol = 'http';
if(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off') $protocol = 'https';

$params = array(
 'ip='.urlencode($_SERVER['REMOTE_ADDR']), 
 'ua='.urlencode($_SERVER['HTTP_USER_AGENT']), 
 'uri='.urlencode($protocol."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']), 
 'version=20140719'
);

mysql_query("INSERT INTO `ad_logs` SET `uri` = '".$_SERVER['REQUEST_URI']."', `ip` = '".$_SERVER['REMOTE_ADDR']."', `ua` = '".$_SERVER['HTTP_USER_AGENT']."'");

foreach($_SERVER as $k => $v) {
	if(substr($k, 0, 4) == 'HTTP') {
		$mobilink_params[$k] = urlencode($v);            
	}
}

while (list($key, $value) = each($mobilink_params)) { 
	$m_params .= $key.'='.$value.'&';
}

$post = $m_params.implode('&', $params);

$timeout = 1; // 1 second
$contents = '';
$errno = 0;
$errstr = '';

$request = @fsockopen('mobilink.az', 80, $errstr, $errno, $timeout);
	if($request){
		stream_set_timeout($request, $timeout);
		$requestBody = "POST /ad.php HTTP/1.0\r\nHost: mobilink.az\r\nContent-Type:application/x-www-form-urlencoded\r\nContent-Length: " . strlen($post) . "\r\n\r\n" . $post;
		$bytesToWrite = strlen($requestBody);
		$bytesWritten = 0;
		$isBody = false;
		$requestInfo = stream_get_meta_data($request);
		$timeout = $requestInfo['timed_out'];
		while ($bytesWritten < $bytesToWrite && !$timeout) {
			$currentWriteBytes = fwrite($request, $requestBody);
			if ($currentWriteBytes === false) return '';
			$bytesWritten += $currentWriteBytes;
			if ($bytesWritten == $bytesToWrite) break;
			$requestBody = substr($requestBody, $bytesWritten);
			$requestInfo = stream_get_meta_data($request);
			$timeout = $requestInfo['timed_out'];
		}
		while (!feof($request) && !$timeout) {
			$line = fgets($request);
			if (!$isBody && $line == "\r\n") $isBody = true;
			if ($isBody && !empty($line)) $contents .= $line; 
			$requestInfo = stream_get_meta_data($request);
			$timeout = $requestInfo['timed_out'];
		}
		fclose($request);
	}
	return $contents;
}
?>

