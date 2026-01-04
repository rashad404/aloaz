<?php

$Database_host = "localhost";
$Database_user = "admin";
$Database_pass = "G1Q59638cL3";
$Database_name = "admin_alochat";

$link=mysql_connect ($Database_host, $Database_user, $Database_pass);
Mysql_query("set NAMES utf8",$link);
mysql_select_db($Database_name);

/*$start = time()+microtime();
$offset_result = mysql_query( " SELECT FLOOR(RAND() * COUNT(*)) AS `offset` FROM `user` ");
$offset_row = mysql_fetch_object( $offset_result );
$offset = $offset_row->offset;
$result = mysql_fetch_assoc(mysql_query( " SELECT * FROM `user` LIMIT $offset, 1 " ));
var_dump($result);
$end = time()+microtime();
$diff = $end - $start;
echo '<br/>Fuadin kodu -> '.$diff.'<br/><hr />';

$start = time()+microtime();
$min = mysql_fetch_assoc(mysql_query("SELECT id FROM `user` ORDER BY id ASC"));
$max = mysql_fetch_assoc(mysql_query("SELECT id FROM `user` ORDER BY id DESC"));
$r = rand($min["id"],$max["id"]);
$result = mysql_fetch_assoc(mysql_query(" SELECT * FROM `user` WHERE id > $r limit 1"));
var_dump($result);
$end = time()+microtime();
$diff = $end - $start;
echo '<br/> rand istifade edilmeden yazilan -> '.$diff.'<br/><hr />';

$start = time()+microtime();
$result = mysql_fetch_assoc(mysql_query( " SELECT * FROM `user` ORDER BY rand() limit 1" ));
var_dump($result);
$end = time()+microtime();
$diff = $end - $start;
echo '<br/> mysql rand -> '.$diff.'<br/>';$start = time()+microtime();


$result = mysql_fetch_assoc(mysql_query( " SELECT * FROM `user` ORDER BY rand() limit 1" ));
var_dump($result);
$end = time()+microtime();
$diff = $end - $start;
echo '<br/> mysql rand -> '.$diff.'<br/>';*/



?>