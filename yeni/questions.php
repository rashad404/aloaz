<?
session_start();

include 'inc/func.php';
include 'inc/functions.php';
include 'inc/config.php';

$title = 'Tez-Tez verilen suallar';
include 'inc/header.php';

echo '<div class="mnav">AloChat » '.$title.'</div>';
echo '<div class="layer">';

echo '<b>Daxil olanda loqin parol istemir</b><br/>';
echo 'Saytdan çıxandan sonra kimse sizin telefonla alo.az hesabınıza daxil ola bilmemesi üçün Baş sehifede en aşağıda olan Çıxış düymesi vasitesi ile çıxmaq lazımdır.<br/><br/>';

echo '<b>Postlar niye hesablanmır?</b><br/>';
echo 'Mesajlaşarken postların hesablanması ve Top listlerde loqinin görünmesi üçün Baş sehifeden Aletler bölmesine daxil olun ve "Postlar aktiv"-i nişanlayın.<br/><br/>';

echo '<b>Ballar niye artmır?</b><br/>';
echo 'Balı yalnız pulla almaq mümkündür. Bunun üçün Baş sehifeden Bal xidmetleri bölmesine daxil olun. Ordan ala bilersiniz.<br/><br/>';

echo '<b>Loqini nece silim?</b><br/>';
echo 'Loqini balla silmek mümkündür. Bunun üçün Baş sehifeden Bal xidmetleri bölmesine daxil olun. Ordan sile bilersiniz.<br/>';


echo '<br/><a href="javascript:history.back(1)">« Geri</a>';
echo '</div>';
include 'inc/footer.php';
?>
