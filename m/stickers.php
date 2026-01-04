<?
error_reporting(0);
session_start();

$_uid = intval($_GET['uid']);

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/stickers.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['stikerler'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=stickers">'.$__lng['giris'].'</a> | <a href="reg.php?loc=stickers">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

echo '<table style="width: 100%; max-width: 500px;" border="0px"><tr>';

echo '<td colspan="4" style="text-align: center;">';

echo '<table style="width: 100%;">';
echo '<tr><td style="text-align: center; padding: 4px; background: #fdddc2"><a href="smiles.php">Klassik '.$__lng['smayllar'].'</a><td style="text-align: center; padding: 4px; background: #fdddc2"><a href="smiles.php">'.$__lng['smayllar'].'</a></td><td style="text-align: center; padding: 4px; background: #fd9c4e">'.$__lng['stikerler'].'</td></tr>';
echo '</table>';

echo '</td></tr>';

//echo '<div style="float: left; width: 50%; background: #fdddc2; padding-top: 4px; padding-bottom: 4px;"><a href="smiles.php?mod=stickers">Smayllar</a></div>
//<div style="float: right; width: 50%; background: #fd9c4e; padding-top: 4px; padding-bottom: 4px;">Stikerler</div>';



echo '<tr>';
$i= 0;

$all_rows = count($stickersArray);
//$all_rows = round($countSmiles/$show_limit);

$show_limit = 9;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$stickersArray = array_slice($stickersArray, $start, $show_limit);

foreach ($stickersArray as $stickerKey => $stickerImg ) {
	if(is_int($i/3) && $i > 0) echo '</tr><tr>';
  echo '<td width="10%" style="text-align: center;"><a href="img/stickers/'.$stickerImg.'.gif"><img src="img/stickers/thumb/'.$stickerImg.'.png" alt="'.$stickerKey.'" width="80" height="74"/><br/>'.$stickerKey.'</a></td>';
  $i++;
}
echo '</tr></table>';

echo '<br/><b>'.$__lng['qeyd'].':</b> '.$__lng['stiker gonderende balans cixilir'].'<br/>';
echo $__lng['stiker gond ucun kod yaz'].'<br/>';

echo '<br/><div class="pageNav" style="text-align:left">';

$interval = 5;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="stickers.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="stickers.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="stickers.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="stickers.php?page='.$i.'">'.$i.'</a> ';
			}
			else{
				echo ' <span id="pageButon_off">'.$i.'</span> ';
			}
		}
		
	}
}
if($page <= $max - $interval) echo '... ';

if($max > $interval){
	if($max != $page){
		echo ' <a href="stickers.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span id="pageButon_off">'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href="stickers.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';
echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
echo '</div>';
include 'inc/footer.php';
?>
