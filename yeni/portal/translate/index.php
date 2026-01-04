<?php
$title = 'Tercüme';
include $_SERVER['DOCUMENT_ROOT'].'/inc/functions.php';
include $_SERVER['DOCUMENT_ROOT'].'/inc/header.php';

echo '<div class="mnav"><a href="index.php">Tercüme</a></div>';
echo '<div class="layer">';

$text = $_POST['text'];
$from = $_POST['from'];
$to = $_POST['to'];

if(!empty($text)){
	$file = file('http://translate.google.az/m?hl=ru&sl='.$from .'&tl='.$to.'&ie=UTF-8&prev=_m&q='.urlencode($text).'');
	$html = @implode("", $file);

	$pos1 = strpos($html, '<div dir="ltr" class="t0">') + strlen('<div dir="ltr" class="t0">');
	$pos2 = strpos($html, '</div>', $pos1);

	if(!$pos1 or !$pos2){
		echo 'Sehv<br/><br/><a href="javascript:history.back(1)">« Geri</a>';
		echo '</div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit();
	}
	$pos2 = $pos2 - $pos1;
	$data = substr($html, $pos1, $pos2);
	echo ''.$from.' » '.$to.'<br/>';
	echo 'Tercümenin neticesi:<br/>';
	echo '<b>'.$data.'</b><br/><br/>';
}
else{
echo '<form method="post">';
echo 'Hansı dilden:<br/>';
echo '<select name="from">';
echo '<option value="en">İngilis</option>';
echo '<option value="az">Azerbaycan</option>';
echo '<option value="de">Alman</option>';
echo '<option value="ar">Ereb</option>';
echo '<option value="fr">Fransız</option>';
echo '<option value="zh-CN">Çin</option>';
echo '<option value="es">İspan</option>';
echo '<option value="it">İtalyan</option>';
echo '<option value="pt">Portuqal</option>';
echo '<option value="ru">Rus</option>';
echo '<option value="tr">Тürk</option>';
echo '<option value="ja">Yapon</option>';
echo '<option value="el">Yunan</option>';
echo '</select><br/>';
echo 'Hansı dile:<br/>';
echo '<select name="to">';
echo '<option value="az">Azerbaycan</option>';
echo '<option value="en">İngilis</option>';
echo '<option value="de">Alman</option>';
echo '<option value="ar">Ereb</option>';
echo '<option value="fr">Fransız</option>';
echo '<option value="zh-CN">Çin</option>';
echo '<option value="es">İspan</option>';
echo '<option value="it">İtalyan</option>';
echo '<option value="pt">Portuqal</option>';
echo '<option value="ru">Rus</option>';
echo '<option value="tr">Тürk</option>';
echo '<option value="ja">Yapon</option>';
echo '<option value="el">Yunan</option>';
echo '</select><br/>';
echo 'Metn:<br/>';
echo '<input name="text" type="text"/><br/>';
echo '<input type="submit" value="Tercüme et" />';
echo '</form>';
}

echo '<br/><a href="javascript:history.back(1)">« Geri</a>';
echo '</div>';
include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
?>