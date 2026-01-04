<?
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = $__lng['elaqe'];
include 'inc/header.php';

echo '<div class="mnav">AloChat » '.$title.'</div>';
echo '<div class="layer">';

echo $__lng['email vasitesile elaqe'].':<br/><br/><img src="img/email.gif" alt="." /><br/>';

/* ?><script type="text/javascript">document.write('<scr'+'ipt type="text/javascript" src="//mobilink.az/pub/15949?t='+new Date().getTime()+'" charset="utf-8" ></scr'+'ipt>');</script><?
 */

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
echo '</div>';
include 'inc/footer.php';
?>

