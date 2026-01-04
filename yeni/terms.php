<?
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['istifade sertleri'];
include 'inc/header.php';

echo '<div class="mnav">AloChat » '.$title.'</div>';
echo '<div class="layer">';

echo $__lng['istifade sertleri yazisi'];

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include 'inc/footer.php';
?>
