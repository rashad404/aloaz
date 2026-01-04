<?
//print_r($_SERVER);
//exit;

$_url = $_GET['url'];
$_hash = $_GET['hash'];

$_key = 'sd6w52D5dw2XC';

$url = base64_decode($_url);

if($_hash != md5($_key.$_url)) exit('Auth');

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);

curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);

$curl_data = curl_exec($ch);

echo $curl_data;
?>