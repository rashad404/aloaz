<?
//error_reporting(0);

include '../inc/func.php';
include '../inc/functions.php';

mysqlConnect();

$title = 'AloChat';
include '../inc/header.php';

$city = intval($_GET["city"]);

$city_name = array(
1 => 'Bakı', 
2 => 'Ağdaş', 
3 => 'Ağsu', 
4 => 'Ağcabedi', 
5 => 'Ağdam', 
6 => 'Akstafa', 
7 => 'Astara', 
8 => 'Babek', 
9 => 'Balaken', 
10 => 'Beyleqan', 
11 => 'Bilasuvar', 
12 => 'Berde', 
13 => 'Celilabad', 
14 => 'Cebrayıl', 
15 => 'Culfa', 
16 => 'Daşkesen', 
17 => 'Deveçi', 
18 => 'Füzuli', 
19 => 'Gence', 
20 => 'Goranboy', 
21 => 'Göyçay', 
22 => 'Gedebey', 
23 => 'Horadiz', 
24 => 'İmişli', 
25 => 'İsmayıllı', 
26 => 'Kelbecer', 
27 => 'Kürdemir', 
28 => 'Laçın', 
29 => 'Lenkeran', 
30 => 'Lerik', 
31 => 'Masallı', 
32 => 'Mingeçevir', 
33 => 'Mereze', 
34 => 'Neftçala', 
35 => 'Naxçıvan', 
36 => 'Oğuz', 
37 => 'Ordubad', 
38 => 'Qazax', 
39 => 'Qazımemmed', 
40 => 'Qax', 
41 => 'Qebele', 
42 => 'Quba', 
43 => 'Qusar', 
44 => 'Qubadlı', 
45 => 'Saatlı', 
46 => 'Sabirabad', 
47 => 'Sederek', 
48 => 'Salyan', 
49 => 'Siyezen', 
50 => 'Terter', 
51 => 'Tovuz', 
52 => 'Elibayramlı', 
53 => 'Ucar', 
54 => 'Xaçmaz', 
55 => 'Xanlar', 
56 => 'Yardımlı', 
57 => 'Yevlax', 
58 => 'Zaqatala', 
59 => 'Zengilan', 
60 => 'Zerdab', 
61 => 'Şahbuz', 
62 => 'Şamaxı', 
63 => 'Şerur', 
64 => 'Şeki', 
65 => 'Şemkir', 
66 => 'Şuşa');

$day = intval($_GET["day"]);
if($day==1) $search_day=date("d.m.Y",time()+86400); else $search_day=date("d.m.Y");

$mod = $_GET['mod'];
switch($mod) {

case 'view':
echo '<div class="mnav"><a href="?">Namaz vaxtı</a> - '.$city_name[$city].'</div>';

echo '<div class="layer">';
if($day==1) echo " <a href=\"?mod=view&amp;city=$city\">Bugün</a> | Sabah";
else echo "Bugün | <a style=\"font-weight: bold\" href=\"?mod=view&amp;city=$city&amp;day=1\">Sabah</a>";
echo '<br/>';

$q = mysql_query("SELECT * FROM `namaz` WHERE `city` = '".$city."' AND `date` = '".$search_day."';");

if(mysql_affected_rows() == 0){
	echo 'Melumat yoxdur';
	break;
}
$user = mysql_fetch_array($q);

$date = $user['date'];
$subh = $user['subh'];
$gun = $user['gun'];
$zohr = $user['zohr'];
$esr = $user['esr'];
$axsham = $user['axsham'];
$isha = $user['isha'];

echo "$date<br/><br/>";
echo "Sübh: $subh<br/>
Gün çıxma vaxtı: $gun<br/>
Zöhr: $zohr<br/>
Esr: $esr<br/>
Axşam: $axsham<br/>
İşa: $isha<br/><br/>";


echo '<a href="javascript:history.back(1)">« Geri</a>';
echo '</div>';
break;


default:
echo '<div class="mnav"><a href="?">Namaz vaxtı</a></div>';
echo '<div class="layer">';

$page=intval($_GET['page']);
if($page < 1) $page=1;
if($page > 3) $page=3;
if($page == 1){
echo "<a href=\"?mod=view&amp;city=1\">Bakı</a><br/>
<a href=\"?mod=view&amp;city=2\">Ağdaş</a><br/>
<a href=\"?mod=view&amp;city=3\">Ağsu</a><br/>
<a href=\"?mod=view&amp;city=4\">Ağcabedi</a><br/>
<a href=\"?mod=view&amp;city=5\">Ağdam</a><br/>
<a href=\"?mod=view&amp;city=6\">Akstafa</a><br/>
<a href=\"?mod=view&amp;city=7\">Astara</a><br/>
<a href=\"?mod=view&amp;city=8\">Babek</a><br/>
<a href=\"?mod=view&amp;city=9\">Balaken</a><br/>
<a href=\"?mod=view&amp;city=10\">Beyleqan</a><br/>
<a href=\"?mod=view&amp;city=11\">Bilasuvar</a><br/>
<a href=\"?mod=view&amp;city=12\">Berde</a><br/>
<a href=\"?mod=view&amp;city=13\">Celilabad</a><br/>
<a href=\"?mod=view&amp;city=14\">Cebrayıl</a><br/>
<a href=\"?mod=view&amp;city=15\">Culfa</a><br/>
<a href=\"?mod=view&amp;city=16\">Daşkesen</a><br/>
<a href=\"?mod=view&amp;city=17\">Deveçi</a><br/>
<a href=\"?mod=view&amp;city=18\">Füzuli</a><br/>
<a href=\"?mod=view&amp;city=19\">Gence</a><br/>
<a href=\"?mod=view&amp;city=20\">Goranboy</a><br/>
<a href=\"?mod=view&amp;city=21\">Göyçay</a><br/>
<a href=\"?mod=view&amp;city=22\">Gedebey</a><br/>
<a href=\"?mod=view&amp;city=23\">Horadiz</a><br/>
<a href=\"?mod=view&amp;city=24\">İmişli</a><br/>
<a href=\"?mod=view&amp;city=25\">İsmayıllı</a><br/>
<a href=\"?mod=view&amp;city=26\">Kelbecer</a><br/>
<a href=\"?mod=view&amp;city=27\">Kürdemir</a><br/>
<a href=\"?mod=view&amp;city=28\">Laçın</a><br/>
<a href=\"?mod=view&amp;city=29\">Lenkeran</a><br/>
<a href=\"?mod=view&amp;city=30\">Lerik</a><br/><br/>
<a href=\"?page=2\">Növbeti »</a><br/>
";
}
if($page==2){
echo"<a href=\"?mod=view&amp;city=31\">Masallı</a><br/>
<a href=\"?mod=view&amp;city=32\">Mingeçevir</a><br/>
<a href=\"?mod=view&amp;city=33\">Mereze</a><br/>
<a href=\"?mod=view&amp;city=34\">Neftçala</a><br/>
<a href=\"?mod=view&amp;city=35\">Naxçıvan</a><br/>
<a href=\"?mod=view&amp;city=36\">Oğuz</a><br/>
<a href=\"?mod=view&amp;city=37\">Ordubad</a><br/>
<a href=\"?mod=view&amp;city=38\">Qazax</a><br/>
<a href=\"?mod=view&amp;city=39\">Qazımemmed</a><br/>
<a href=\"?mod=view&amp;city=40\">Qax</a><br/>
<a href=\"?mod=view&amp;city=41\">Qebele</a><br/>
<a href=\"?mod=view&amp;city=42\">Quba</a><br/>
<a href=\"?mod=view&amp;city=43\">Qusar</a><br/>
<a href=\"?mod=view&amp;city=44\">Qubadlı</a><br/>
<a href=\"?mod=view&amp;city=45\">Saatlı</a><br/>
<a href=\"?mod=view&amp;city=46\">Sabirabad</a><br/>
<a href=\"?mod=view&amp;city=47\">Sederek</a><br/>
<a href=\"?mod=view&amp;city=48\">Selyan</a><br/>
<a href=\"?mod=view&amp;city=49\">Siyezen</a><br/>
<a href=\"?mod=view&amp;city=50\">Terter</a><br/>
<a href=\"?mod=view&amp;city=51\">Tovuz</a><br/>
<a href=\"?mod=view&amp;city=52\">Elibayramlı</a><br/>
<a href=\"?mod=view&amp;city=53\">Ucar</a><br/>
<a href=\"?mod=view&amp;city=54\">Xaçmaz</a><br/>
<a href=\"?mod=view&amp;city=55\">Xanlar</a><br/>
<a href=\"?mod=view&amp;city=56\">Yardımlı</a><br/>
<a href=\"?mod=view&amp;city=57\">Yevlax</a><br/>
<a href=\"?mod=view&amp;city=58\">Zaqatala</a><br/>
<a href=\"?mod=view&amp;city=59\">Zengilan</a><br/>
<a href=\"?mod=view&amp;city=60\">Zerdab</a><br/><br/>
<a href=\"?page=1\"> « Evvelki</a> | <a href=\"?page=3\">Növbeti »</a><br/>";
}
if($page==3){
echo"<a href=\"?mod=view&amp;city=61\">Şahbuz</a><br/>
<a href=\"?mod=view&amp;city=62\">Şamaxı</a><br/>
<a href=\"?mod=view&amp;city=63\">Şerur</a><br/>
<a href=\"?mod=view&amp;city=64\">Şeki</a><br/>
<a href=\"?mod=view&amp;city=65\">Şemkir</a><br/>
<a href=\"?mod=view&amp;city=66\">Şuşa</a><br/><br/>
<a href=\"?page=2\"> « Evvelki</a><br/>
";
}
echo '</div>';
	break;
}
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';


?>
