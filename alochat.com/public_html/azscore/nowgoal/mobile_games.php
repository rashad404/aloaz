<?php
	// $url = 'http://m.nowgoal.cc/phone/Schedule_4_0.txt?flesh=0.22721844143234193';
  	$url = 'http://m.nowgoal.id/phone/Schedule_4_0.txt?flesh=0.22721844143234193';
  	$url = 'http://m.nowgoal.top/phone/Schedule_4_0.txt?flesh=0.22721844143234193';
	$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8";
	$header[] = "Accept-Encoding: gzip, deflate";
	$header[] = "Cache-Control: no-cache";
	$header[] = "Connection: keep-alive";
	$header[] = "Keep-Alive: 300";
	$header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$header[] = "Accept-Language: en-US,en;q=0.9";
	$header[] = "Pragma: "; // BROWSERS USUALLY LEAVE THIS BLANK

	if( $curl = curl_init() ) {
		curl_setopt( $curl, CURLOPT_URL,            $url  );
		curl_setopt($curl, CURLOPT_USERAGENT, 		'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36');
		curl_setopt( $curl, CURLOPT_HTTPHEADER,     $header  );
		curl_setopt( $curl, CURLOPT_REFERER,        'http://www.google.com');
		curl_setopt( $curl, CURLOPT_ENCODING,       'gzip,deflate'  );
		curl_setopt( $curl, CURLOPT_AUTOREFERER,    TRUE  );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, TRUE  );
		curl_setopt( $curl, CURLOPT_TIMEOUT,        5  );
		$source = curl_exec($curl);
		echo $source;
		curl_close($curl);
	}
	
?>