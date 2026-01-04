<?php

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

// $DB_HOST = "localhost";
// $DB_USER = "aloaz_db";	
// $DB_PASS = "s85kv25cPwL";
// $DB_NAME = "aloaz_db";

$DB_HOST = "localhost";
$DB_USER = "aloaz_chat";
$DB_PASS = "=OMoU{h@kMKo";
$DB_NAME = "aloaz_db";

date_default_timezone_set('Asia/Baku');

$mysql_connect = @mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$mysql_connect) exit('DB Error (101)');
mysql_set_charset('utf8', $mysql_connect);

$selectdb = mysql_select_db($DB_NAME, $mysql_connect);

if(!$selectdb) exit('DB Error (102)' . mysql_error());

define('DOCUMENT_ROOT','/home/aloaz/public_html/m/');
include_once(DOCUMENT_ROOT .'simaz/config.php');
?>
