<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/smiles.php';
include 'inc/lang/pack.php';


$title = 'AloChat';
include 'inc/header.php';
foreach ($smilesArray as $key => $value) {
    $smilesArray[$key] = '<img src="/img/smiles/'.$value.'.png" alt="'.$key.'" />';
}
echo '<div class="mnav"><a href="main.php">'.$title.'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`, `unseen`, `nickname`,`user_status`, `coins`');
if($checkAuth == 'error'){
    displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
        '<a href="index.php?loc=profile">'.$__lng['giris'].'</a> | <a href="reg.php?loc=profile">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$profile_unseen = $userrow['unseen'];
$user_status = $userrow['user_status'];
$coins = $userrow['coins'];


if($_GET["mod"]!="game"){
	if($_GET["mod"]=='end'){
		$game_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`brain_game` WHERE `user_id`='".$id."' and `status`=0 ORDER BY `id` DESC"));
		mysql_query("UPDATE `aloaz_db`.`user` SET `question_rating`=`question_rating`+'".$game_row["point"]."' WHERE `id`='".$id."' LIMIT 1");
		echo "Nik: ".$login."<br />"."Merhele: ".$game_row["step"]."<br /> Xal:".$game_row["point"]." <br />";
	}else{
		echo "Nik: ".$login."<br />"."Merhele: 0<br /> Xal:0 <br />";
	}
	echo "<a href='sual-cavab.php?mod=game&s=0'>Oyuna başla</a>";
}else{
	$game_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`brain_game` WHERE `user_id`='".$id."' and `status`=0 ORDER BY `id` DESC"));
	if(!$game_row and intval($_GET["s"]==0)){
		mysql_query("INSERT INTO `aloaz_db`.`brain_game` SET `user_id`='".$id."',`status`=0,`step`=0,`point`=0,`begin_time`='".time()."'");
		$game_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`brain_game` WHERE `user_id`='".$id."' and `status`=0 ORDER BY `id` DESC"));
	}elseif($game_row["step"]!=intval($_GET["step"]) and intval($_GET["step"])!=0){
		mysql_query("UPDATE `aloaz_db`.`brain_game` SET status=1,`end_time`='".time()."' WHERE `user_id`='".$id."' and `status`=0");
		echo 'Oyundan yarımcıq çıxdığınız üçün Səhvlik baş verdi. Yenidən oyuna başlayın!<br />'; 
		echo "<a href='sual-cavab.php?mod=game&s=0'>Oyuna başla</a>";
		exit;
	}
	
	$step = intval($_GET["s"]); 
	$answer_array=array('a','b','c','d');
	if(isset($_GET["q_id"]) and intval($_GET["q_id"])>0 and isset($_GET["a"]) and in_array(trim($_GET["a"]),$answer_array)){
		$q_id = intval($_GET["q_id"]);
		$answer = htmlspecialchars(trim($_GET["a"]));
		$question = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`questions` WHERE id='".$q_id."'"));
		if($question["correct"]==$answer){
			echo "Tebrikler. Dogru cavab verdiniz ve 1 xal qazandiniz. Novbeti merheleye kecende eger dogru cavab vermesez oyundan qazandiginiz xal 0 olacaq. Eger oyunu terk etsez oyundan qazandiginiz xal balansiniza yuklenecek";
			mysql_query("UPDATE `aloaz_db`.`brain_game` SET `step`=`step`+1,`point`=`point`+1 WHERE `id`='".$game_row["id"]."' LIMIT 1");
			echo '<a href="sual-cavab.php?mod=game&s='.($step+1).'">Yeni Merhele: '.($step+1).'</a>';
			if($step>0){
				echo '<a href="sual-cavab.php?mod=imtina">Imtina et</a>';

			}
			exit;
		}else{
			echo "Yanlis cavab verdiniz";
			mysql_query("UPDATE `aloaz_db`.`brain_game` SET `status`=1,`end_time`='".time()."' WHERE `id`='".$game_row["id"]."' LIMIT 1");
			exit;

		}
	} 
	echo 'Mərhələ: Hazırlıq Mərhələsi';
	echo '<p style="line-height:1.8">';
	$questions_query = mysql_query("SELECT * FROM questions WHERE step='".$step."' ORDER BY RAND() LIMIT 1"); 
 	$row = mysql_fetch_assoc($questions_query);
	echo "<b>".$row["question"]."</b><br />";
	echo '<a href="sual-cavab.php?mod=game&q_id='.$row["id"].'&a=a&s='.$step.'">a)</a>'.$row["a"]."<br />";
	echo '<a href="sual-cavab.php?mod=game&q_id='.$row["id"].'&a=b&s='.$step.'">b)</a>'.$row["b"]."<br />";
	echo '<a href="sual-cavab.php?mod=game&q_id='.$row["id"].'&a=c&s='.$step.'">c)</a>'.$row["c"]."<br />";
	echo '<a href="sual-cavab.php?mod=game&q_id='.$row["id"].'&a=d&s='.$step.'">d)</a>'.$row["d"]."<br />";
	echo "</p>";
	
	
}
 
echo '</div>';
include 'inc/footer.php';
?>
