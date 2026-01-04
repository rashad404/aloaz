<?
exit;
$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include '/home/admin/domains/server-saytim.net/public_html/chat/config.php';

//mysql_query("INSERT INTO `blog_cat` SET `name` = 'Qadın dünyası'");
mysql_query("INSERT INTO `blog_cat` SET `name` = 'Mobil texnologiyalar'");
echo mysql_error();

?>