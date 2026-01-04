<?php
		$Database_host = "localhost";
		$Database_user = "admin";
		$Database_pass = "G1Q59638cL3";
		$Database_name = "admin_alochat";
		
	$link=mysql_connect ($Database_host, $Database_user, $Database_pass);
	Mysql_query("set NAMES utf8",$link);
	mysql_select_db($Database_name);

	$start = time()+microtime();
	echo 'tes1t';
	
	
		$select = 'U.nickname,U.full_name,U.age,U.user_value,U.id,U.sex,U.point,U.profile_photo,U.last_activity,U.last_activity_round,U.last_post,U.city_id,C.`name` ';
        $from = '`user` U LEFT JOIN city C ON U.city_id=C.id';
        $where = 'U.id!=1 and U.status=10 and U.role=2 and U.deactive=0 and U.age>=18 and U.age<=60';
        $order = 'last_activity_round DESC,point DESC,user_value DESC,level DESC,profile_photo_id DESC,id  DESC';


	$query = mysql_query("SELECT nickname,full_name,age,user_value,id,sex,point,profile_photo,last_activity,last_activity_round,last_post,city_id FROM `user` WHERE 
	id!=1 and status=10 and role=2 and deactive=0 and age>=18 and age<=60
	ORDER BY ".$order." LIMIT 12,15");
	
	// while($array = mysql_fetch_array($query)){
		// echo $array['id'].'<br/>';
	// }
	while($array = mysql_fetch_array($query)){
        echo $array["nickname"]."<br />";
    }
	
	
	
	$end = time()+microtime();
	$diff = $end - $start;
	echo '<br/>'.$diff.'<br/>';
?>