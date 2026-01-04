<?php
$title = 'Bloq';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="index.php">Bloq</a></div>';
echo '<div class="layer">';

switch($_GET['mod']){

case 'about':
echo '<b>Bloq nedir?</b><br/>';

echo 'Bloq - fərdi jurnal olub, informasiyanı, fikirləri, şərhləri və müxtəlif tipli hiper əlaqələri özündə toplayan bir saytdır.';
echo 'Internet inkişafından sonra ferdi saytların yaratmaq isteyini meydana çıxardı.<br/>';
echo 'Amma bele bir problem var idi ki, hamı sayt hazırlamaq üçün lazımı qeder proqramlama ve dizayn biliyine malik deyildi.<br/>';
echo 'Bunun üçün müxtelif saytlar insanların öz ferdi saytlarını başqa bir deyişle bloglarının açmasına imkan yaratdı.<br/>';
echo 'Bunun üçün siz sayta üzv olursunuz ve öz blogunuzu açırsınız.<br/><br/>';

echo '<b>Niye bloq açım?</b><br/>';
echo 'Blogunuzu özünüzü ifade etmek ve özünüz haqqında informasiya vermek üçün aça bilersiniz.<br/>';
echo 'Her hansı bir mehsul veya mal haqqında reklam meqsedli blog qura bilersiniz<br/>';
echo 'Başqa insanlara kömek etmek üçün<br/>';
echo 'Herhansı bir sahede expert olduğunuzu göstermek üçün.<br/>';
echo 'Sizinle eyni fikirde olan insanlarla elaqe qurmaq üçün<br/>';
echo 'Expert olduğunuz sahe haqqında özünüzü inkişaf etdirmek üçün, çünki siz blogunuzu açdıqdan sonra bu sahe haqqında en son xeberleri ve inkişafları blogunuza yazırsınız ve bu yolla siz hem özünüzü hem de blogunuzu oxuyanları inkişaf etdirirsiniz.<br/><br/>';

echo '<b>Bloq açmaq isteyenler üçün tövsiyeler</b><br/>';
echo 'Dostlarınızı bloga baxmağa devet edin, bu yolla siz blogunuza giren istirfadeçilerin sayısını artırmış olarsınız.<br/>';

echo 'Blogunuz meşhur firmalar kimi size aid olsun.Dizaynı, elave edilmiş informasiyalar, logolar ve s.<br/>';
echo 'Blogda experti olduğunuz sahe haqqında informasiyalar elave edin.<br/>';
echo 'Lazım geldikde kömek isteyin ve başqa dostlarınız ve tanışlarınızın da yazıları bloga elave edin<br/>';

break;


}
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>
