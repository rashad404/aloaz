<?php
require("../inc.php");
$link = connect_db();
$simaz->connect();

$act = '';
if( isset($_GET['act']) ) {
	$act = $_GET['act'];
}

if($_v->ver == 'wml') {
	$_v->set('version', 'vista1');
}

if( $act == 'buy' ) {
	$error1 = $error2 = null;
	$check_q = mysql_query("SELECT `id` FROM `buy_reg` WHERE `active`=1 AND `pack_id`='".$pack_id."' AND `date`='".$date."'");
	if(mysql_num_rows($check_q)>0){
		$error1 = '<font color="red">Sehv:</font> Bu tarixde qeydiyyat artıq alınıb. Başqa tarix seçin.<br/>';
	}

	if(strtotime($date)<strtotime(date('Y-m-d')) or strlen($date)!=10){
		$error1 = '<font color="red">Sehv:</font> Keçmiş vaxtı seçmek olmaz.<br/>';
	}
	$hour_query = mysql_query("SELECT `hour`,`price` FROM `buy_reg_cost` WHERE `id`='".$pack_id."' and `price` != 'x'");
	if( !$hour_array = mysql_fetch_array($hour_query) ) {
		$error1 = '<font color="red">Sehv:</font> Paket düzgün seçilməyib.<br/>';
	}
	$hour = $hour_array['hour'];
	$price = $hour_array['price'];
	
	if(strtotime($date)==strtotime(date('Y-m-d')) && $hour<=date("H")){
		$error1 = '<font color="red">Sehv:</font> Keçmiş vaxtı seçmek olmaz.<br/>';
	}
	
	$week_plus = strtotime(date('Y-m-d'))+86400*7;
	if(strtotime($date)>=$week_plus){
		$error1 = '<font color="red">Sehv:</font> 1 hefteden çox vaxt seçmek olmaz.<br/>';
	}
	
	if($_POST && $error1 == null) {
		$site_url = trim($_POST['site_url']);
		if (!preg_match("/^(http|https):/", $site_url)) {
			$site_url = 'http://'.$site_url;
		}
		
		$isurl = $simaz->isurl($site_url);
		if(!$isurl) {
			$error2 = '<b>Xeta</b>: Qeydiyyatın yönlənəcəyi ünvan düz deyil.<br/>----<br/>';
		}
		else {
			$simaz->get('buy_reg', array('date' => $date, 'pack' => $pack_id, 'hour' => $hour, 'site_url' => $site_url, 'amount' => $price), $simaz->home.'/simaz/buy_reg.php');
		}
	}
}

$_v->title('Qeydiyyatın satın alınması', 'center');
$_v->fsize1($fsize1);

if($simaz->params['reg_buy'] != 'true') {
	echo 'Xidmət deaktiv edilib.<br/>';
	echo $divide;
	echo "<a href=\"{$simaz->home}\">Ana sehife</a><br/>";
	echo $divide;
	echo 'Sim.az - Service<br/>';
	$_v->fsize2($fsize2);
	$_v->end('1',$link);
	exit;
}

echo "<img src=\"img/logo.png\" alt=\"Azercell\"/><br/>";
switch($act)
{
case 'buy':

	$_v->divide();
	$_v->align('left');
	
	$date = $_GET['date'];
	$pack_id = $_GET['pack_id'];

	if( $error1 ) {
		echo $error1;
		echo "<a href=\"buy_reg.php\">Geri</a><br/>";
		break;
	}
	
	echo 'Tarix: '.$date.'<br/>';
	echo 'Saat: '.$hour.':00 - '.$hour.':59<br/>';
	echo "<br/>";
	
	if( $error2 ) {
		echo $error2;
	}
	
	echo "Ödəniləcək məbləğ: ".$price." AZN<br/><br/>";
	
	$_v->action("buy_reg.php?act=buy&amp;pack_id={$pack_id}&amp;date={$date}&amp;ref=$ref");
		echo 'Qeydiyyatın yönlənəcəyi ünvan: (http yazmasazda olar)<br/>';
		echo $_v->input("<input type=\"text\" name=\"site_url\" value=\"{$_POST['site_url']}\"/>").'<br/>';
	echo $_v->submit('Davam et','action=go');

	echo '<a href="buy_reg.php">Geri qayıt</a><br/>';
break;

default:
?>
<style>
.table_buy_reg{
    max-width: 700px;
    min-width: 350px;
    width: 100%;
    font-size: 14px;
    border: 1px solid #CACACA;
    margin: auto;
	border-collapse: collapse;
}
.table_buy_reg tr {
    border-top: 1px solid #CACACA;
}
.table_buy_reg td {
    padding: 5px;
    border-right: 1px solid #CACACA;
}
.table_buy_reg thead > tr > th {
    padding: 5px;
    border-right: 1px solid #CACACA;
}
.table_buy_reg td > a{
    display: block;
}
.table_buy_reg td.buy:hover {
    background: #eaeaea;
}
.table_buy_reg td.buy a:hover {
    background: none;
	color: #0e63b8;
}
.table_buy_reg tr.info td {
    background: #ffdec6;
}
</style>
<?
	function getMonth($month){
			if($month=='01')$month_name = 'Yanvar';
			if($month=='02')$month_name = 'Fevral';
			if($month=='03')$month_name = 'Mart';
			if($month=='04')$month_name = 'Aprel';
			if($month=='05')$month_name = 'May';
			if($month=='06')$month_name = 'İyun';
			if($month=='07')$month_name = 'İyul';
			if($month=='08')$month_name = 'Avqust';
			if($month=='09')$month_name = 'Sentyabr';
			if($month=='10')$month_name = 'Oktyabr';
			if($month=='11')$month_name = 'Noyabr';
			if($month=='12')$month_name = 'Dekabr';
			return $month_name;
	}
	
	echo $divide;
	echo '<table class="table_buy_reg" cellpadding="0" cellspacing="0" border="0">';
		echo '<thead>';
			echo '<tr>';
				echo '<th colspan="2"></th>';

					$count_day_of_this_month =0;
					$count_day_of_next_month =0;
					$this_month = date("m");
					for($i=0;$i<7;$i++)
					{
							$this_year = date("Y");
							$month = date("m",time()+86400*$i);
							if($this_month==$month)
							{
									$count_day_of_this_month++;
							}
					}

					for($i=0;$i<7;$i++)
					{
							$next_year = date("Y",time()+86400*$i);
							$next_month = date("m",time()+86400*$i);
							if($this_month!=$month)
							{
									$count_day_of_next_month++;
							}
					}
					echo '<th colspan="'.$count_day_of_this_month.'">'.getMonth($this_month).' '.$this_year.'</th>';
					if($count_day_of_next_month>0)
					{
							echo '<th colspan="'.$count_day_of_next_month.'">'.getMonth($next_month).' '.$next_year.'</th>';
					}
			echo '</tr>';
		echo '</thead>';
		
		echo '<thead>';
			echo '<tr class="info">';
				echo '<td>Qiymet</td>';
				echo '<td>Saat</td>';
				for( $i = 0; $i < 7; $i++ ) {
					$day = date('d', time() + 86400 * $i);
					$month = date('m', time() + 86400 * $i);
					echo '<td>'.$day.'</td>';
				}
			echo '</tr>';
		echo '</thead>';
		
		echo '<tbody>';
			$query = mysql_query("SELECT `id`,`hour`,`price` FROM `buy_reg_cost` WHERE `price` != 'x' ORDER BY `id`");
			while($array = mysql_fetch_array($query))
			{
				echo '<tr>';
				echo '<td>'.$array['price'].' AZN</td>';
				echo '<td>'.$array['hour'].':00-'.$array['hour'].':59</td>';
				
				for($i=0;$i<7;$i++)
				{
						$show_date = date("Y-m-d",time()+86400*$i);
						$show_day = date("d",time()+86400*$i);
						$today = date("d");
						$now_hour = date("H");
						$check_q = mysql_query("SELECT `id` FROM `buy_reg` WHERE `active`= 1 AND `pack_id`='".$array['id']."' AND `date`='".$show_date."'");

						if($today == $show_day && $array['id'] <= $now_hour+1) {
							echo '<td><font color="red">x</font></td>';
						}
						elseif(mysql_num_rows($check_q)==0)
						{
							echo '<td class="buy"><a href="buy_reg.php?act=buy&amp;pack_id='.$array['id'].'&amp;date='.$show_date.'">Al</a></td>';
						}
						else
						{
							echo '<td><img src="img/ok.png" alt="alınıb"/></td>';
						}
				}
				echo '</tr>';
			}
		echo '</tbody>';

	echo '</table>';
	$_v->align('left');
	echo '<br/>';
	echo '<b>Qeyd:</b> Qeydiyyat Yönəltmə avtomatik rejimdə işleyir<br/>';
	echo 'Zəhmət olmasa gösterilən məbleği tam ödəyin əks halda qeydiyyat yönəlməyəcək...<br/>';
	$_v->divide();
	echo "<a href=\"{$simaz->home}\">Ana sehife</a><br/>";

    break;
}
echo $divide;
echo 'Sim.az - Service<br/>';
$_v->fsize2($fsize2);
$_v->end('1',$link);
