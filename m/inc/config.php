<?php

// Suppress PHP 8 deprecation warnings for legacy code
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING & ~E_NOTICE);
ini_set('display_errors', 0);

// Load environment variables
require_once(__DIR__ . '/env.php');

// MySQL compatibility layer for PHP 7+
require_once(__DIR__ . '/mysql_compat.php');

// Database credentials from .env
$DB_HOST = env('DB_HOST', 'localhost');
$DB_USER = env('DB_USER', 'root');
$DB_PASS = env('DB_PASS', '');
$DB_NAME = env('DB_NAME', 'aloaz_db');

date_default_timezone_set('Asia/Baku');

$mysql_connect = @mysql_connect($DB_HOST,$DB_USER,$DB_PASS);

if(!$mysql_connect) exit('DB Error (101)');
mysql_set_charset('utf8', $mysql_connect);

$selectdb = mysql_select_db($DB_NAME, $mysql_connect);

if(!$selectdb) exit('DB Error (102)' . mysql_error());

if (!defined('DOCUMENT_ROOT')) {
    define('DOCUMENT_ROOT', env('DOCUMENT_ROOT', __DIR__ . '/../'));
}
include_once(DOCUMENT_ROOT .'simaz/config.php');
?>
