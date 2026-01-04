<?
error_reporting(0);
session_start();

//exit;
include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';
$title = 'Alochat';
include 'inc/header.php';


$checkAuth = checkOnlyAuth();

if(mysql_num_rows($checkAuth)==0){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=block">'.$__lng['giris'].'</a> | <a href="reg.php?loc=block">'.$__lng['qeyd ol'].'</a>', 2);
} 
 
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];


$mod = checkData($_GET['mod']);
switch($mod){
		default:

$portmanat = getPortmanat();
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';


echo 'Aşağıdakı üsullar vasitəsi ilə bal ala bilərsiniz:<br/>';
echo '<br />
<a class="payButton" href="buy.php?mod=pm_redirect" style="background-color: #818181; color: white; padding: 4px; border: 0px; font-size: 14px;">Portmanat Kodla bal al</a>
<br/><br/>
<a class="payButton" href="buy.php?mod=pm_hesab" style="background-color: #818181; color: white; padding: 4px; border: 0px; font-size: 14px;">Portmanat Hesabla bal al</a><br/>';

echo '<br/>Bal avtomatik olaraq əlavə olunur.<br/><br/>';
echo '1 AZN = 50 Bal<br/>';
echo '<small>Nümunə üçün 20 AZN -lik Portmanat kodunu yükləsəniz 1000 bal (20*50=1000) hesabınıza əlavə olunacaq.</small><br/><br/>';

echo '
<img src="img/million.jpg" alt="MilliÖN" height="120px" /><br/><br/>
<b>Portmanat kod almaq</b> üçün MilliÖN, eManat, Easypay aparatlarından Portmanat Kod almaq üçün Ödəmə kartları menyusuna daxil olunur, Portmanat Code düyməsi tapılıb vurulur, açılan ödəniş menyusunda kartın şifrə və seriya nömrəsinin SMS vasitəsilə mobil telefona göndərilməsindən ötrü mütləq olaraq işlətdiyiniz hər hansı mobil operatora məxsus telefon nomrə lazımi xanaya qeyd olunur, məbləğ aparata daxil edilir və təsdiqləmə düyməsi vurulur.<br/>
Əməliyyat başa çatdıqdan sonra ödəniş qəbzini götürməyi unutmayın. Çünki mobil telefonunuza gələn SMS ilə yanaşı, ödəniş qəbzinin də üzərində aldığınız Portmanat Kod və onun seriyası əks olunur.<br/>
<br/><img src="img/portmanat.png" alt="Portmanat" /><br/>';

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'pm_redirect';

$portmanat = getPortmanat();
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';

$amount = 0;
$method = 'code';
if(isset($_POST["portmanat_submit"]) and isset($_POST["amount"]) and intval($_POST["amount"])>0){
	$method = 'account';
	$amount = intval($_POST["amount"]);
}
$date = date("Y-m-d H:i:s");

mysql_query("INSERT INTO `aloaz_db`.`transactions` SET `user_id`='".$id."',`amount`='".$amount."',`payment_method`='".$method."',`payment_service`='aloaz_portmanat',`date`='".$date."',payment_status=0");
$order_id = mysql_insert_id();
?>
	Ödəmə səhifəsinə yönləndirilirsiniz...

	<form action='https://www.portmanat.az/checkout' method='post' id="portmanat-checkout" style="display: none">
		<input type='hidden' name='s_id' value='<?= $portmanat["portmanat_service_id"]?>'>
		<input type='hidden' name='o_id' value='<?= $order_id; ?>'>
		<input type='hidden' name='method' value='<?= $method?>'>
		<?php
		if($method == 'account'){
			echo "<input type='text' name='amount' value='".$amount."'>";
		}

		?>
		<input type='submit' value='Portmanat Kodla ödə'>
	</form>
	<script type="text/javascript">
		document.getElementById("portmanat-checkout").submit();
	</script>
<?php
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'pm_hesab';

$portmanat = getPortmanat();
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
echo '<div class="layer">';
?>
	<form action="buy.php?mod=pm_redirect" method="post">
		Məbləğ (maksimum 400):<br>
		<input type="text" name="amount" value="1"><br>
		<input type="submit" name="portmanat_submit" value="Portmanat Hesabla ödə" style="background: #D9534F; border: none; color: #fff; margin-top: 5px; padding: 5px; font-size: 14px;">
	</form>
<?php
echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'pm_success';

	echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
	echo '<div class="layer">';
	echo "Ödəməniz uğurla tamamlandı<br/>";
break;

case 'pm_error';

	echo '<div class="mnav"><a href="main.php">'.$title.'</a> » '.$__lng['bal almaq'].'</div>';
	echo '<div class="layer">';
	echo "Ödəmə prosesinde xeta bas verdi<br/>";
break;


}

echo '</div>';
include 'inc/footer.php';

?>
