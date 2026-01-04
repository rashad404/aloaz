<?php
$imagePath = $_GET['id'];

$pathRoot = '/home/aloaz/public_html/alochat.com/public_html';

echo file_get_contents($pathRoot.$imagePath);
?>