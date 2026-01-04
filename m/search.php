<?
error_reporting(0);
session_start();

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="search.php">'.$__lng['axtaris'].'</a></div>';
echo '<div class="layer">';

$checkAuth = checkAuth('`id`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=search">'.$__lng['giris'].'</a> | <a href="reg.php?loc=search">'.$__lng['qeyd ol'].'</a>', 2);
}
$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];

if(!isset($_POST['submit']) && !isset($_GET['page'])){
	echo '<form method="post" action="search.php">';
	echo '<b>'.$__lng['loqin'].':</b> ('.$__lng['axtaris min herf'].')<br/>';
	echo '<input type="text" name="login" /><br/>';
	echo '<select name="similarity">';
	echo '<option value="0">'.$__lng['deqiq'].'</option>';
	echo '<option value="1">'.$__lng['oxsar'].'</option>';
	echo '</select><br/>';
	echo '<input type="submit" name="submit" value="'.$__lng['axtar'].'" />';
	echo '<input type="hidden" name="advanced" value="0" />';
	echo '</form><br/>';
	
	echo '-- '.$__lng['etrafli axtaris'].' --<br/><br/>';
	
	echo '<form method="post" action="search.php">';
	
	echo '<b>'.$__lng['cins'].':</b><br/>';
	echo '<select name="sex">';
	echo '<option value="100">'.$__lng['kisi'].' + '.$__lng['qadin'].'</option>';
	echo '<option value="0">'.$__lng['kisi'].'</option>';
	echo '<option value="1">'.$__lng['qadin'].'</option>';
	echo '</select><br/>';
	echo '<b>'.$__lng['yash'].':</b><br/>';
	echo $__lng['min'].': ';

	echo '<select name="year1">';

	$max_yash=70;
	 for ($min_yash=12; $min_yash<=$max_yash; $min_yash++){
		$min_il=date('Y') -$min_yash;
		echo '<option value="'.$min_yash.'">'.$min_yash.'</option>';
	}
	echo '</select> - '.$__lng['maks'].': ';
	echo '<select name="year2">';

	for($min_yash=12; $min_yash<=$max_yash; $min_yash++){
		$min_il=date('Y') -$max_yash+ $min_yash-12;
		$min2_yash=$max_yash-$min_yash+12;
		echo '<option value="'.$min2_yash.'">'.$min2_yash.'</option>';
	}
	echo '</select><br/>';

	echo '<label><input type="checkbox" name="photo" /> '.$__lng['yalniz sekli olanlar'].'</label><br/>';
	echo '<label><input type="checkbox" name="online" /> '.$__lng['yalniz onlayn olanlar'].'</label><br/>';

	echo '<input type="submit" name="submit" value="'.$__lng['axtar'].'" />';
	echo '<input type="hidden" name="advanced" value="1" />';
	echo '</form><br/>';
}
else{

$search_sex = trim(htmlspecialchars(mysql_escape_string($_POST['sex'])));
$search_sex = str_replace('$', '$$', $search_sex);
$search_year1 = trim(htmlspecialchars(mysql_escape_string($_POST['year1'])));
$search_year1 = str_replace('$', '$$', $search_year1);
$search_year2 = trim(htmlspecialchars(mysql_escape_string($_POST['year2'])));
$search_year2 = str_replace('$', '$$', $search_year2);
$search_on = checkData($_POST['online']);
$search_photo = checkData($_POST['photo']);

$search_similarity = intval($_POST['similarity']);
$_advanced = intval($_POST['advanced']);

$search_login = trim(htmlspecialchars(mysql_escape_string($_POST['login'])));
$search_login = str_replace('$', '$$', $search_login);

if(isset($_POST['submit'])){
	$_SESSION['search_sex'] = $search_sex;
	$_SESSION['search_year1'] = $search_year1;
	$_SESSION['search_year2'] = $search_year2;
	$_SESSION['search_on'] = $search_on;
	$_SESSION['search_photo'] = $search_photo;
	$_SESSION['search_login'] = $search_login;
	$_SESSION['search_similarity'] = $search_similarity;
	$_SESSION['advanced'] = $_advanced;
}

if($_SESSION['advanced'] > 0){
	$ins_sql = " `age` >= '".$_SESSION['search_year1']."' AND `age` <= '".$_SESSION['search_year2']."' ";
	if($_SESSION['search_sex'] != 100) $ins_sql .= " AND `sex` = '".$_SESSION['search_sex']."'";
	if($_SESSION['search_on'] == 'on') $ins_sql .= " AND `last_activity` > ".(time()-600).""; else $ins_sql .= " AND `last_activity` < ".(time()-600)."";
	if($_SESSION['search_photo'] == 'on') $ins_sql .= " AND `profile_photo` != ''";
}
else{
	if($_SESSION['search_similarity'] == 1) $ins_sql .= " `nickname` LIKE '%".$_SESSION['search_login']."%'";
	else $ins_sql .= " `nickname` LIKE '".$_SESSION['search_login']."'";
	
	if(strlen($_SESSION['search_login']) < 3) $error .= $__lng['loqin axtaris min herf'].'.<br/>';
}


$mysql_photo = ''; //Photo elave edende bu sirani sil

if(!empty($error)){
	echo $error.'<br/>';
	echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	echo '</div>';
	include 'inc/footer.php';
	exit();
}

$sql = mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE ".$ins_sql.";"); 
$all = mysql_result($sql, 0);
if($all > 1000) $all = 1000;
if($all==0){
	echo $__lng['axtarisa uygun user tapilmadi'].'.<br/><br/>';
	echo mysql_error();
	echo '<a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
	echo '</div>';
	include 'inc/footer.php';
	exit();
}

$show_limit = 6;
if(isset($_GET['page'])) $page = $_GET['page'];
else $page = 1;
if($page < 1) $page = 1;
if($page > $all) $page = 1;
$start = ($page-1)*$show_limit;

echo '<table width="100%" cellpadding="2">';

echo "Axtarış neticesinde tapılan istifadeçiler:<br/><br/>";

$q = mysql_query("SELECT `id`, `nickname`, `sex`, `full_name`, `age`, `profile_photo`, `last_activity`,`invisible` FROM `aloaz_db`.`user` WHERE ".$ins_sql." ORDER BY `last_activity` DESC LIMIT ".$start.", ".$show_limit.";");
while($users = mysql_fetch_array($q)){
	$users_login = $users['nickname'];
	$users_id = $users['id'];
	$users_photo= $users['profile_photo'];
	$users_name= $users['full_name'];
	$users_age = $users['age'];
	$users_sex = $users['sex'];
	$users_time = $users['last_activity'];
	$users_invisible = $users['invisible'];
	 
 

	if($users_sex==0){
		$users_sex_='K';
		$users_sex_img = 'man';
	}
	else{
		$users_sex_='Q';
		$users_sex_img = 'woman';
	}
	
	if($users_invisible==1){
		$onlineInfo = '<span style="font-size:11px; color: green;">'.$__lng['gizli'].'</span>';
	}else{
		if($users_time > (time() -600)) $onlineInfo = '<span style="font-size:11px; color: green;">'.$__lng['onlayn'].'</span>'; else $onlineInfo = '<span style="font-size:11px;">'.date('Y-m-d H:i', $users_time).'</span>';
	}
	
	
	if(empty($users_photo)) $img_file = 'img/'.$users_sex_img.'.gif'; 
	else $img_file = 'udata'.$users_photo;
	
	echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></td>
	<td width="100%"><a href="profile.php?uid='.$users_id.'">'.$users_login.'</a> <span style="font-size:11px">('; 
	echo $users_sex_.'/'; 
	echo ''.$users_age.')<br/>'.$users_name.'</span><br/>'.$onlineInfo.'</td></tr>';
}
echo '</table>';

echo '<div class="pageNav">';

$interval = 3;
$max = ceil($all/$show_limit);

if($page > 1) echo '<a id="pageButon" href ="search.php?page='.($page-1).'">&lt;</a> ';

if($page > $interval) echo ' <a id="pageButon" href ="search.php?page=1">1</a> ... ';

for($i=1; $i<=$max; $i++){
	if($page <= $interval && $i <=$interval){
		if($i != $page){
			echo ' <a id="pageButon" href="search.php?page='.$i.'">'.$i.'</a> ';
		}
		else{
			echo ' <span id="pageButon_off">'.$i.'</span> ';
		}
	}
	else{
		if($page > $interval && $i >= $page-2 && $i <= $page+2 && $i < $max){
			if($i != $page){
				echo ' <a id="pageButon" href="search.php?page='.$i.'">'.$i.'</a> ';
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
		echo ' <a id="pageButon" href="search.php?page='.$max.'">'.$max.'</a> ';
	}
	else{
		echo ' <span id="pageButon_off">'.$max.'</span> ';
	}
}

if($page < $max) echo '<a id="pageButon" href ="search.php?page='.($page+1).'">&gt;</a> ';

echo '</div>';

echo "<br/><br/><a href=\"search.php\">".$__lng['yeniden axtar']."</a><br/>";
}
echo '</div>';
include 'inc/footer.php';

?>
