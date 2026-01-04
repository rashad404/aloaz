<?php
//echo '<meta charset="UTF-8">'; 
//include('simple_html_dom.php');
$url=$_GET["url"]; 
$url  = str_replace("`and`","&",$url);
if(isset($_GET["axsamPhoto"]) and isset($_GET["axsamPhoto"])==1){
	$url  = str_replace("/","%2F",$url);
	$url  = str_replace("=","%3D",$url);
	$url  = str_replace("+","%2B",$url);
	$url  = str_replace("http:%2F%2F","http://",$url);
	$url  = str_replace(".az%2F%2F",".az//",$url);
	$url  = str_replace("site%2F","site/",$url);
	$url  = str_replace("yol%3D","yol=",$url);
} 
$html  = file_get_contents($url);
echo $html;
/*
$c = curl_init($url);
curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
//curl_setopt(... other options you want...)

$html = curl_exec($c);

if (curl_error($c))
    die(curl_error($c));
echo $html;
// Get the status code
$status = curl_getinfo($c, CURLINFO_HTTP_CODE);

curl_close($c);
*/
?>