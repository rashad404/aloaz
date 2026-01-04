<?
include 'inc/func.php';
include 'inc/functions.php';
include 'inc/lang/pack.php';
include 'inc/config.php';

include 'inc/mobilinkad.php';

$date = date('Y-m-01 00:00:00');
$str = strtotime($date);

echo $date;
exit;
?>



<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
$.extend($.expr[':'], {
    focused: function(elem) { return elem.hasFocus; }
});

alert($('input :focused').length);

</script>

<?
echo '<a href="?">test click</a>';
echo "<div class=\"mobilinkdiv\">";
echo mobilink_ad($mobilink_params);
echo "</div>";

/*
$query = mysql_query("SELECT * FROM `chat_users` WHERE `created_at` = '' ORDER BY `id` DESC LIMIT 100");
while($row = mysql_fetch_array($query)){
	$id = $row['id'];
	$reggun = $row['reggun'];
	$regay = $row['regay'];
	$regil = $row['regil'];
	$utime = strtotime(''.$regil.'-'.$regay.'-'.$reggun.'');
	echo $id.' '.$regil.'-'.$regay.'-'.$reggun.' = '.$utime.' = '.date('Y-m-d H:i', $utime).'<br/>';
	//mysql_query("UPDATE `chat_users` SET `created_at` = '".$utime."' WHERE `id` = '".$id."' LIMIT 1;");
}
*/

/*
$query = mysql_query("SELECT * FROM `chat_users` WHERE `birthday` = '' ORDER BY `id` DESC LIMIT 5000");
while($row = mysql_fetch_array($query)){
	$id = $row['id'];
	$gun = $row['gun'];
	$ay = $row['ay'];
	$il = $row['il'];
	//$utime = strtotime(''.$regil.'-'.$regay.'-'.$reggun.'');
	$birthday = ''.$il.'-'.$ay.'-'.$gun.'';
	echo $id.' '.$il.'-'.$ay.'-'.$gun.'<br/>';
	mysql_query("UPDATE `chat_users` SET `birthday` = '".$birthday."' WHERE `id` = '".$id."' LIMIT 1;");
}
*/

/*
$query = mysql_query("SELECT * FROM `chat_users` WHERE `age` = '' ORDER BY `id` DESC LIMIT 5000");
while($row = mysql_fetch_array($query)){
	$id = $row['id'];
	$birthday = $row['birthday'];
	
	$age = floor((time() - strtotime($birthday)) / (24*3600*365));
	
	echo $birthday.' = '.$age.'<br/>';
	mysql_query("UPDATE `chat_users` SET `age` = '".$age."' WHERE `id` = '".$id."' LIMIT 1;");
	
}
*/


echo mysql_error();
?>