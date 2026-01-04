<?
session_start();

$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$_id = intval($_GET['id']);
$_check = intval($_GET['check']);
$_blog_id = intval($_GET['blog_id']);

echo '<div class="mnav"><a href="../index.php">Bloq</a> » Bloq tesdiqlenmesi</div>';
echo '<div class="layer">';

if($_SESSION['auth']){
	$checkAuth = checkAuth();
	if($checkAuth != 'error'){
		$userrow = mysql_fetch_array($checkAuth);
		$uid = $userrow['id'];
		$u_level = $userrow['level'];
		
		if($u_level == 0){
			echo 'Yalnız statuslu istifadeçiler bu bölmeye daxil ola biler';
			echo '</div>';
			include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
			exit();
		}
	}
}

if($_check == 1 || $_check == 2){
	mysql_query("UPDATE `blog_list` SET `status` = '".$_check."' WHERE `id` = '".$_blog_id."' AND `status` = '0' LIMIT 1");
	if(mysql_affected_rows() > 0){
		if($_check == 1) echo 'Aktivleşdirildi!<br/><br/>';
		if($_check == 2) echo 'Deaktiv olundu!<br/><br/>';
	}
}

$file_count = mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `status` = '0';");
$all_rows = mysql_result($file_count, 0);

echo 'Bu sehifeye yalnız statuslu istifadeçiler daxil ola bilir<br/>';
echo 'Yoxlanılmamış bloqlar: [<b>'.$all_rows.'</b>]<br/><br/>';

$show_limit = 10;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$query = mysql_query("SELECT * FROM `blog_list` WHERE `status` = '0' ORDER BY `date` DESC LIMIT ".$start.", ".$show_limit.";");
while($row = mysql_fetch_array($query)){
	$blogid = $row['id'];
	$uid = $row['uid'];
	$name = replaceLatin_E(stripslashes($row['name']));
	$body = $row['body'];
	$date = $row['date'];
	$catid = $row['catid'];
	$image = $row['image'];
	
	$str_search  = array('big.az', 'wap.', 'b i g', 'b.i.g', 'b_i_g', 'b-i-g', 'b*i*g', 'b,i,g', 'bebek.az', 'wen.ru');
	$str_replace = array('.');
	$name = str_ireplace($str_search, $str_replace, $name);

	$catname = mysql_result(mysql_query("SELECT `name` FROM `blog_cat` WHERE `id` = '".$catid."';"), 0);
	
	if(!empty($image)) echo '<p style="margin-top:0px;">';
	echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <a href="../view_blog.php?id='.$blogid.'">'.$name.'</a><br/>';
	if(!empty($image)) echo '<img src="../thumbs/small/'.$image.'" alt="." style="float:left; margin:2px" /> ';
	echo 'Qısa metn:<br/>'.substr($body, 0, 300).'...<br/>';
	echo 'Tarix: '.date('d-m-Y H:i', $date).'<br/>';
	echo 'Bölme: '.$catname.' | ';
	$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
	$u_login = mysql_result($u_query, 0);
	echo 'Müellif: '.$u_login.'<br/>';
	echo '<a href="?check=1&amp;blog_id='.$blogid.'">Aktivleşdir</a> / <a href="?check=2&amp;blog_id='.$blogid.'">Deaktiv</a><br/><br/>';
	if(!empty($image)) echo '</p>';
}

if($page > 1) echo "<a href=\"newblogs.php?page=".($page - 1)."\">« Evvelki</a> | ";
if($all_rows > $start + $show_limit) echo "<a href=\"newblogs.php?page=".($page + 1)."\">Növbeti »</a>";
if($page > 1 || $all_rows > $start + $show_limit) echo '<br/>';

$interval = 5;
$max = ceil($all_rows/$show_limit);
if($page > $interval) echo " <a href=\"newblogs.php?page=1\">1</a> ... ";

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo " <a href=\"newblogs.php?page=".$i."\">".$i."</a> ";
		}
		else{
			echo " ".$i." ";
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo " <a href=\"newblogs.php?page=".$i."\">".$i."</a> ";
			}
			else{
				echo " ".$i." ";
			}
		}
		
	}
}
if($page <= $max - 5) echo '... ';

if($max > $interval){
	if($max != $page){
		echo " <a href=\"newblogs.php?page=".$max."\">".$max."</a> ";
	}
	else{
		echo " ".$max." ";
	}
}

echo '<a href="javascript:history.back(1)">« Geri</a>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>