<?
error_reporting(0);
session_start();

include 'inc/lang/pack.php';

$_confirm = intval($_GET['confirm']);

if($_confirm == 1){
	session_destroy();
	header('location: index.php?ref=logout');
	exit;
}

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';

$title = 'Alochat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Çıxış</div>';
echo '<div class="layer">';

echo $__lng['cixis etmeye eminsen'].'<br/><br/>';
echo '<a href="logout.php?confirm=1">'.$__lng['beli'].'</a> / <a href="main.php">'.$__lng['xeyr'].'</a><br/>';
echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

echo '</div>';
include 'inc/footer.php';
?>
