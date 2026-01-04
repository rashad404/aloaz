<?php
/*****************************\
# API: Sim.az				  |
# Author: Yusubov Pərviz	  |
******************************/

session_start();
include '../inc/config.php';
$simaz->connect();

$ref=rand(10000,1000000);
$divide = '----<br/>';
$title = 'AloChat - Reklam Reytinq';
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj';
$meta_description = 'Sayt ve ya Android, Iphone mobil tetbiqlerimiz vasitesi ile daxil olaraq yeni dostlar qazan, pulsuz mesajlaş, paylaş ve tanış ol!';


include '../inc/header.php';
echo '<div class="mnav">Reklam Reytinq</div>';

echo '<div class="layer">';
	echo '<div align="center">';
		echo "<b>Reklam və Qeydiyyat alanlar</b><br/>";
		echo $divide;
		echo $simaz->params['client_bonus_text'].'<br/>';
		echo $divide;


		if( $error ) {
			echo '<div style="background: #f9e2bf;padding: 10px;">';
			echo '<b>Error:</b> '.$error.'.<br/>';
			echo '</div>';
			echo $divide;
		}

		if( $simaz->params['client_bonus_timer'] == 'true' ) {
			echo 'Yarışın Bitmesine '.$simaz->srTime( strtotime($simaz->params['client_bonus_stop_time'].' 00:00:00') ).' qalıb.<br/>';
		}
	echo '</div>';
	echo $divide;

	$i = 0;
	$onu = mysql_num_rows(mysql_query("select `id` from `advertiser_rating`"));
	$next_id = $simaz->next_id($onu,500);

	$a = mysql_query("select * from `advertiser_rating` order by `cost` DESC LIMIT $next_id[start],$next_id[max_page];");
	if (mysql_affected_rows() == 0) {
		echo 'Hələ ki, Reklam və qeydiyyat alan olmayıb...<br/>';
	}
	else {
		
		while($b = mysql_fetch_object($a)){
			$url = $b->site;
			$i++;

			echo $i.") <a href=\"http://".$b->site."\">".$url."</a> - ".$b->cost." Azn.";
			if($i <= 3) {
				echo '<img style="vertical-align: sub" src="img/'.$i.'.gif" alt="'.$i.'"/>';
			}
			echo '<br/>';
			
		}

		if($next_id['a'] > $next_id['max_page'])
		{
			echo $divide;
			echo $simaz->page_next("reytinq.php?ref=$ref", $next_id['a'], $next_id['max_page'], $next_id['page']);
		}
	}


	echo $divide;
	echo "<a href=\"{$simaz->home}\">Ana sehife</a><br/>\n";
	echo $divide;
	echo 'Sim.az - Service<br/>';
echo '</div>';
include 'inc/footer.php';
?>