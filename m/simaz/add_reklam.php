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
$title = 'AloChat - Reklam Yerləşdir';
$meta_keywords = 'AloChat, Mobil, Chat, Tanışlıq, Mesaj, Eylen, Android, Iphone, Application, Dost tap, Pulsuz mesaj';
$meta_description = 'Sayt ve ya Android, Iphone mobil tetbiqlerimiz vasitesi ile daxil olaraq yeni dostlar qazan, pulsuz mesajlaş, paylaş ve tanış ol!';


$placeList = array();
if( $simaz->params['site_index_reklam'] == 'true' ) {
	$placeList[] = 'site';
}
if( $simaz->params['chat_index_reklam'] == 'true' ) {
	$placeList[] = 'chat';
}
if( $simaz->params['enter_reklam'] == 'true' ) {
	$placeList[] = 'enter';
}
if( $simaz->params['on_reklam'] == 'true' ) {
	$placeList[] = 'on';
}

$request = $simaz->request();
$error = null;
if($request) {
	$url = htmlspecialchars($request['url']);
	if (!preg_match("/^(http|https):/", $url)) {
		$url = 'http://'.$url;
	}
	if($simaz->strlen($request['title']) < 3) {
		$error = 'Reklam mətni 3 simvoldan çox olmalıdır.';
	}
	else if(!$simaz->isurl($url)) {
		$error = 'URL ünvan düz deyil.';
	}
	else if($simaz->strlen($request['title']) > 40) {
		$error = 'Reklam mətni 40 simvoldan çox olmalıdır.';
	}
	else if( !$simaz->replace[$request['place']] ) {
		$error = 'Reklam yeri düzgün seçilməyib.';
	}
	else if(!is_numeric($request['amount']) || round($request['amount']) < 1) {
		$error = 'Məbləğ düzgün qeyd olunmayıb.';
	}
	else if( !$simaz->isurl($url) ) {
		$error = 'URL ünvan düz deyil.';
	}
	
	
	else {
		$simaz->get('reklam', array(
			'title' => htmlspecialchars($request['title']),
			'url'  => $url,
			'place' => $request['place'],
			'amount' => intval($request['amount']),
		));
	}
}



include '../inc/header.php';
echo '<div class="mnav">Reklam Yerləşdir</div>';
echo '<div class="layer">';

	echo '<div align="center">';
		if($simaz->params['reklam'] != 'true') {
			echo 'Xidmət deaktiv edilib.<br/>';
			exit;
		}



		echo "<img src=\"img/logo.png\" alt=\"Azercell\"/><br/>";
		echo "Azercell Sim Şifrə ilə Reklam Yerləşdir";

		echo "<br/>---<br/>";
		echo 'Azercell Sim kontur şifrə ilə avtomatik və yerinde dəqiq <b>Reklam</b> əlavə edə bilərsiz.<br/>';
		echo 'Reklam və qeydiyyat almaqla siz Reytinqdə iştrak edib pulsuz Reklam qazanacaqsız.<br/>';
		echo $divide;
		echo "<b><a href=\"reytinq.php?$ref\">Reklam Reytinqi</a></b><br/>";
		echo $divide;
	echo '</div>';

	if( $error ) {
		
		echo '<div style="background: #f9e2bf;padding: 10px;">';
		echo '<b>Error:</b> '.$error.'.<br/>';
		echo '</div>';
		echo $divide;
	}


	echo '<form method="post" action="add_reklam.php?ref='.$ref.'">';
	echo 'Reklam mətni:<br/>';
	echo "<input name=\"title\" maxlength=\"40\" value=\"{$request['title']}\"/><br/>";

	echo 'Saytın ünvanı: (http yazmasazda olar)<br/>';
	echo "<input name=\"url\" maxlength=\"250\" value=\"{$request['url']}\"/><br/>";

	echo 'Reklam yeri:<br/>';
	$option = "<select name=\"place\">|";
	foreach( $placeList as $i ) {
		$option .= "<option value=\"{$i}\">{$simaz->replace[$i]['value']}</option>|";
	}
	$option .= '</select>';
	echo $option.'<br/>';

	echo "Məbləğ: (Neçə AZN-di?)<br/>";
	echo "<input name=\"amount\" size=\"8\" maxlength=\"4\" value=\"{$request['amount']}\"/><br/>";
	echo '<input type="hidden" name="action" value="save">';
	echo '<input type="submit" value="Davam et">';
	echo '<br/>----<br/>';


	$implode = null;
	if( $simaz->params['site_index_reklam'] == 'true' ) {
		echo "Saytın girişi: <br/>";
		$list = $simaz->array_numeric($simaz->params['site_index_reklam_price']);
		if( sizeof( $list ) > 0 ) {
			foreach( $list as $price => $time ) {
				$exp = explode('.', $time);

				if( $implode ) {
					$implode .= ', ';
				}
				
				$implode .= $price.' AZN = ';
				if( intval($time) > 0 ) {
					$implode .= intval($time).' saat';
				}
				
				if( sizeof($exp) > 1 ) {
					if( $exp[1] > 0 ) {
						$implode .= ' '.$exp['1'].' dəqiqə';
					}
				}
			}
			echo $implode.' ve s...<br/>';
		}
	}


	if( $simaz->params['chat_index_reklam'] == 'true' ) {
		
		if( $implode ) {
			echo '<br/>';
		}
		echo "Chatın girişi: <br/>";
		$list = $simaz->array_numeric($simaz->params['chat_index_reklam_price']);
		if( sizeof( $list ) > 0 ) {
			$implode = null;
			foreach( $list as $price => $time ) {
				$exp = explode('.', $time);

				if( $implode ) {
					$implode .= ', ';
				}
				
				$implode .= $price.' AZN = ';
				if( intval($time) > 0 ) {
					$implode .= intval($time).' saat';
				}
				
				if( sizeof($exp) > 1 ) {
					if( $exp[1] > 0 ) {
						$implode .= ' '.$exp['1'].' dəqiqə';
					}
				}
			}
			echo $implode.' ve s...<br/>';
		}
	}



	if( $simaz->params['enter_reklam'] == 'true' ) {
		
		if( $implode ) {
			echo '<br/>';
		}
		echo "Chatın dəhlizi: <br/>";
		$list = $simaz->array_numeric($simaz->params['enter_reklam_price']);
		if( sizeof( $list ) > 0 ) {
			$implode = null;
			foreach( $list as $price => $time ) {
				$exp = explode('.', $time);

				if( $implode ) {
					$implode .= ', ';
				}
				
				$implode .= $price.' AZN = ';
				if( intval($time) > 0 ) {
					$implode .= intval($time).' saat';
				}
				
				if( sizeof($exp) > 1 ) {
					if( $exp[1] > 0 ) {
						$implode .= ' '.$exp['1'].' dəqiqə';
					}
				}
			}
			echo $implode.' ve s...<br/>';
		}
	}



	if( $simaz->params['on_reklam'] == 'true' ) {

		if( $implode ) {
			echo '<br/>';
		}
		echo "Chatın onlinesi: <br/>";
		$list = $simaz->array_numeric($simaz->params['on_reklam_price']);
		if( sizeof( $list ) > 0 ) {
			$implode = null;
			foreach( $list as $price => $time ) {
				$exp = explode('.', $time);

				if( $implode ) {
					$implode .= ', ';
				}
				
				$implode .= $price.' AZN = ';
				if( intval($time) > 0 ) {
					$implode .= intval($time).' saat';
				}
				
				if( sizeof($exp) > 1 ) {
					if( $exp[1] > 0 ) {
						$implode .= ' '.$exp['1'].' dəqiqə';
					}
				}
			}
			echo $implode.' ve s...<br/>';
		}
	}


	echo $divide;
	echo "<a href=\"{$simaz->home}\">Ana sehife</a><br/>\n";
	echo $divide;
	echo 'Sim.az - Service<br/>';
echo '</div>';
include 'inc/footer.php';
?>