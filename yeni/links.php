<?
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'Faydalı linkler';
include 'inc/header.php';

echo '<div class="mnav">AloChat » '.$title.'</div>';
echo '<div class="layer">';

echo '<a href="/namaz">Namaz vaxtları (Teqvim)</a><br/>';
echo '<i>Şeherlere göre Namaz vaxtları</i><br/><br/>';

echo '<a href="http://metbuat.az/?ref=aloaz">Ölke ve Dünyadan En Son Xeberler</a><br/>';
echo '<i>Saatın esas xeberleri metbuat.az saytında</i><br/><br/>';

echo '<a href="http://mp3tap.com">Musiqi (mp3) Axtar/Yükle</a><br/>';
echo '<i>İstenilen mp3-ü süretle tap ve yükle</i><br/><br/>';

echo '<a href="portal/currencies/">Valyuta mezenneleri</a><br/>';
echo '<i>Her gün yenilenen 50 valyutanın mezennesi</i><br/><br/>';

echo '<a href="portal/translate/">Tercüme</a><br/>';
echo '<i>İngilis, Rus, Alman, Fransız ve s. dillerden tercüme</i><br/><br/>';

echo '<br/><a href="javascript:history.back(1)">« Geri</a>';
echo '</div>';
include 'inc/footer.php';
?>
