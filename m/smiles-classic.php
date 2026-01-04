<?
error_reporting(0);
session_start();

$_uid = intval($_GET['uid']);

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/smiles_classic_name.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Klassik '.$__lng['smayllar'].'</div>';
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
$smileCatArray = [1=>"Ümumi",2=>"Döyüş",3=>"İdman",4=>"Sevgi, ürekler",5=>"Hirsli, esebi",6=>"Öpüş, dodaqlar",7=>"Gülmek",8=>"Musiqi,reqs",9=>"Novruz Bayramı",10=>"Ağlamaq",11=>"Krutoylar",12=>"Avto",13=>"Yuxu",14=>"Etiraz",15=>"Personaj",16=>"Qorxu",17=>"Utanmaq",18=>"Emosiyalar"];
$smileCatName = [1=>"umumi_smiles",2=>"doyus_smiles",3=>"idman_smiles",4=>"servgi_smiles",5=>"hirsli_smiles",6=>"opush_smiles",7=>"gulmek_smiles",8=>"musiqi_smiles",9=>"novruz_smiles",10=>"aglamaq_smiles",11=>"krutoylar_smiles",12=>"avto_smiles",13=>"yuxu_smiles",14=>"etiraz_smiles",15=>"personaj_smiles",16=>"qorxu_smiles",17=>"utanmaq_smiles",18=>"emosiyalar_smiles"];;
if(!isset($_GET["cat"]) or intval($_GET["cat"])==0 or !isset($smileCatArray[intval($_GET["cat"])])){
	
	foreach($smileCatArray as $k=>$v){
		echo "<a href='smiles-classic.php?cat=".$k."'>".$v."</a><br />";
	} 
 
}else{
		$cat = intval($_GET["cat"]);
		$arrayName = $smileCatName[$cat];
		echo " <a href='smiles-classic.php'>&laquo; Smayl kateqoriyaları</a><br /><br />";
		echo '<table style="width: 100%; max-width: 500px;" cellpadding="1">';

		echo '<tr><td colspan="2" style="text-align: center; padding: 4px; background: #fd9c4e"> '.$smileCatArray[$cat].'</td><td colspan="1" style="text-align: center; padding: 4px; background: #fdddc2"><a href="smiles.php">'.$__lng['smayllar'].'</a></td><td colspan="1" style="text-align: center; padding: 4px; background: #fdddc2"><a href="stickers.php">'.$__lng['stikerler'].'</a></td></tr>';

		echo '<tr>';
		$i= 0;

		$array = $$arrayName;

		$all_rows = count($array);
		//$all_rows = round($countSmiles/$show_limit);

		$show_limit = 24;
		if(isset($_GET['page'])) $page = $_GET['page'];
		else $page = 1;
		if($page < 1) $page = 1;
		if($page > $all_rows) $page = 1;
		$start = ($page-1)*$show_limit;

		$array = array_slice($array, $start, $show_limit);

		foreach ($array as $smileKey) {
			if(is_int($i/4) && $i > 0) echo '</tr><tr>';
		  echo '<td width="10%" style="text-align:center"><img src="img/smiles/'.$smilesArray[$smileKey].'" alt="'.$smileKey.'" /><br/>'.$smileKey.'</td>';
		  $i++;
		}
		echo '</tr></table>';

		echo '<br/><div class="pageNav" style="text-align:left">';

		$interval = 5;
		$max = ceil($all_rows/$show_limit);

		if($page > 1) echo '<a href ="smiles-classic.php?cat='.$cat.'&amp;page='.($page-1).'">&lt;</a> ';

		if($page > $interval) echo ' <a href ="smiles-classic.php?cat='.$cat.'&amp;page=1">1</a> ... ';

		for($i=1; $i<=$max; $i++){
			if($page <= $interval && $i <=$interval){
				if($i != $page){
					echo ' <a href="smiles-classic.php?cat='.$cat.'&amp;page='.$i.'">'.$i.'</a> ';
				}
				else{
					echo ' <span id="pageButon_off">'.$i.'</span> ';
				}
			}
			else{
				if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
					if($i != $page){
						echo ' <a href="smiles-classic.php?cat='.$cat.'&amp;page='.$i.'">'.$i.'</a> ';
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
				echo ' <a href="smiles-classic.php?cat='.$cat.'&amp;page='.$max.'">'.$max.'</a> ';
			}
			else{
				echo ' <span id="pageButon_off">'.$max.'</span> ';
			}
		}

		if($page < $max) echo '<a href ="smiles-classic.php?cat='.$cat.'&amp;page='.($page+1).'">&gt;</a> ';

		echo '</div><br/>';
}


echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a><br/>';
echo '</div>';
include 'inc/footer.php';
?>
