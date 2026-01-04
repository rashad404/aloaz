<?php

function validateUrl($url){
	$urlregex = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";
	if(eregi($urlregex, $url)) return true; else return false;
}

function country($ip, $type){
	$query = mysql_query("SELECT * FROM `ip_group_country` where `ip_start` <= INET_ATON('".$ip."') order by `ip_start` desc limit 1;");
	$get = mysql_fetch_array($query);
	$country_code = $get['country_code'];
	
	if($type == "2code"){
		return $country_code;
	}
	elseif($type == "name"){
		$query = mysql_query("SELECT * FROM `iso3166_countries` where `code` = '".$country_code."' order by `code` desc limit 1;");
		$get = mysql_fetch_array($query);
		$country_name = $get['name'];
		return $country_name;
	}
}

function countryby2letter($data){
	$query = mysql_query("SELECT * FROM `ip_group_country` where `ip_start` <= INET_ATON('".$ip."') order by `ip_start` desc limit 1;");
	$get = mysql_fetch_array($query);
	$country_code = $get['country_code'];
	
	if($type == "2code"){
		return $country_code;
	}
	elseif($type == "name"){
		$query = mysql_query("SELECT * FROM `iso3166_countries` where `code` = '".$country_code."' order by `code` desc limit 1;");
		$get = mysql_fetch_array($query);
		$country_name = $get['name'];
		return $country_name;
	}
}

function checkData($data){
	$search = array("`", ",", "union");
	$replace = array("", "", "");
	$data = str_replace($search, $replace, $data);
	$data = htmlspecialchars(trim($data));
	return $data;
}

function SqlInjectFilter($str) {
    $str = str_replace(" ",'',$str);
    $str = mysql_real_escape_string($str);
    $str = str_replace("\n",'',$str);
    $str = str_replace("\t",'',$str);
    $str = str_replace("\r",'',$str);
    $str = str_replace("\0",'',$str);
    $str = str_replace("\x0B",'',$str);
    $str = str_replace("'",'',$str);
    $str = str_replace('"','',$str);
    $str = str_replace('\\','',$str);
    $str = str_replace('/','',$str);
    $str = str_ireplace (" and ","",$str);
    $str = str_ireplace ("execute ","",$str);
    $str = str_ireplace ("update ","",$str);
    $str = str_ireplace ("count ","",$str);
    $str = str_ireplace ("chr ","",$str);
    $str = str_ireplace ("mid ","",$str);
    $str = str_ireplace ("master ","",$str);
    $str = str_ireplace ("truncate ","",$str);
    $str = str_ireplace ("char ","",$str);
    $str = str_ireplace ("declare ","",$str);
    $str = str_replace ("select ","",$str);
    $str = str_ireplace ("create ","",$str);
    $str = str_ireplace ("delete ","",$str);
    $str = str_ireplace ("insert ","",$str);
    $str = str_ireplace ("union ","",$str);
    $str = str_replace ("\"","",$str);
    $str = str_replace ('"',"",$str);
    //$str = str_replace (" ","",$str);
    $str = str_replace ("$","",$str);
    $str = str_ireplace ("or ","",$str);
    //$str = str_replace ("=","",$str);
    $str = str_replace ("% 20 ","",$str);
    $str = addslashes($str);
    return $str;
}

function getTagValue ($string, $tag) {
	$z=strpos ($string, "<".$tag.">")+strlen ($tag)+2;
	$s=substr ($string, $z, strpos ($string, "</".$tag.">")-$z);
	return $s;
}

function checkIp($ip, $operator){
	
	if(strtolower($operator) == "azercell"){
		if($ip == "217.168.176.4" or $ip == "217.168.176.3" or $ip == "217.168.176.18"){
			return true;
		}
		else{
			return false;
		}
	}
}

function encode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    for ($i = 0; $i < $strLen; $i++) {
        $ordStr = ord(substr($string,$i,1));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= strrev(base_convert(dechex($ordStr + $ordKey),16,36));
    }
    return $hash;
}

function decode($string,$key) {
    $key = sha1($key);
    $strLen = strlen($string);
    $keyLen = strlen($key);
    $j = 0;
    for ($i = 0; $i < $strLen; $i+=2) {
        $ordStr = hexdec(base_convert(strrev(substr($string,$i,2)),36,16));
        if ($j == $keyLen) { $j = 0; }
        $ordKey = ord(substr($key,$j,1));
        $j++;
        $hash .= chr($ordStr - $ordKey);
    }
    return $hash;
}

function copyFile($url, $newfile){ 
    @$file = fopen ($url, "r"); 
    if(!$file){  
        return false; 
    }else{ 
       // $filename = basename($url); 
        $fc = fopen($newfile, "wb"); 
        while(!feof ($file)){ 
           $line = fread ($file, 1028); 
           fwrite($fc,$line); 
        } 
        fclose($fc); 
        return true; 
    } 
}

function format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 2).$units[$i];
}

function detectCarrier($ip){
	$ip = explode('.', $ip);
	$ip = $ip[0].'.'.$ip[1].'.'.$ip[2];
	
	$op_array = array(
		'217.168.176' => 'Azercell', 
		'85.132.57' => 'Bakcell', 
		'85.132.75' => 'Bakcell', 
		'176.28.80' => 'Bakcell', 
		'176.28.81' => 'Bakcell', 
		'176.28.87' => 'Bakcell', 
		'77.244.112' => 'Azerfon'
	);
	if(array_key_exists($ip, $op_array)) return $op_array[$ip]; else return 'other';
}

function get_operator($ip){
	$ip = explode('.', $ip);
	$ip = $ip[0].'.'.$ip[1].'.'.$ip[2];
	
	$op_array = array(
		'217.168.176' => 'Azercell', 
		'217.168.179' => 'Azercell', 
		'217.168.181' => 'Azercell', 
		'217.168.182' => 'Azercell', 
		'217.168.183' => 'Azercell', 
		'217.168.184' => 'Azercell', 
		'217.168.185' => 'Azercell', 
		'217.168.186' => 'Azercell', 
		'217.168.187' => 'Azercell', 
		'217.168.188' => 'Azercell', 
		'217.168.190' => 'Azercell', 
		'217.168.191' => 'Azercell', 
		'46.23.104' => 'Azercell', 
		'46.23.105' => 'Azercell', 
		'46.23.106' => 'Azercell', 
		'46.23.107' => 'Azercell', 
		'94.20.230' => 'Azercell', 
		'85.132.57' => 'Bakcell', 
		'85.132.75' => 'Bakcell', 
		'176.28.80' => 'Bakcell', 
		'176.28.81' => 'Bakcell', 
		'176.28.82' => 'Bakcell', 
		'176.28.87' => 'Bakcell', 
		'5.44.39' => 'Bakcell', 
		'5.44.38' => 'Bakcell', 
		'77.244.112' => 'Azerfon', 
		'77.244.114' => 'Azerfon', 
		'77.244.115' => 'Azerfon'
	);
	if(array_key_exists($ip, $op_array)) return $op_array[$ip]; else return 'other';
}

function replaceLatin($str){
	$search  = array('Ə', 'ə', 'Ö', 'ö', 'Ş', 'ş', 'Ğ', 'ğ', 'Ç', 'İ', 'ı', 'Ü', 'ü');
	$replace = array('E', 'e', 'O', 'o', 'S', 's', 'G', 'g', 'c', 'I', 'i', 'U', 'u');
	$str = str_replace($search, $replace, $str);
	return $str;
}

function replaceLatin_E($str){
	$search  = array('Ə', 'ə');
	$replace = array('E', 'e');
	$str = str_replace($search, $replace, $str);
	return $str;
}

function mysqlConnect(){
	$DB_HOST = "localhost";
	$DB_USER = "admin_db";	
	$DB_PASS = "mv97CvrC1o";
	$DB_NAME = "admin_db";
	
	$mysql_connect = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	if(!$mysql_connect) exit('Zehmet olmasa bir neçe deqiqeden sonra yoxlayın (101)');
	//mysql_set_charset('utf8', $mysql_connect);
	
	$selectdb = mysql_select_db($DB_NAME, $mysql_connect);
	if(!$selectdb) exit('Zehmet olmasa bir neçe deqiqeden sonra yoxlayın (102)');
}

function mysqlConnectToChat(){
	$DB_HOST = "localhost";
	$DB_USER = "aloaz_db";	
	$DB_PASS = "s85kv25cPwL";
	$DB_NAME = "aloaz_db";
	
	$mysql_connect = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
	if(!$mysql_connect) exit('Zehmet olmasa bir neçe deqiqeden sonra yoxlayın (201)');
	
	$selectdb = mysql_select_db($DB_NAME, $mysql_connect);
	if(!$selectdb) exit('Zehmet olmasa bir neçe deqiqeden sonra yoxlayın (202)');
}

function checkAuth2(){
	if(isset($_POST['login']) && isset($_POST['password'])){
		$login = checkData($_POST['login']);
		$password = md5(trim($_POST['password']));
		
		$_SESSION['login'] = $login;
		$_SESSION['password'] = $password;
	}
	else{
		$login = checkData($_SESSION['login']);
		$password = checkData($_SESSION['password']);
	}
	
	if(empty($login) && empty($password)) $error_code = '1'; else $error_code = '2';
	
	$user_query = mysql_query("SELECT * FROM `users` WHERE `login` = '".$login."' AND `password` = '".$password."';");

	if(mysql_num_rows($user_query) == 0){
		return 'error';
	}
	else{
		$_SESSION['auth'] = true;
		return $user_query;
	}
}

function checkAuthBlog($sqlFields = '*'){
	if(isset($_POST['id']) && isset($_POST['password'])){
		$id = intval($_POST['id']);
		$password = md5(trim($_POST['password']));
		
		$_SESSION['id'] = $id;
		$_SESSION['password'] = $password;
	}
	else if(isset($_GET['id']) && isset($_GET['password'])){
		$id = intval($_GET['id']);
		$password = trim($_GET['password']);
		
		$_SESSION['id'] = $id;
		$_SESSION['password'] = $password;
	}
	else{
		$id = intval($_SESSION['id']);
		$password = checkData($_SESSION['password']);
	}
	
	if(empty($id) && empty($password)) $error_code = '1'; else $error_code = '2';
	
	$user_query = mysql_query("SELECT ".$sqlFields." FROM `chat_users` WHERE `id` = '".$id."' AND `md5_pass` = '".$password."';");

	if(mysql_num_rows($user_query) == 0){
		return 'error';
	}
	else{
		$_SESSION['auth'] = true;
		return $user_query;
	}
}

function checkAuth($sqlFields = '*'){
	if(isset($_POST['login']) && isset($_POST['password'])){
		$login = checkData($_POST['login']);
		$login = SqlInjectFilter($login);
		$password = md5(trim($_POST['password']));
		
		$_SESSION['login'] = $login;
		$_SESSION['password'] = $password;
	}
	else{
		$login = checkData($_SESSION['login']);
		$password = checkData($_SESSION['password']);
	}
	
	if(empty($login) && empty($password)) $error_code = '1'; else $error_code = '2';
	
	if(strlen(phoneFormat($login)) == 12 && is_numeric($login)){
		$ins_login = "(`nickname` = '".$login."' OR `phone` = '".phoneFormat($login)."') ";
	}
	else{
		$ins_login = "`nickname` = '".$login."' ";
	}
	

	$user_query = mysql_query("SELECT ".$sqlFields." FROM `chat_users` WHERE ".$ins_login." AND `md5_pass` = '".$password."';");

	if(mysql_num_rows($user_query) == 0){
		$_SESSION['auth'] = false;
		return 'error';
	}
	else{
		$_SESSION['auth'] = true;
		return $user_query;
	}

}

function displayError($txt, $type){
	if($type == 0){
		$title = 'Diqqet!';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';
		echo '<div class="mnav">'.$title.'</div>';
		echo '<div class="layer">'.$txt.'</div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	elseif($type == 1){
		$title = 'Diqqet!';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';
		echo '<div class="mnav">'.$title.'</div>';
		echo '<div class="layer">'.$txt.'</div>';
	}
	elseif($type == 2){
		echo $txt.'</div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	else{
		echo 'Sehv: '.$txt;
	}
}

function createthumb2($name,$filename,$new_w,$new_h, $proportion){
	$maxWidth = $new_w; $maxHeight = $new_h;
        list($width, $height) = getimagesize($name);
        if($width>=$height){
            $d = $width/$maxWidth;
        }else{
            $d = $height/$maxHeight;
        }

        $h = $height/$d;
        $w = $width/$d;
		if($h>$maxHeight){
			$new_h=$maxHeight;
		}else{
			$new_h= $h;
		}
		
		if($w>$maxWidth){
			$d = $width/$maxWidth;
			$new_h = $height/$d;
		}
        createthumb($name,$filename,$new_w,$new_h, $proportion);
}


function createthumb($name,$filename,$new_w,$new_h, $proportion){
	$fm = strtolower($name); 
	//$exts = split("[/\\.]", $fm); 
	//$n = count($exts)-1; 
	//$ext = strtolower($exts[$n]);
	$ext = end(explode('.',$fm));

	if($ext == "jpg" || $ext == "jpeg"){$src_img=imagecreatefromjpeg($name);}
	if($ext == "png"){$src_img=imagecreatefrompng($name);}
	if($ext == "gif"){$src_img=imagecreatefromgif($name);}
	
	$old_x=imageSX($src_img);
	$old_y=imageSY($src_img);
	
	if($proportion == 1){
		$thumb_w=$new_w;
		$thumb_h=$new_h;
	}
	else{
		$percent = $new_h*100/$old_y;
		$thumb_w = number_format(($old_x*$percent/100));
		$thumb_h=$new_h;
		
	}
	$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
	imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 
	if (preg_match("/png/",$ext)){
		imagepng($dst_img,$filename); 
	}
	elseif(preg_match("/gif/",$ext)){
		imagegif($dst_img,$filename, 90); 
	}
	else{
		imagejpeg($dst_img,$filename, 80);
	}
	imagedestroy($dst_img); 
	imagedestroy($src_img);
}


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

	if(intval($output) == 1) return true; else return false;
}


function smsgenSendSMS($msisdn, $smstext){
	$url = 'http://smsgen.net/send_sms/api.php';
	$postData = "&user=994702610777&pass=nebilim&to=$msisdn&sms_message=$smstext";

	$ch = curl_init();  
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch,CURLOPT_HEADER, false); 
	curl_setopt($ch, CURLOPT_POST, count($postData));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
	$output = curl_exec($ch);
	curl_close($ch);
	
	if(!empty($output)) return true; else return false;
}


function phoneFormat($phone){
	$phone = str_replace(' ', '', $phone);
	$phone = str_replace('+', '', $phone);
	if(preg_match("/[0-9]{9}/", intval($phone)) && strlen(intval($phone)) == 9) $phone = '994'.intval($phone);
	return $phone;
}


function updateOnline(){
	global $id, $_rid;

	$_rid = intval($_rid);
	$time = time();
	$online = $time + 600;
	$online_round = substr($time,0,7);
	
	$update = mysql_query("UPDATE `chat_users` SET `time` = '".$online."',`last_activity_round` = '".$online_round."' ,`place` = '".$_rid."', `onfrom` = 'mobile', `ip` = '".getenv('REMOTE_ADDR')."', `ua` = '".htmlspecialchars(getenv('HTTP_USER_AGENT'))."' WHERE `id` = '".$id."' LIMIT 1;");
	if($update) return true; else return false;
}

function countryCodeName($code){
	$countryArray = array("a1" => "Anonymous Proxy", "a2" => "Satellite Provider", "ad" => "Andorra", "ae" => "United Arab Emirates", "af" => "Afghanistan", "ag" => "Antigua and Barbuda", "ai" => "Anguilla", "al" => "Albania", "am" => "Armenia", "ao" => "Angola", "ap" => "Asia/Pacific Region", "aq" => "Antarctica", "ar" => "Argentina", "as" => "American Samoa", "at" => "Austria", "au" => "Australia", "aw" => "Aruba", "ax" => "Aland Islands", "az" => "Azerbaijan", "ba" => "Bosnia and Herzegovi", "bb" => "Barbados", "bd" => "Bangladesh", "be" => "Belgium", "bf" => "Burkina Faso", "bg" => "Bulgaria", "bh" => "Bahrain", "bi" => "Burundi", "bj" => "Benin", "bl" => "Saint Barthelemy", "bm" => "Bermuda", "bn" => "Brunei Darussalam", "bo" => "Bolivia", "bq" => "Bonaire, Saint Eusta", "br" => "Brazil", "bs" => "Bahamas", "bt" => "Bhutan", "bw" => "Botswana", "by" => "Belarus", "bz" => "Belize", "ca" => "Canada", "cc" => "Cocos (Keeling) Isla", "cd" => "Congo, The Democrati", "cf" => "Central African Repu", "cg" => "Congo", "ch" => "Switzerland", "ci" => "Cote D'Ivoire", "ck" => "Cook Islands", "cl" => "Chile", "cm" => "Cameroon", "cn" => "China", "co" => "Colombia", "cr" => "Costa Rica", "cu" => "Cuba", "cv" => "Cape Verde", "cw" => "Curacao", "cx" => "Christmas Island", "cy" => "Cyprus", "cz" => "Czech Republic", "de" => "Germany", "dj" => "Djibouti", "dk" => "Denmark", "dm" => "Dominica", "do" => "Dominican Republic", "dz" => "Algeria", "ec" => "Ecuador", "ee" => "Estonia", "eg" => "Egypt", "er" => "Eritrea", "es" => "Spain", "eu" => "Europe", "fi" => "Finland", "fj" => "Fiji", "fk" => "Falkland Islands (Ma", "fm" => "Micronesia, Federate", "fo" => "Faroe Islands", "fr" => "France", "ga" => "Gabon", "gb" => "United Kingdom", "gd" => "Grenada", "ge" => "Georgia", "gf" => "French Guiana", "gg" => "Guernsey", "gh" => "Ghana", "gi" => "Gibraltar", "gl" => "Greenland", "gm" => "Gambia", "gn" => "Guinea", "gp" => "Guadeloupe", "gq" => "Equatorial Guinea", "gr" => "Greece", "gs" => "South Georgia and th", "gt" => "Guatemala", "gu" => "Guam", "gw" => "Guinea-Bissau", "gy" => "Guyana", "hk" => "Hong Kong", "hn" => "Honduras", "hr" => "Croatia", "ht" => "Haiti", "hu" => "Hungary", "id" => "Indonesia", "ie" => "Ireland", "il" => "Israel", "im" => "Isle of Man", "in" => "India", "io" => "British Indian Ocean", "iq" => "Iraq", "ir" => "Iran, Islamic Republ", "is" => "Iceland", "it" => "Italy", "je" => "Jersey", "jm" => "Jamaica", "jo" => "Jordan", "jp" => "Japan", "ke" => "Kenya", "kg" => "Kyrgyzstan", "kh" => "Cambodia", "ki" => "Kiribati", "km" => "Comoros", "kn" => "Saint Kitts and Nevi", "kp" => "Korea, Democratic Pe", "kr" => "Korea, Republic of", "kw" => "Kuwait", "ky" => "Cayman Islands", "kz" => "Kazakhstan", "la" => "Lao People's Democra", "lb" => "Lebanon", "lc" => "Saint Lucia", "li" => "Liechtenstein", "lk" => "Sri Lanka", "lr" => "Liberia", "ls" => "Lesotho", "lt" => "Lithuania", "lu" => "Luxembourg", "lv" => "Latvia", "ly" => "Libya", "ma" => "Morocco", "mc" => "Monaco", "md" => "Moldova, Republic of", "me" => "Montenegro", "mf" => "Saint Martin", "mg" => "Madagascar", "mh" => "Marshall Islands", "mk" => "Macedonia", "ml" => "Mali", "mm" => "Myanmar", "mn" => "Mongolia", "mo" => "Macau", "mp" => "Northern Mariana Isl", "mq" => "Martinique", "mr" => "Mauritania", "ms" => "Montserrat", "mt" => "Malta", "mu" => "Mauritius", "mv" => "Maldives", "mw" => "Malawi", "mx" => "Mexico", "my" => "Malaysia", "mz" => "Mozambique", "na" => "Namibia", "nc" => "New Caledonia", "ne" => "Niger", "nf" => "Norfolk Island", "ng" => "Nigeria", "ni" => "Nicaragua", "nl" => "Netherlands", "no" => "Norway", "np" => "Nepal", "nr" => "Nauru", "nu" => "Niue", "nz" => "New Zealand", "om" => "Oman", "pa" => "Panama", "pe" => "Peru", "pf" => "French Polynesia", "pg" => "Papua New Guinea", "ph" => "Philippines", "pk" => "Pakistan", "pl" => "Poland", "pm" => "Saint Pierre and Miq", "pn" => "Pitcairn Islands", "pr" => "Puerto Rico", "ps" => "Palestinian Territor", "pt" => "Portugal", "pw" => "Palau", "py" => "Paraguay", "qa" => "Qatar", "re" => "Reunion", "ro" => "Romania", "rs" => "Serbia", "ru" => "Russian Federation", "rw" => "Rwanda", "sa" => "Saudi Arabia", "sb" => "Solomon Islands", "sc" => "Seychelles", "sd" => "Sudan", "se" => "Sweden", "sg" => "Singapore", "sh" => "Saint Helena", "si" => "Slovenia", "sj" => "Svalbard and Jan May", "sk" => "Slovakia", "sl" => "Sierra Leone", "sm" => "San Marino", "sn" => "Senegal", "so" => "Somalia", "sr" => "Suriname", "ss" => "South Sudan", "st" => "Sao Tome and Princip", "sv" => "El Salvador", "sx" => "Sint Maarten (Dutch ", "sy" => "Syrian Arab Republic", "sz" => "Swaziland", "tc" => "Turks and Caicos Isl", "td" => "Chad", "tf" => "French Southern Terr", "tg" => "Togo", "th" => "Thailand", "tj" => "Tajikistan", "tk" => "Tokelau", "tl" => "Timor-Leste", "tm" => "Turkmenistan", "tn" => "Tunisia", "to" => "Tonga", "tr" => "Turkey", "tt" => "Trinidad and Tobago", "tv" => "Tuvalu", "tw" => "Taiwan", "tz" => "Tanzania, United Rep", "ua" => "Ukraine", "ug" => "Uganda", "um" => "United States Minor ", "us" => "United States", "uy" => "Uruguay", "uz" => "Uzbekistan", "va" => "Holy See (Vatican Ci", "vc" => "Saint Vincent and th", "ve" => "Venezuela", "vg" => "Virgin Islands, Brit", "vi" => "Virgin Islands, U.S.", "vn" => "Vietnam", "vu" => "Vanuatu", "wf" => "Wallis and Futuna", "ws" => "Samoa", "ye" => "Yemen", "yt" => "Mayotte", "za" => "South Africa", "zm" => "Zambia", "zw" => "Zimbabwe");
	return $countryArray[$code];
}

?>
