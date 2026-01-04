<?
error_reporting(0);
session_start();

$_uid = intval($_GET['uid']);

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/smiles_org.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['smayllar'].'</div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=smiles">'.$__lng['giris'].'</a> | <a href="reg.php?loc=smiles">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$mySmile = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`smiles` WHERE `user_id`='".$id."' ORDER BY `id` DESC LIMIT 1"));
$smilesArray['.my.'] = $mySmile["file"];
echo '<table style="width: 100%; max-width: 500px;" cellpadding="1">';

echo '<tr><td colspan="2" style="text-align: center; padding: 4px; background: #fdddc2"><a href="smiles-classic.php">Klassik '.$__lng['smayllar'].'</a></td><td colspan="1" style="text-align: center; padding: 4px; background: #fd9c4e">'.$__lng['smayllar'].'</td><td colspan="1" style="text-align: center; padding: 4px; background: #fdddc2"><a href="stickers.php">'.$__lng['stikerler'].'</a></td></tr>';

echo '<tr>';
$i= 0;

$all_rows = count($smilesArray); 
//$all_rows = round($countSmiles/$show_limit);

$show_limit = 32;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all_rows) $page = 1;
$start = ($page-1)*$show_limit;

$smilesArray = array_slice($smilesArray, $start, $show_limit);

foreach ($smilesArray as $smileKey => $smileImg ) {
	if(is_int($i/4) && $i > 0) echo '</tr><tr>';
  echo '<td width="10%" style="text-align:center"><img src="img/smiles/'.$smileImg.'" alt="'.$smileKey.'" /><br/>'.$smileKey.'</td>';
  $i++;
}
echo '</tr></table>';

echo '<br/><div class="pageNav" style="text-align:left">';

$interval = 5;
$max = ceil($all_rows/$show_limit);

if($page > 1) echo '<a href ="smiles.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a href ="smiles.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a href="smiles.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a href="smiles.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a href="smiles.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span id="pageButon_off">'.$max.'</span> ';
	}
}

if($page < $max) echo '<a href ="smiles.php?page='.($page+1).'">&gt;</a> ';

echo '</div><br/>';
echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
echo '</div>';
include 'inc/footer.php';
?>
