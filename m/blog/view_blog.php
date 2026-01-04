<?

include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

$_id = intval($_GET['id']);

$query = mysql_query("SELECT * FROM `blog_list` WHERE `id` = '".$_id."';");
$row = mysql_fetch_array($query);

$blogid = $row['id'];
$catid = $row['catid'];
$uid = $row['uid'];
$name = replaceLatin_E(stripslashes($row['name']));
$body = replaceLatin_E(stripslashes($row['body']));
$read = $row['read'];
$image = $row['image'];
$date = $row['date'];

$title = ''.$name.' - Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

$str_search  = array('big.az', 'wap.', 'b i g', 'b.i.g', 'b_i_g', 'b-i-g', 'b*i*g', 'b,i,g');
$str_replace = array('.');
$body = str_ireplace($str_search, $str_replace, $body);

$query = mysql_query("SELECT `name` FROM `blog_cat` WHERE `id` = '".$catid."';");
$catname = mysql_result($query, 0);

echo '<div class="mnav"><a href="index.php">Bloq</a> » <a href="cat.php?id='.$catid.'">'.$catname.'</a></div>';
echo '<div class="layer">';

echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <span style="background-color: #ebebeb"> '.$name.' </span><br/>';
echo 'Tarix: '.date('d-m-Y H:i', $date).'<br/>';
$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
$u_login = mysql_result($u_query, 0);
echo 'Müellif: <a href="../profile.php?uid='.$uid.'">'.$u_login.'</a><br/><br/>';

if(!empty($image)){
	echo '<a href="uploads/images/'.$image.'"><img src="thumbs/medium/'.$image.'" alt="." /></a> ';
	$query_gallery = mysql_query("SELECT `fname` FROM `blog_uploads` WHERE `bid` = '".$blogid."' ORDER BY `date` ASC LIMIT 1, 5;");
	while($row_gallery = mysql_fetch_array($query_gallery)){
		$fname = $row_gallery['fname'];
		echo '<a href="uploads/images/'.$fname.'"><img src="thumbs/medium/'.$fname.'" alt="." /></a> ';
	}
	echo '<br/><br/>';
}

$body = str_replace("\n", '<br/>', $body);

echo $body.'<br/><br/>';
echo 'Oxunub: '.$read.'<br/>';

$count_com = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_com` WHERE `bid` = '".$blogid."';"), 0);
echo '<a href="comments.php?id='.$blogid.'">Şerhler: '.$count_com.'</a><br/>';
mysql_query("UPDATE `blog_list` SET `read` = `read` + 1 WHERE `id` = '".$_id."' LIMIT 1;");

echo '<br/><a href="javascript:history.back(1)">« Geri</a>';

?><script type="text/javascript">
document.write('<scr'+'ipt type="text/javascript" src="//mobilink.az/pub/17218?t='+new Date().getTime()+'" charset="utf-8" ></scr'+'ipt>');
</script><?
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
