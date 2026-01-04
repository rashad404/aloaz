<?
session_start();
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/config.php';
session_destroy();

$title = 'Çıxış';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav">'.$title.'</div>';
echo '<div class="layer">';

echo 'Çıxış emeliyyatı müveffeqiyyetle icra olundu. Teşekkür edirik!<br/>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';

?>
