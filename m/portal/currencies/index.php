<?
$title = 'Valyuta mezenneleri';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';
include 'currencies.inc.php';

echo '<div class="mnav"><a href="?">'.$title.'</a></div>';
echo '<div class="layer">';

echo getCurrencies();

echo '<br/><a href="javascript:history.back(1)">« Geri</a>';

echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>