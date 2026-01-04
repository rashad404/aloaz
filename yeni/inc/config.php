<?
// $DB_HOST = "localhost";
// $DB_USER = "aloaz_db";	
// $DB_PASS = "s85kv25cPwL";
// $DB_NAME = "aloaz_db";

$DB_HOST = "localhost";
$DB_USER = "admin";	
$DB_PASS = "G1Q59638cL3";
$DB_NAME = "aloaz_db";

$mysql_connect = mysql_connect($DB_HOST,$DB_USER,$DB_PASS);
if(!$mysql_connect) exit('DB Error (101)');
mysql_set_charset('utf8', $mysql_connect);

$selectdb = mysql_select_db($DB_NAME, $mysql_connect);
if(!$selectdb) exit('DB Error (102)');


?>
