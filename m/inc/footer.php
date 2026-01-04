<?php

if($_SESSION['auth'] && $_SERVER['SCRIPT_NAME'] != '/ch/index.php'){
	
	$_back = checkData($_GET['back']);
	
	//$pageLoc['online'] = ' - <a href="/online.php">Onlayn</a>';
	//$pageLoc['friends'] = ' - <a href="/friends.php">Dostlar</a>';
	//$pageLoc['msgsrecent'] = ' - <a href="/online.php">Onlayn</a> - <a href="/friends.php">Dostlar</a>';
	//'.$pageLoc[$_back].'
	
	if($_SERVER['SCRIPT_NAME'] == '/main.php') $navLinks = '';
	elseif($_SERVER['SCRIPT_NAME'] == '/online.php') $navLinks = ' - <a href="/friends.php">'.$__lng['dostlar'].'</a> - <a href="/messages.php?mod=unread">'.$__lng['mesajlar'].'</a>';
	elseif($_SERVER['SCRIPT_NAME'] == '/friends.php') $navLinks = ' - <a href="/online.php">'.$__lng['onlayn'].'</a> - <a href="/friends.php">'.$__lng['dostlar'].'</a>';
	else $navLinks = ' - <a href="/online.php">'.$__lng['onlayn'].'</a> - <a href="/friends.php">'.$__lng['dostlar'].'</a>';
	
	echo '<div class="graynav"><a href="/main.php">'.$__lng['bas sehife'].'</a> '.$navLinks.'</div>';
	
}
else{
	echo '<div class="graynav"><a href="/">m.alo.az</a></div>';
}
echo '<div class="layer">© '.date("Y").' AloChat';

if($_SERVER['SCRIPT_NAME'] == '/main.php') echo '<br/><a href="terms.php">İstifade şertleri</a>';

echo '</div></body></html>';

?>
