<?php
session_start();
$title = 'Milli Bloq - Azerbaycanın en böyük bloqu';
include $_SERVER['DOCUMENT_ROOT'].'/inc/func.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';

include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="?">Bloq</a></div>';
echo '<div class="layer">';
echo '<b>Azerbaycanın ilk mobil bloqu</b><br/><br/>';

$cntActiveBlogs = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `status` = '1';"), 0);
echo $cntActiveBlogs.' bloq sayı ile Azerbaycanın en böyük bloq saytı olmaqdan qürur duyuruq!<br/><br/>';

if($_SESSION['auth']){
	$checkAuth = checkAuth("`id`, `nickname`, `level`");
	if($checkAuth != 'error'){
		$userrow = mysql_fetch_array($checkAuth);
		$__uid = $userrow['id'];
		$login = $userrow['nickname'];
		$u_level = $userrow['level'];
		
		echo 'Loqin: '.$login.'<br/>';
		
		if($u_level > 0){
			echo '<a href="cpanel/newblogs.php">Yeni bloqların yoxlanılması</a><br/><br/>';
		}
	}
}
else{
	//echo 'Yenilik! Chatdan bloqa birbaşa qeydiyyatlı kimi daxil ola bilersiniz!<br/><br/>';
}

echo '<a href="faq.php?mod=about">Bloq nedir?</a> | <a href="add.php">Bloq yaz</a><br/>';
echo '<br/>';

$query = mysql_query("SELECT `id`, `uid`, `name`, `body`, `image`, `date` FROM `blog_list` WHERE `status` = '1' AND `image` != '' AND `date` > '".(time()-3600*20*1)."' ORDER BY `read` DESC LIMIT 3;");
while($row = mysql_fetch_array($query)){
	$blogid = $row['id'];
	$uid = $row['uid'];
	$name = replaceLatin_E(stripslashes($row['name']));
	$body = $row['body'];
	$date = $row['date'];
	$image = $row['image'];
	
	$str_search  = array('big.az', 'wap.', 'b i g', 'b.i.g', 'b_i_g', 'b-i-g', 'b*i*g', 'b,i,g', 'bebek.az', 'wen.ru');
	$str_replace = array('.');
	$name = str_ireplace($str_search, $str_replace, $name);
	
	$count_comms = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_com` WHERE `bid` = '".$blogid."';"), 0);
	
	echo '<div class="content">';
	if(!empty($image)) echo '<p style="margin-top:0px;">';
	if(!empty($image)) echo '<img src="thumbs/small/'.$image.'" alt="." style="float:left; padding-right:5px" width="80" height="70" /> ';
	echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <a href="view_blog.php?id='.$blogid.'">'.$name.'</a><br/>';
	if(date('d-m-Y', $date) == date('d-m-Y')) $date_str = 'Bugün '.date('H:i', $date);
	else if(date('d-m-Y', $date) == date('d-m-Y', strtotime('-1 day'))) $date_str = 'Dünen '.date('H:i', $date);
	else $date_str = date('d-m-Y H:i', $date);
	echo 'Tarix: '.$date_str.' <img src="/img/icons/com.png" alt="Şerhler" style="vertical-align:middle;"/> '.$count_comms.'<br/>';
	$u_query = mysql_query("SELECT `nickname` FROM `chat_users` WHERE `id` = '".$uid."';");
	$u_login = mysql_result($u_query, 0);
	echo 'Müellif: '.$u_login.'<br/>';
	if(!empty($image)) echo '</p>';
	echo '</div>';
}

if(mysql_num_rows($query) > 0){
	echo '<br/>';
}

/*
$query = mysql_query("SELECT `id`, `name` FROM `blog_cat` ORDER BY `pos` ASC LIMIT 10;");
while($row = mysql_fetch_array($query)){
	$catid = $row['id'];
	$catname = $row['name'];
	$cnt_blog = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `catid` = '".$catid."'"), 0);
	echo '<a href="cat.php?id='.$catid.'">'.$catname.' ('.$cnt_blog.')</a><br/>';
	
	$lr_query = mysql_query("SELECT * FROM `blog_list` WHERE `catid` = '".$catid."' AND `status` = 1 ORDER BY `date` DESC LIMIT 1;");
	$lr_row = mysql_fetch_array($lr_query);
	$blogid = $lr_row['id'];
	$uid = $lr_row['uid'];
	$name = replaceLatin_E(stripslashes($lr_row['name']));
	$body = $lr_row['body'];
	$date = $lr_row['date'];
	
	echo '<img src="/img/blog_icon.gif" alt="." style="vertical-align:middle;"/> <a href="view_blog.php?id='.$blogid.'">'.$name.'</a><br/>';
	echo 'Tarix: '.date('d-m-Y H:i', $date).'<br/>';
	$u_query = mysql_query("SELECT `login` FROM `users` WHERE `id` = '".$uid."';");
	$u_login = mysql_result($u_query, 0);
	echo 'Muellif: '.$u_login.'<br/><br/>';

}
*/

echo 'Kanallar:<br/>';
$query = mysql_query("SELECT `id`, `name` FROM `blog_cat` ORDER BY `pos` DESC LIMIT 11;");
while($row = mysql_fetch_array($query)){
	$catid = $row['id'];
	$catname = $row['name'];
	$cnt_blog = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `status` = '1' AND `catid` = '".$catid."'"), 0);
	$cnt_blog_new = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `blog_list` WHERE `status` = '1' AND `catid` = '".$catid."' AND `date` > '".strtotime('today')."'"), 0);
	if($cnt_blog_new > 0) $show_cnt_new = ' <span style="color: green">+'.$cnt_blog_new.'</span>'; else $show_cnt_new = '';
	echo '+ <a href="cat.php?id='.$catid.'">'.$catname.' ('.$cnt_blog.')</a> '.$show_cnt_new.'<br/>';
}

//echo '<a href="/login.php?loc=blog">Daxil ol</a> | <a href="/registration.php?loc=blog">Qeydiyyat</a><br/>';

echo '<br/>';
echo '<a href="topusers.php?period=month">TOP bloqcular</a><br/>';
echo '<a href="topblogs.php?period=week">TOP bloq yazılar</a><br/>';

if($_SESSION['auth']){
	echo '<br/><a href="/main.php">Chat</a> ';
	echo ' | <a href="/logout.php?loc=blog">Çıxış</a><br/>';
	//ONLINE
	$online = time() + 600;
	$update = mysql_query("UPDATE `chat_users` SET `time` = '".$online."', `place` = 0, `ip` = '".getenv('REMOTE_ADDR')."', `ua` = '".htmlspecialchars(getenv('HTTP_USER_AGENT'))."', `provider` = '".get_operator(getenv('REMOTE_ADDR'))."' WHERE `id` = '".$__uid."' LIMIT 1;");
	//END ONLINE
}

echo '<br/><span style="font-size: 11px">Bloqda yazılan yazılara göre sayt cavabdeh deyildir. Yazılanlar tamamile müelliflerin öz fikirleridir</span>';
echo '</div>';

include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
