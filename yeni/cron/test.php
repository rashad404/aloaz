<?php 
         $startTime = time()+microtime();

error_reporting(0);

session_start();
include '../inc/config.php'; 

 $sql = mysql_query("select * from chat_messages");
         $endtime = time()+microtime();
		 echo $endtime-$startTime; 

?>