<?php
$lng = $_GET['lang'];
$lng_array = array('az' => 'Azerbaycanca', 'ru' => 'Русский', 'tr' => 'Türkce', 'en' => 'English', 'vn' => 'Việt');
if(!array_key_exists($lng, $lng_array)) $lng = 'en';

setcookie("alochat_lng", $lng);

header('location: http://'.$_SERVER['HTTP_HOST'].'/?ref=setlng');

?>
