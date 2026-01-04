<?php
/*****************************\
# API: Sim.az				  |
# Author: Yusubov Pərviz	  |
******************************/

include_once('class.php');

# Site URL from environment
$simaz->home = env('APP_URL', 'http://m.alo.az');

if( !defined('DOCUMENT_ROOT') ) {
	define('DOCUMENT_ROOT', env('DOCUMENT_ROOT', '/home/admin/domains/alo.az/public_html/m/'));
}

// Database credentials from environment
$simaz->mysql_db(array(
	'localhost' => env('DB_HOST', 'localhost'),
	'db_user'	=> env('DB_USER', 'root'),
	'db_pass'	=> env('DB_PASS', ''),
	'db_name'   => env('DB_NAME', 'aloaz_db'),
));

$simaz->replace = array(
	'site'  => array(
		'key' => 'site_index_reklam',
		'value' => 'Sayt Index',
	),
	'chat'  => array(
		'key' => 'chat_index_reklam',
		'value' => 'Chat Index',
	),
	'enter'  => array(
		'key' => 'enter_reklam',
		'value' => 'Chat Dehliz',
	),
	'on'  => array(
		'key' => 'on_reklam',
		'value' => 'Chat Online',
	)
);

if($result = $simaz->result_body()) {
	die(urlencode(http_build_query( $result, '', '&' )));
}



function reklam ( $place ) {
	global $simaz;
	$allow = null;
	$sql = mysql_query("SELECT `id`,`title` FROM `advertisers` WHERE `index` = '{$place}' AND `time` > '".time()."' order by rand() DESC;");
	while( $object = mysql_fetch_object($sql) ) {
		echo "<b>Reklam:</b> <a href=\"{$simaz->home}/simaz/go.php?id={$object->id}\">{$object->title}</a><br/>";
		$allow = true;
	}
	return $allow;
}



function buy_reg ( $location = false ) {

	$sql = mysql_query("SELECT `id`,`site_url` FROM `buy_reg` WHERE `pack_id` = '".(date("H")+1)."' AND `date` = '".date("Y-m-d")."' AND `active` = '1';");
	if( $object = mysql_fetch_object($sql) ) {
		if( $location ) {
			ob_clean();
			header("Location: {$object->site_url}"); die;
		}
		return $object->site_url;
	}
}
