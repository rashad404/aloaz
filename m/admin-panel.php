<?
//exit;
error_reporting(0);
session_start();

$__posttopoint = 300;

include 'inc/func_n04.php';
include 'inc/functions_n04.php';
include 'inc/config.php';
include 'inc/params.php';
include 'inc/csrf_func.php';
include 'inc/lang/pack.php';

$title = 'AloChat';
include 'inc/header.php';

$checkAuth = checkAuth('`id`, `nickname`, `coins`, `point`, `profile_photo`, `msg_count`, `country_id`,`user_status`,`unseen`,`invisible`');
if($checkAuth == 'error'){
	displayError($__lng['qeydiyyatlilar daxil ola biler'].'<br/>'.$__lng['loqinle daxil olun'].'<br/><br/>'.
	'<a href="index.php?loc=pointserv">'.$__lng['giris'].'</a> | <a href="reg.php?loc=pointserv">'.$__lng['qeyd ol'].'</a>', 2);
}

$userrow = mysql_fetch_array($checkAuth);
$id = $userrow['id'];
$login = $userrow['nickname'];
$point = $userrow['coins'];
$xal = $userrow['point'];
$photo = $userrow['profile_photo'];
$post = $userrow['msg_count'];
$user_status = $userrow['user_status'];
$user_unseen = $userrow["unseen"];
$user_invisible = $userrow["invisible"];
$country = 'az';//$userrow['country_id'];
$admin_status = 0;

if(intval($user_status) == 0){
	displayError('Bu səhifəyə daxil olmağa icazəniz yoxdur. Yalnız vəzifəlilər daxil ola bilər.<br/><br/>'.
	'<a href="pointserv.php?mod=vipuser">Vəzifə al</a>', 0);
}
 
 $point_discount = '';
 $user_status_value = '';
if($user_status == 1) 
{
	$point_discount = '10%';
	$user_status_value = $__lng["user_status_1"];
}
elseif($user_status == 2) 
{
	$point_discount = '15%';
	$user_status_value = $__lng["user_status_2"];
}
elseif($user_status == 3)
{
	$point_discount = '20%';
	$user_status_value = $__lng["user_status_3"];
}
elseif($user_status == 10)
{
	$admin_status = 1;
	$point_discount = '20%';
	$user_status_value = $__lng["user_status_10"];
}

if(in_array($id,[1129446,1129447])){
	$admin_status=1;
}

$user_status_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user_status` WHERE `user_id`='".$id."' and `end_time`>'".time()."' ORDER BY ID DESC LIMIT 1"));

$expPhoto = explode('|', $photo);
$photoId = $expPhoto[1];

$mod = $_GET['mod'];

switch($mod){

default:

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » Admin səhifəsi </div>';
echo '<div class="layer">';

echo $__lng['Sizin status'].': '.$user_status_value.'<br/>';
echo $__lng['bitme tarixi'].': '.date("d-m-Y H:i",$user_status_row["end_time"]).'<br/><br/>';
echo '<a href="pointserv.php?mod=buy">+ '.$__lng['bal almaq'].' ('.$point_discount." ".$__lng["bonus"].')</a><br/><br/>';

echo '<form action="http://m.alo.az/ban.php?mod=request" method="post">
Loqin:<br/>
<input type="text" name="b_login" value="" /><br/>
<input type="submit" name="submit" value="Ban et" />
</form><br/>';
echo '- <a href="team-panel.php?mod=room">'.$__lng["otaq mesajlari silmek"].'</a><br/>';
echo '- <a href="ban.php">'.$__lng['ban olanlar'].'</a><br/>';
echo '- <a href="team-panel.php?mod=unseen">'.$__lng['profile baxanda qeyde alinmasin'].'</a><br/>';
echo '- <a href="team-panel.php?mod=invisible">'.$__lng['online gorunmemek'].'</a><br/>';
if($admin_status==1){
	echo '<br />- <a href="admin-panel.php?mod=share">Paylaşım silmek</a><br/>';	
	echo '- <a href="admin-panel.php?mod=register">Yeni profil</a><br/>';	
	echo '- <a href="admin-panel.php?mod=profile">Profili deyiş</a><br/>';	
	echo '- <a href="admin-panel.php?mod=setteam">'.$__lng["vezife vermek"].'</a><br/>';	
	echo '- <a href="admin-panel.php?mod=transactions">'.$__lng["odenislere bax"].'</a><br/>';
	echo '- <a href="admin-panel.php?mod=team">Vezifeli istifadeçiler</a><br/>';
	echo '- <a href="admin-panel.php?mod=sameuser">Eyni ip ile giren istifadeçiler</a><br/>';
	echo '- <a href="admin-panel.php?mod=coin-logs">Bal emeliyyatları</a><br/>';
	echo '- <a href="task/index.php">'.$__lng["muraciet et"].'</a><br/>';
}

echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';

break;

case 'list':

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a>  » Vəzifəli istifadəçilər</div>';
echo '<div class="layer">';


$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 3");
echo '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Boss</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 2");
echo '<img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Moder</b> ('.mysql_num_rows($vip_query).'): <br/>';
echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 1");
echo '<img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Vip</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';

echo '<br/><a href="pointserv.php?mod=vipuser">Vəzifə almaq</a><br/>';

break;




case 'setteam';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a>  » '.$__lng["vezife vermek"].'</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}
	
		// DELETE A Status
		$_del = intval($_GET['del']);
		$_uid = intval($_GET['uid']);

		if($_del == 1){
			echo '<div class="notif" align="center">';
			echo 'Silmek istediyinize eminsiniz?<br/>';
			echo '<a href="">'.$__lng['xeyr'].'</a> / ';
			echo '<a href="?mod=setteam&amp;uid='.$_uid.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
			echo '</div>';
		}
		if($_del == 2){
				$sql = mysql_query("select id,user_status from `aloaz_db`.`user` where id='".$_uid."' and user_status>0 limit 1");
				$row = mysql_fetch_assoc($sql);
				if($row){
					$id = $row["id"];
					$user_status = $row['user_status'];
					$status_query = mysql_query("SELECT id FROM `aloaz_db`.`user_status` WHERE `user_id`='".$_uid."' and `ended`=0");
					if(mysql_num_rows($status_query)>0){
						$status_row = mysql_fetch_assoc($status_query);
						mysql_query("UPDATE `aloaz_db`.`user_status` SET `ended`=1 WHERE `id`='".$status_row["id"]."'");
						mysql_query("UPDATE `aloaz_db`.`user` SET `user_status`=0,`invisible`=0,`unseen`=0 WHERE id='".$_uid."' LIMIT 1");
						echo "Status silindi<br /><br />";
					}else{
						echo 'Bu istifadecinin vezifesi yoxdur<br /><br />';
					}
				}else{
					echo 'Bu istifadeçi tapılmadı yada vezifesi yoxdur<br /><br />';
					break;
				}
					
				
			
		}
	 
         if($_POST and $_POST["submit"]){
			 
            $nickname = htmlspecialchars(trim($_POST["nickname"])); 
			$user = mysql_fetch_assoc(mysql_query("SELECT `user_status`,`id`,`nickname` FROM `aloaz_db`.`user` WHERE `nickname`='".$nickname."' LIMIT 1"));
			if($user){
				if(isset($_GET["status"]) and intval($_GET["status"])==1){
					$user_status = intval($_POST["user_status"]);
 					if(in_array($user_status,[1,2,3])){
 						$update = mysql_query("UPDATE `aloaz_db`.`user` SET `user_status`='".$user_status."' WHERE `id`='".$user["id"]."' LIMIT 1");
						$begin_time = time();
						$end_time = $begin_time + 30*24*3600;
						 
						$insert = mysql_query("INSERT INTO `aloaz_db`.`user_status` SET `user_id`='".$user["id"]."',`status`='".$user_status."',`begin_time`='".$begin_time."',`end_time`='".$end_time."';");
						 
						if($update){
							echo "İstifadeçiye vezife verildi";
							setNotification($user["id"],$paramsArray["NOT_USER_STATUS".$user_status],time(),$id,$login);

						}else{
							echo "Xeta baş verdi";
						}
					}else{
						echo "Vezife düzgün qeyd olunmayıb";
					}
				}else{
					$user_team = $user["user_status"];
					$user_team_value = '';
					if($user_team == 1)  $user_team_value = $__lng["user_status_1"];
					elseif($user_team == 2) $user_team_value = $__lng["user_status_2"];			 
					elseif($user_team == 3) $user_team_value = $__lng["user_status_3"];			 
					elseif($user_team == 10) $user_team_value = $__lng["user_status_10"];
					else $user_team_value = 'Yoxdur';
					echo $nickname." istifadeçininin statusu:".$user_team_value;
					if($user_team>0 and $user_team<10){
						echo " <a href='admin-panel.php?mod=setteam&amp;uid=".$user["id"]."&amp;del=1'>Statusu sil</a>";
					}
					echo "<br />";
					echo '<form name="form" method="post" action="admin-panel.php?mod=setteam&amp;status=1">';
					echo '<br/><br/>';
					echo '<input type="text" name="nickname" value="'.$nickname.'" readonly="readonly"><br /><br />'; 
					echo '<select name="user_status">
							<option value="1">VIP</option>
							<option value="2">Moder</option>
							<option value="3">Boss</option>
						</select>';
					echo '<br /><br /><input type="submit" name="submit" value="Dəyiş" /><br/>';
				}				
		
            }else{
				echo $nickname."  nikli istifadeçi tapılmadı";
			}

        }else{
				echo '<form name="form" method="post" action="admin-panel.php?mod=setteam">';
				echo 'Nicki daxil edin:<br/>';
				echo '<input type="text" name="nickname" value=""><br />'; 
				echo '<input type="submit" name="submit" value="Təsdiqlə" /><br/>';
        } 
		 

		

	echo "<br />"; 

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';



break;
case 'transactions';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a>  » '.$__lng["odenislere bax"].'</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}
	
	
		$transactions = []; 
        $where = 'WHERE `payment_status`=1';
        $begin_date =  date("Y-m-d 00:00");
        $end_date =  date("Y-m-d 23:59");
         if($_POST and $_POST["submit"]){
			 
            $begin_date = htmlspecialchars(trim($_POST["begin_date"]));
            $end_date = htmlspecialchars(trim($_POST["end_date"]));
            $where.= ' AND `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

            if($begin_date=="" and $end_date!==""){
                $where.= ' AND `date`<="'.$end_date.'"';
            }elseif($begin_date!=="" and $end_date==""){
                $end_date = date("Y")."-".date('m')."-".(date('d')-1);
                $where.= ' AND `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';
            }elseif($begin_date=="" and $end_date==""){
                $begin_date = date("Y")."-".date('m')."-".(date('d')-1);
                $end_date = date("Y-m-d");
                $where = ' AND `date`>="'.$begin_date.'" and `date`<"'.$end_date.'"';
            }

        }else{
             $where.= ' AND `date`>="'.$begin_date.'" and `date`<="'.$end_date.'"';

        }
         $transaction_sum = mysql_fetch_assoc(mysql_query('SELECT sum(`amount`) as `sum` FROM `aloaz_db`.`transactions` '.$where.' ORDER BY `id` DESC'));
		 

		echo '<form name="form" method="post" action="admin-panel.php?mod=transactions">';
		echo 'Baslanğıc tarixi ve bitme tarixini qeyd edin<br/><br/>';
		echo '<input type="text" name="begin_date" value="'.date("Y-m-d 00:00",strtotime($begin_date)).'"> - '; 
		echo '<input type="text" name="end_date" value="'. date("Y-m-d 23:59",strtotime($end_date)).'"><br />'; 
		echo '<input type="submit" name="submit" value="Bax" /><br/>';

	echo "<br />"; 
	echo $begin_date." və ".$end_date." aralığında cəmi ödəniş məbləği: <b>".number_format($transaction_sum["sum"], 2, '.', ',')." AZN</b> <br /> <br />";

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';



break;

case 'room';

echo '<div class="mnav"><a href="main.php">'.$title.'</a>  » <a href="admin-panel.php">Admin</a> » '.$__lng["otaq mesajlari silmek"].'</div>';
echo '<div class="layer">';
	if(intval($user_status) == 0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}

if($_POST['submit'] == ''){
	echo $__lng['otaq mesajlarini silmek haqqinda'].'<br/>'; 
  
	echo '<br/>';
	
	echo '<form name="form" method="post" action="admin-panel.php?mod=room">';
		echo $__lng["otaq sec"].':<br/>';
		echo '<select name="room_id">';
			$rooms_query = mysql_query("SELECT `id`,`name` FROM `aloaz_db`.`room` WHERE `type`=1 and `id`!=10"); 
			while($room = mysql_fetch_assoc($rooms_query)){
				echo '<option value='.$room["id"].'>'.$room["name"].'</option>';
			}
		echo '</select> <br/><br/>';
		echo '<input type="submit" name="submit" value="Mesajları sil" /><br/>';

	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{
	$_room_id = intval($_POST["room_id"]);
	$room = mysql_fetch_assoc(mysql_query("SELECT `id`,`name`,`type` FROM `aloaz_db`.`room` WHERE `id`='".$_room_id."' and `type`=1"));
	if($room["type"]==1){
		if($admin_status==1){
			$delete = mysql_query("DELETE FROM `aloaz_db`.`room_msgs` WHERE `rid`='".$_room_id."'");
			$_message = 'Admin terefinden otaqdakı mesajlar silinmişdir.';
			mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = 'Alochat', `message` = '".$_message."', `uid` = '1', `rid` = '".$_room_id."', `time` = '".time()."'");
			$textLog = $login.' niki  '.$room["name"].' otaqdakı mesajlari silmişdir.';
			mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`nickname`='".$login."',`to_id`='".$_room_id."',`text`='".$textLog."',`date`='".date("Y-m-d H:i:s")."'");
			if($delete) echo 'Silindi <br/>'; else echo 'Databse error [1126]<br/>';
		}else{
			if($room["id"]!=10 and $room["type"]==1){
				$_message = 'Otaqdakı yazılar 1 deqiqeden sonra '.$login.' terefinden silinecek.';
				mysql_query("INSERT INTO `aloaz_db`.`room_msgs` SET `login` = 'Alochat', `message` = '".$_message."', `uid` = '1', `rid` = '".$_room_id."', `time` = '".time()."'");
				$del_time = time() + 60;
				mysql_query("UPDATE `aloaz_db`.`room` SET `del_time`='".$del_time."',`del_nickname`='".$login."' WHERE `id`='".$_room_id."' LIMIT 1");
				$textLog = $login.' niki  '.$room["name"].' otaqdakı mesajlari silmişdir.';
				mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`nickname`='".$login."',`to_id`='".$_room_id."',`text`='".$textLog."',`date`='".date("Y-m-d H:i:s")."'");
				echo 'Silindi <br/>'; 
			}else{
				echo "Bu otağı sile bilmezsiniz";
			}
			
		}	

	}else{
		echo 'Otaq tapılmadı';
	}
	  
}

break;

case 'share';

echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a>  » <a href="admin-panel.php?mod=share">Paylaşım silmek</a></div>';
echo '<div class="layer">';
 

	if($admin_status==0){
		echo $__lng['siz vip olmadiginizdan icaze yoxdur'].'. <a href="pointserv.php?mod=vipuser">- '.$__lng['vip user olmaq'].'</a><br/>';
		break;
	}

if($_GET['submit'] != 1){
	echo 'Paylaşımın id-sini qeyd ederek sile bilersiniz<br/>'; 
  
	echo '<br/>';
	
	echo '<form name="form" method="post" action="admin-panel.php?mod=share&del=1&submit=1">';
		echo 'Paylaşım id:<br/>';
		echo '<input type="text" name="sid"><br />';
		echo '<input type="submit" name="submit" value="Paylaşımı sil" /><br/>';

	echo '</form>';

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
}
else{ 
	$_share_id = intval($_REQUEST["sid"]);
	$row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`share` WHERE `id`='".$_share_id."'"));
	if($row){
		if($admin_status==1){
			$_del = intval($_GET['del']);
			$_sid = intval($_REQUEST['sid']);

			if($_del == 1){
				$share_user = mysql_fetch_assoc(mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id`='".$row["user_id"]."' LIMIT 1"));
				echo '<div class="notif" align="center">';
				echo $share_user["nickname"].' nikli istifadeçinin '.$_share_id.' idli paylaşımını silmek istediyinize eminsiniz?<br/>';
				echo '<a href="?mod=share">'.$__lng['xeyr'].'</a> / ';
				echo '<a href="?mod=share&amp;del=2&amp;sid='.$_sid.'&amp;submit=1">'.$__lng['beli'].'</a><br/>';
				echo '</div>';
			}
			if($_del == 2){  
			 
				$attach = $row['attach'];
				$date = $row['time'];				
				mysql_query("DELETE FROM `aloaz_db`.`share` WHERE `id` = '".$_sid."' LIMIT 1;");
				if(mysql_affected_rows() > 0){
					echo "silindi";
					mysql_query("DELETE FROM aloaz_db.`share_comment` WHERE `sid` = '".$_sid."';");
					mysql_query("DELETE FROM aloaz_db.`share_like` WHERE `sid` = '".$_sid."';");
					$textLog = $login.' niki  '.$row["id"].'-li paylaşımı sildi.';
				mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`nickname`='".$login."',`to_id`='".$row["id"]."',`text`='".$textLog."',`date`='".date("Y-m-d H:i:s")."'");
					unlink('/home/aloaz/public_html/alochat.com/public_html/images/share/uploads/'.date('Ym', $date).'/'.$attach.'');
					unlink('/home/aloaz/public_html/alochat.com/public_html/images/share/thumbs/'.date('Ym', $date).'/'.$attach.'');
					unlink('/home/aloaz/public_html/alochat.com/public_html/images/share/resized/'.date('Ym', $date).'/'.$attach.'');
				}else{
					echo "Xeta!";
				}
			} 
		}else{
			echo "sizin bele huququnuz yoxdur"; 
			
		}	

	}else{
		echo 'Paylaşım tapılmadı';
	}
	  
}

break;
case 'coin-logs';
?>
<style>
td {
  border-bottom:1pt solid #cdcdcd;
  padding:5px;
}
</style>
<?php
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a> » Bal tarixçesi</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo 'Sizin bura girişinize icaze yoxdur<br/>';
		break;
	}	
	
	if($_POST and $_POST["submit"]){
		$nickname = htmlspecialchars(trim($_POST["nickname"])); 
		if(isset($_POST["log_type"]) and trim($_POST["log_type"])!='all'){
			$log_type = trim(htmlspecialchars($_POST["log_type"])); 
			$where_type = "and `text`='".$log_type."'";
		}
			$user = mysql_fetch_assoc(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `nickname`='".$nickname."'  LIMIT 1"));
			if($user){
				echo $nickname." istifadəçinin bal əməliyyatları <br />";
				$logs_query = mysql_query("SELECT * FROM `aloaz_db`.`coin_logs` WHERE user_id='".$user["id"]."' ".$where_type." ORDER BY id DESC");
	
				if(mysql_num_rows($logs_query) == 0){
					echo 'Bal emeliyyatı tapılmadı<br/>';
				}
				else{
					echo '<form name="form" method="post" action="admin-panel.php?mod=coin-logs">';
					echo '<br />Emeliyyatı seçin: ';
					echo '<select name="log_type">';
					echo '<option value="all">Hamısı</option>';
					echo '<option value="buy_coin_portmanat"'; echo $log_type=='buy_coin_portmanat'?' selected="selected" ':"";echo '>Portmanat ilə bal almaq</option>';
					echo '<option value="buy_coin"'; echo $log_type=='buy_coin'?' selected="selected" ':"";echo '>Bal almaq</option>';
					echo '<option value="send_coin"'; echo $log_type=='send_coin'?' selected="selected" ':"";echo '>Bal göndərmək</option>';
					echo '<option value="receive_coin"'; echo $log_type=='receive_coin'?' selected="selected" ':"";echo '>Hədiyyə gələn bal</option>';
					echo '<option value="set_vip"'; echo $log_type=='set_vip'?' selected="selected" ':"";echo '>Vip istifadəçi olmaq</option>';
					echo '<option value="add_point"'; echo $log_type=='add_point'?' selected="selected" ':"";echo '>Xal almaq</option>';
					echo '<option value="change_nick"'; echo $log_type=='change_nick'?' selected="selected" ':"";echo '>Nik dəyişmək</option>';					
					echo '<option value="delete_nick"'; echo $log_type=='delete_nick'?' selected="selected" ':"";echo '>Nik silmək</option>';
					echo '<option value="receive_coin_alochat"'; echo $log_type=='receive_coin_alochat'?' selected="selected" ':"";echo '>Alochatdan gələn bal</option>';
					echo '<option value="set_team"'; echo $log_type=='set_team'?' selected="selected" ':"";echo '>Vəzifə almaq</option>';
					echo '<option value="own_smile"'; echo $log_type=='own_smile'?' selected="selected" ':"";echo '>Nik dəyişmək</option>';
					echo '<option value="rengli_nick"'; echo $log_type=='rengli_nick'?' selected="selected" ':"";echo '>Rəngli nik</option>';
					echo '<option value="buy_coin_post"'; echo $log_type=='buy_coin_post'?' selected="selected" ':"";echo '>Post ilə bal almaq</option>';
					echo '<option value="bonus_coin_activity"'; echo $log_type=='bonus_coin_activity'?' selected="selected" ':"";echo '>Aktivlikden qazanılan bonus bal</option>';
					echo '</select>';
					echo '<input type="hidden" name="nickname" value="'.$nickname.'"><br />'; 
					echo '<input type="submit" name="submit" value="Təsdiqlə" /><br/><br/>';
					echo '<table border="0" style="text-align:left; width: 100%; max-width: 600px"><tr style="font-weight:bold;"><td>#</td><td>Emeliyyat</td><td>Nik</td><td>Bal</td><td>Tarix</td></tr>';
					$i = 1;
					while($log = mysql_fetch_assoc($logs_query)){
						echo '<tr><td>'.$i.'</td><td>'.$paramsArray[$log["text"]].'</td>'; 
						$fromNick=$log["user_id2"];
						if($log["user_id2"]>0){
							$user2 = mysql_fetch_assoc(mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id`='".$log["user_id2"]."'"));
							if($user2){
								$fromNick = $user2["nickname"];
							}
						}		
						echo '<td>'.$fromNick.'</td>';
						echo '<td>';
						$sym = $log["type"]==1?'-':'+';
						
						echo $sym.$log["coins"].'</td><td>'.$log["date"].'</td></tr>';
						$i++;
						
						// $sym = $log["type"]==1?'-':'+'; 
						// echo 'Tarix: '.$log["date"].'<br/>';
						// echo 'Emeliyyat: '.$paramsArray[$log["text"]].'<br/>';
						// echo 'Bal: '.$sym.$log["coins"].'<br/><br/>';
					}
					echo '</table>';
				}

			}else{
				echo 'İstifadeçi tapılmadı<br />';
			}
	}else{
			echo '<form name="form" method="post" action="admin-panel.php?mod=coin-logs">';
			echo 'Nicki daxil edin:<br/>';
			echo '<input type="text" name="nickname" value=""><br />'; 
			echo '<input type="submit" name="submit" value="Təsdiqlə" /><br/>';
         } 
	
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
break;


case 'kontur-coins';
?>
<style>
td {
  border-bottom:1pt solid #cdcdcd;
  padding:5px;
}
</style>
<?php
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a> » Azercell şifrələri</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo 'Sizin bura girişinize icaze yoxdur<br/>';
		break;
	}	
	
	if(isset($_POST["log_type"]) and in_array(intval($_POST["log_type"]),array(0,1,2))){
		$log_type = intval($_POST["log_type"]); 
		$where_type = " WHERE `status`='".$log_type."'";
	} 
	
	echo "Azercell şifrələri<br />";
	$logs_query = mysql_query("SELECT * FROM `aloaz_db`.`kontur_coins`  ".$where_type." ORDER BY id DESC");
	
			 
	echo '<form name="form" method="post" action="admin-panel.php?mod=kontur-coins">';
	echo '<br />Emeliyyatı seçin: ';
	echo '<select name="log_type">';
	echo '<option value="10">Hamısı</option>';
	echo '<option value="0"'; echo $log_type=='0'?' selected="selected" ':"";echo '>Ödenmemiş</option>'; 
	echo '<option value="1"'; echo $log_type=='1'?' selected="selected" ':"";echo '>Ödenmiş</option>'; 
	echo '<option value="2"'; echo $log_type=='2'?' selected="selected" ':"";echo '>Qebul edilməyən</option>'; 
	echo '</select>';
	echo '<input type="submit" name="submit" value="Təsdiqlə" /><br/><br/>';
	echo '<table border="0" style="text-align:left; width: 100%; max-width: 600px"><tr style="font-weight:bold;"><td>#</td><td>Nik</td><td>Məbləğ</td><td>Tarix</td><td>Status</td><td></td></tr>';
	$i = 1;
	while($log = mysql_fetch_assoc($logs_query)){
		echo '<tr><td>'.$i.'</td>'; 
		$fromNick=$log["user_id"];
		if($log["user_id"]>0){
			$user2 = mysql_fetch_assoc(mysql_query("SELECT `nickname` FROM `aloaz_db`.`user` WHERE `id`='".$log["user_id"]."'"));
			if($user2){
				$fromNick = $user2["nickname"];
			}
		}		
		echo '<td>'.$fromNick.'</td>';
		echo '<td>'; 
		
		echo $log["amount"].'</td><td>'.$log["insert_date"].'</td>';
		if($log["status"]==0) $tr_status = 'Ödənməyib';
		elseif($log["status"]==1) $tr_status = 'Ödənib';
		elseif($log["status"]==2) $tr_status = 'Qebul edilmeyib';
		else $tr_status = ' - ';
		echo '<td>'.$tr_status.'</td>';
		echo '<td><a href="admin-panel.php?mod=kontur-log&id='.$log["id"].'">Bax</a></td></tr>';
		$i++;		 
	}
	echo '</table>'; 	
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
break;
case 'kontur-log';
?>
<style>
td {
  border-bottom:1pt solid #cdcdcd;
  padding:5px;
}
</style>
<?php 
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a> » <a href="admin-panel.php?mod=kontur_coins">Azercell şifrələri</a>  » Bax </div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo 'Sizin bura girişinize icaze yoxdur<br/>';
		break;
	}	
	
	if(isset($_GET["id"]) and intval($_GET["id"])>0){
		$tr_id = intval($_GET["id"]); 
		$kontur_query = mysql_query("SELECT * FROM `aloaz_db`.`kontur_coins` WHERE `id`='".$tr_id."' LIMIT 1");
		if(mysql_num_rows($kontur_query)==0){
			echo "Şifrə tapılmadı";
			break;
		}
		$kontur_row = mysql_fetch_assoc($kontur_query);
		$user_query = mysql_query("SELECT id,`nickname`,`coins` FROM `aloaz_db`.`user` WHERE `id`='".$kontur_row["user_id"]."' LIMIT 1");
		if(mysql_num_rows($user_query)==0){
			echo "İstifadeçi tapılmadı";
			break;
		}
		
		$user_row = mysql_fetch_assoc($user_query);
		
		if($_POST){
			$_user_id = intval($_POST["user_id"]);
			$_coins = intval($_POST["coins"]);
			$_amount = intval($_POST["amount"]);
			if($user_row["id"]!=$_user_id){
				echo "Bal yüklemek istediyiniz istifadeçinin id-si uyğun gelmir";
				break;
			}
			
			if($_coins==0){
				echo "Elave olunan bal 0dan çox olmalıdır";
				break;
			}
			
			
			if($_coins==0){
				echo "Mebleğ 0 ola bilmez";
				break;
			}
			$new_coins = $user_row["coins"] + $_coins;
			$update_user = mysql_query("UPDATE `aloaz_db`.`user` SET `coins`=".$new_coins." WHERE `id`='".$_user_id."' LIMIT 1");
			if($update_user){
				$update_kontur = mysql_query("UPDATE `aloaz_db`.`kontur_coins` SET `status`=1,`coins`='".$_coins."',`amount`='".$_amount."' WHERE `id`='".$tr_id."'LIMIT 1");
				$method = 'azercell_shifre';
				$date = date("Y-m-d H:i:s");
				mysql_query("INSERT INTO `aloaz_db`.`transactions` SET `user_id`='".$_user_id."',`amount`='".$_amount."',`coins`='".$_coins."',`payment_method`='".$method."',`date`='".$date."',payment_status=1");
				mysql_query("INSERT INTO `aloaz_db`.`coin_logs` SET `user_id`='".$_user_id."',`coins`='".$_coins."',`type`=2,`text`='".$paramsArray["LOG_AZERCELL_SHIFRE"]."',`date`='".date("Y-m-d H:i:s")."';");
				echo "Bal elave edildi<br />";
				echo "<a href='admin-panel?mod=kontur-coins'>Azercell şifreler sehifesi</a>";
			}		
			
		}
				
		echo "Əməliyyat: ".$kontur_row["id"]."<br />";
		echo "Nik: <a href='profile.php?uid=".$user_row["id"]."'>".$user_row["nickname"]."</a><br /><br />";
		echo "Məbləğ: ".$kontur_row["amount"]." AZN <br />";
		if($kontur_row["status"]==0){
			echo "Bal: ".$paramsArray["1_azn"]*$kontur_row["amount"]."<br /><br />";
			echo "Şifre: *131*0502472932*".$kontur_row["kontur"]."#<br />";
			echo "Şifrəni yüklə: "."<a href='tel:*131*0502472932*".$kontur_row["kontur"]."#'>Yükle</a><br />";
			echo "<form action='admin-panel.php?mod=kontur-log&id=".$kontur_row["id"]."' method='post'>";
			echo '<input type="hidden" name="user_id" value="'.$kontur_row["user_id"].'"><br />';	
			echo 'Məbləğ: <input type="text" name="amount" value="'.$kontur_row["amount"].'" style="width:50px"><br />';	
			echo 'Bal: <input type="text" name="coins" value="'.$kontur_row["coins"].'" style="width:50px"><br />';	
			echo '<input type="submit" value="Tesdiqle">';
			echo "</form>";		
 		}elseif($kontur_row["status"]==1){
			echo "Bal: ".$kontur_row["coins"];
		}elseif($kontur_row["status"]==2){
			echo "Bal: ".">Şifrə qəbul olunmayıb";
		}
		
	}else{
		echo 'Səhv var<br/>';
		break;
	} 
	
	echo '<br/><a href="admin-panel.php?mod=kontur-coins">« '.$__lng['geri'].'</a>';
break;


case 'sameuser';
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a> » Eyni cihaznan girenler</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo 'Sizin bura girişinize icaze yoxdur<br/>';
		break;
	}
	
	
	if($_POST and $_POST["submit"]){
		$nickname = htmlspecialchars(trim($_POST["nickname"])); 
			$user = mysql_fetch_assoc(mysql_query("SELECT `ua`,`ip` FROM `aloaz_db`.`user` WHERE `nickname`='".$nickname."' LIMIT 1"));
			if($user){
				$query = mysql_query("SELECT * FROM `aloaz_db`.`user` WHERE `ip`='".$user["ip"]."' and `ua`='".$user["ua"]."'");
				echo $nickname." istifadeçisi ile eyni ip və user agente mexsus istifadeçiler<br />";
				echo '<table cellpadding="2">';

				while($row = mysql_fetch_assoc($query)){
					$onuser_id = $row['id'];
					$onuser_age = $row['age'];
					$onuser_sex = $row['sex'];
					$onuser_login = $row['nickname'];

					if($row["rnickname"]!=""){
						$onuser_login = '<img src="rn/tmp/'.$row["rnickname"].'" style="vertical-align:middle" alt="'.$row["nickname"].'"/>';
					}
					
					$onuser_name = $row['full_name'];
					$onuser_user_status = $row['user_status'];
					$onuser_photo = $row['profile_photo'];
					$onuser_status = htmlspecialchars(stripslashes($row['last_post']));
					$onuser_point = $row['point'];
					$onuser_friend = $row['only_friend'];
					
					$vip_img = '';
					if($onuser_user_status>0){
						if($onuser_user_status==1) $vip_img = '<img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" >';
						elseif($onuser_user_status==2) $vip_img = '<img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" >';
						elseif($onuser_user_status==3) $vip_img = '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" >';
					} 
					if($onuser_friend == 1) $lock_img = '<img src="img/lock.png" alt="." style="float:right; padding-right: 10px" />'; else $lock_img = '';
					
					if(strlen($onuser_status) > 50) $onuser_status = mb_substr($onuser_status,0,50,"utf-8"); 
					
					$expPhoto = explode('|', $onuser_photo);
					$photoName = $expPhoto[0];
					$photoId = $expPhoto[1];

					if($onuser_sex==0){
						$onuser_sex_=$__lng['k'];
						$onuser_sex_img ='man';
					}
					else{
						$onuser_sex_=$__lng['q'];
						$onuser_sex_img='woman';
					}
				 
					
					if(empty($onuser_photo)) $img_file = 'img/'.$onuser_sex_img.'.gif';
					else $img_file = 'https://m.alo.az/udata'.$onuser_photo; 
					
					echo '<tr '; echo $i++ % 2 ? ' style="background: #f6f4f4"' : ''; echo '><td><a href="profile.php?uid='.$onuser_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:60px;height:60px;" /></a></td>
					<td width="300px"><a href="profile.php?uid='.$onuser_id.'&amp;back=online">'.$onuser_login.'</a> <span style="font-size:11px">('; 
					echo $onuser_sex_.'/'; 
					echo ''.$onuser_age.') '.$vip_img.$lock_img.'<br/>'.$onuser_status.'</span><br/>';
					if($onuser_point > 0) echo '<span style="font-size:11px; color: green;">+ '.$onuser_point.' '.$__lng['xal'].'</span>';
					echo '</td></tr>';
				}
				echo '</table>';

			}
	}else{
			echo '<form name="form" method="post" action="admin-panel.php?mod=sameuser">';
			echo 'Nicki daxil edin:<br/>';
			echo '<input type="text" name="nickname" value=""><br />'; 
			echo '<input type="submit" name="submit" value="Təsdiqlə" /><br/>';
         } 
	
	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
break;

case 'profile';
echo '<div class="mnav"><a href="main.php">'.$title.'</a> » <a href="admin-panel.php">Admin</a> » Profili deyiş</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo 'Sizin bura girişinize icaze yoxdur<br/>';
		break;
	}
	 
	 
         if($_POST and $_POST["submit"]){
			 
            $nickname = htmlspecialchars(trim($_POST["nickname"])); 
			$user = mysql_fetch_assoc(mysql_query("SELECT `user_status`,`id`,`nickname`,`created_at`,`about`,`password`,`md5_pass`,`unseen`,`invisible`,`sex` FROM `aloaz_db`.`user` WHERE `nickname`='".$nickname."' LIMIT 1"));
			if($user){
				if(isset($_GET["edit"]) and intval($_GET["edit"])==1){
					$error = ''; 
					$log_text = 'Profil deyisdi: ';
					$id_change = false;
					$_id = intval($_POST["user_id"]);
					
					if($_id<1002){
						$error = 'ID 1002-den kiçik ola bilmez';						
					}elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `id`='".$_id."'"))>0 and $user["id"]!=$_id){
						$error = $_id." - bu id artıq mövcuddur";
					}
					
					if($_id!=$user["id"]){
						$id_change = true;
						$log_text .= 'Id:'.$_id.",";
					}					
					
					$_nickname = htmlspecialchars($_POST["new_nickname"]);
					if(strlen($_nickname)<4){
						$error = 'nik 4 simvoldan az ola bilmez';						
					}elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `nickname`='".$_nickname."'"))>0 and $user["nickname"]!=$_nickname){
						$error = $_nickname." - bu nik artıq mövcuddur";
					}
					
					
					$_sex = intval($_POST["sex"]);
					if($_sex!=0 and $_sex!=1){
						$error = 'Cins düzgün qeyd olunmayıb';
						$log_text.='Cins: "'.$_sex.'",';
					} 
					
					if($_nickname!=$user["nickname"]){
						$log_text .= 'Nickname:'.$_nickname.",";
					}
					
					$_created_at = htmlspecialchars($_POST["created_at"]);
					if(intval($user["created_at"])>0 and $_created_at==""){
						$error = 'Qeydiyyat tarixi boş buraxmaq olmaz';
					}
					$_created_at = strtotime($_created_at);
					
					if($_created_at!=$user["created_at"]){
						$log_text .= 'Qeyd tarixi:'.$_created_at.",";
					}
					
					$_password = htmlspecialchars($_POST["password"]);
					$_repassword = htmlspecialchars($_POST["repassword"]);
					if(($_password!="" or $_repassword!="") and $_password!=$_repassword){
						$error = 'Şifrə və təkrar şifrə eyni olmalıdır';
					}
					$md5_pass = md5($_password);  		
					
					
					if(trim($_password=="") and ($_repassword=="")){
						$_password = $user["password"];
						$md5_pass = $user["md5_pass"];
					}
					
					if(trim($_password=="") and ($md5_pass=="")){
						$error = 'Şifrə boş ola bilmez';
					}
					
					if($_password!=$user["password"]){
						$log_text .= 'Şifre:'.$_password.",";
					}
					
					$_about  = htmlspecialchars($_POST["about"]);
					
					if($_about!=$user["about"]){
						$log_text .= 'About:'.$_about.",";
					}
					
					if(isset($_POST["invisible"])){
						$invisible_post = 1;
					}else{
						$invisible_post =  0;
					} 
					
					$log_text .= 'Invisible:'.$invisible_post.",";

					if(isset($_POST["unseen"])){
						$unseen_post = 1;
					}else{
						$unseen_post =  0;
					} 
					$log_text .= 'Unseen:'.$unseen_post.",";

					if($error!=""){
						echo '<span style="color:red">'.$error.'</span>';
					}else{ 
						$update = mysql_query('UPDATE `aloaz_db`.`user` SET `nickname`="'.$_nickname.'",`id`="'.$_id.'",`created_at`="'.$_created_at.'",`updated_at`="'.time().'",`password`="'.$_password.'",`md5_pass`="'.$md5_pass.'",`about`="'.$_about.'",`invisible`="'.$invisible_post.'",`unseen`="'.$unseen_post.'",`sex`="'.$_sex.'" WHERE id="'.$user["id"].'" LIMIT 1;');
						if($update){
							echo "<b>İstifadeçinin profili deyişdi</b><br />"; 
							$log_insert = mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`to_id`='".$user["id"]."',`nickname`='".$login."',`text`='".$log_text."',`date`='".date("Y-m-d H:i:s")."';");
							if($id_change){
								
								//blocks
								$query = mysql_query("UPDATE `aloaz_db`.`blocks` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 1000;");
								$query = mysql_query("UPDATE `aloaz_db`.`blocks` SET `from_id`='".$_id."' WHERE `from_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 1000;");
								
								//chat_ozunutanit
								$query = mysql_query("UPDATE `aloaz_db`.`chat_ozunutanit` SET `uid`='".$_id."' WHERE `uid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//chat_rooms
								$query = mysql_query("UPDATE `aloaz_db`.`chat_rooms` SET `adminid`='".$_id."' WHERE `adminid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//coin_logs
								$query = mysql_query("UPDATE `aloaz_db`.`coin_logs` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								$query = mysql_query("UPDATE `aloaz_db`.`coin_logs` SET `user_id2`='".$_id."' WHERE `user_id2`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//conversation
								$query = mysql_query("UPDATE `aloaz_db`.`conversation` SET `user_one`='".$_id."' WHERE `user_one`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								$query = mysql_query("UPDATE `aloaz_db`.`conversation` SET `user_two`='".$_id."' WHERE `user_two`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								$query = mysql_query("UPDATE `aloaz_db`.`conversation` SET `deleted_by`='".$_id."' WHERE `deleted_by`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//conversation_reply
								$query = mysql_query("UPDATE `aloaz_db`.`conversation_reply` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								$query = mysql_query("UPDATE `aloaz_db`.`conversation_reply` SET `user_id_to`='".$_id."' WHERE `user_id_to`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								$query = mysql_query("UPDATE `aloaz_db`.`conversation_reply` SET `deleted_by`='".$_id."' WHERE `deleted_by`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//news_comment
								$query = mysql_query("UPDATE `aloaz_db`.`news_comment` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");  
								
								//notification
								$query = mysql_query("UPDATE `aloaz_db`.`notification` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								$query = mysql_query("UPDATE `aloaz_db`.`notification` SET `user_id_from`='".$_id."' WHERE `user_id_from`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								
								//operation_logs
								$query = mysql_query("UPDATE `aloaz_db`.`operation_logs` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								$query = mysql_query("UPDATE `aloaz_db`.`operation_logs` SET `to_id`='".$_id."' WHERE `to_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								
								//rating_logs
								$query = mysql_query("UPDATE `aloaz_db`.`rating_logs` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								$query = mysql_query("UPDATE `aloaz_db`.`rating_logs` SET `user_id2`='".$_id."' WHERE `user_id2`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								
								//room
								$query = mysql_query("UPDATE `aloaz_db`.`room` SET `uid`='".$_id."' WHERE `uid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");  
								
								//room_msgs
								$query = mysql_query("UPDATE `aloaz_db`.`room_msgs` SET `uid`='".$_id."' WHERE `uid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 
								$query = mysql_query("UPDATE `aloaz_db`.`room_msgs` SET `to`='".$_id."' WHERE `to`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 

								//room_subs
								$query = mysql_query("UPDATE `aloaz_db`.`room_subs` SET `uid`='".$_id."' WHERE `uid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");  

								//share
								$query = mysql_query("UPDATE `aloaz_db`.`share` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");  	

								//share_comment
								$query = mysql_query("UPDATE `aloaz_db`.`share_comment` SET `uid`='".$_id."' WHERE `uid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 	

								//share_like
								$query = mysql_query("UPDATE `aloaz_db`.`share_comment` SET `uid`='".$_id."' WHERE `uid`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000"); 	
								
								//smiles
								$query = mysql_query("UPDATE `aloaz_db`.`smiles` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	

								//transactions
								$query = mysql_query("UPDATE `aloaz_db`.`transactions` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	

								//user_activity
								$query = mysql_query("UPDATE `aloaz_db`.`user_activity` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");

								//user_block
								$query = mysql_query("UPDATE `aloaz_db`.`user_block` SET `block_from`='".$_id."' WHERE `block_from`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								$query = mysql_query("UPDATE `aloaz_db`.`user_block` SET `block_to`='".$_id."' WHERE `block_to`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//user_friend
								$query = mysql_query("UPDATE `aloaz_db`.`user_friend` SET `user_1`='".$_id."' WHERE `user_1`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								$query = mysql_query("UPDATE `aloaz_db`.`user_friend` SET `user_2`='".$_id."' WHERE `user_2`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");

								//user_gift
								$query = mysql_query("UPDATE `aloaz_db`.`user_gift` SET `gift_from`='".$_id."' WHERE `gift_from`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								$query = mysql_query("UPDATE `aloaz_db`.`user_gift` SET `gift_to`='".$_id."' WHERE `gift_to`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								
								//user_image
								$query = mysql_query("UPDATE `aloaz_db`.`user_image` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");

								//user_image_resized
								$query = mysql_query("UPDATE `aloaz_db`.`user_image_resized` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");		

								//user_image_send
								$query = mysql_query("UPDATE `aloaz_db`.`user_image_send` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");

								//user_image_thumb
								$query = mysql_query("UPDATE `aloaz_db`.`user_image_thumb` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");										
								 
								//user_like
								$query = mysql_query("UPDATE `aloaz_db`.`user_like` SET `like_from`='".$_id."' WHERE `like_from`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								$query = mysql_query("UPDATE `aloaz_db`.`user_like` SET `like_to`='".$_id."' WHERE `like_to`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//user_status
								$query = mysql_query("UPDATE `aloaz_db`.`user_status` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								
								//user_visit
								$query = mysql_query("UPDATE `aloaz_db`.`user_visit` SET `visit_from`='".$_id."' WHERE `visit_from`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								$query = mysql_query("UPDATE `aloaz_db`.`user_visit` SET `visit_to`='".$_id."' WHERE `visit_to`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");
								
								//viktorina
								$query = mysql_query("UPDATE `aloaz_db`.`viktorina` SET `winner_id`='".$_id."' WHERE `winner_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
								
								//vip_user
								$query = mysql_query("UPDATE `aloaz_db`.`vip_user` SET `user_id`='".$_id."' WHERE `user_id`='".$user["id"]."' ORDER BY `id` DESC LIMIT 10000");	
							}
						}else{
							echo "Xeta baş verdi<br />";
						}
					}			
					 
				}else{
					$man_selected = $woman_selected = '';
					if($user["sex"]==0){
						$man_selected = 'selected="selected"';
					}elseif($user["sex"]==1){
						$woman_selected = 'selected="selected"';
					}
					
					$invisible_check = $unseen_check = '';
					if($user["invisible"]==1) $invisible_check = 'checked="checked"';
					if($user["unseen"]==1) $unseen_check = 'checked="checked"';
					$created_at = date("d-m-Y H:i",$user["created_at"]);
					echo $nickname."  nikli istifadeçinin profilini deyiş";
					echo '<form name="form" method="post" action="admin-panel.php?mod=profile&amp;edit=1">';
					echo '<br/><br/>';
					echo '<label>ID(id 1002-den kiçik ola bilmez)</label><br /><input type="text" name="user_id" value="'.$user["id"].'"><br />'; 
					echo '<label>Nik</label><br /><input type="text" name="new_nickname" value="'.$user["nickname"].'">
					<input type="hidden" name="nickname" value="'.$user["nickname"].'"><br />'; 
					echo '<label>Qeydiyyat tarixi</label><br /><input type="text" name="created_at" value="'.$created_at.'"><br />'; 
					echo '<label>Şifre</label><br /><input type="text" name="password" value=""><br />'; 
					echo '<label>Şifre tekrarı</label><br /><input type="text" name="repassword" value=""><br />'; 
					echo '<label>Cins</label><br /><select name="sex">';
					echo '<option value="0" '.$man_selected.'>Kişi</option>';
					echo '<option value="1" '.$woman_selected.'>Qadın</option>';
					echo '</select><br /><br />';
					echo '<label>Görünməzlik</label> <input name="invisible" type="checkbox" '.$invisible_check.'/><br />';
					echo '<label>Gizli qonaq</label> <input name="unseen" type="checkbox" '.$unseen_check.'/><br /><br />';
					echo '<label>Haqqında</label><br /><textarea name="about">'.$user["about"].'</textarea><br />'; 
					echo '<br /><br /><input type="submit" name="submit" value="Dəyiş" /><br/>';
				}
					
								
		
            }else{
				echo $nickname."  nikli istifadeçi tapılmadı";
			}

        }else{
				echo '<form name="form" method="post" action="admin-panel.php?mod=profile">';
				echo 'Nicki daxil edin:<br/>';
				echo '<input type="text" name="nickname" value=""><br />'; 
				echo '<input type="submit" name="submit" value="Təsdiqlə" /><br/>';
        } 
		 

		

	echo "<br />"; 

	echo '<br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';


break;

case 'register';
echo '<div class="mnav"><a href="main.php">'.$title.'</a>  » <a href="admin-panel.php">Admin</a>  » Yeni istifadeçi</div>';
echo '<div class="layer">';
	if(intval($admin_status) == 0){
		echo 'Sizin bura girişinize icaze yoxdur<br/>';
		break;
	}
	 
	 
    if($_POST and $_POST["submit"] and isTokenValid()){	 
		 
		$error = ''; 
		
		$_created_at = htmlspecialchars($_POST["created_at"]);
		if($_created_at=="") $_created_at = time(); else $_created_at = strtotime($_created_at);
		$_about  = htmlspecialchars($_POST["about"]);
		$ins_sql = "`created_at`='".$_created_at."',`about`='".$_about."'";
		
		$_id = intval($_POST["user_id"]);		
		if($_id<1002 and $_id>0){
			$error .= 'ID 1002-den kiçik ola bilmez<br />';						
		}elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `id`='".$_id."'"))>0 and $_id>0){
			$error .= $_id." - bu id artıq mövcuddur<br />";
		}elseif($_id!=0){
			$ins_sql .= ",`id`='".$_id."'";
		}			
			
		$_nickname = htmlspecialchars($_POST["new_nickname"]);
		if(strlen($_nickname)<4){
			$error .= 'nik 4 simvoldan az ola bilmez<br />';						
		}elseif(mysql_num_rows(mysql_query("SELECT `id` FROM `aloaz_db`.`user` WHERE `nickname`='".$_nickname."'"))>0 and $user["nickname"]!=$_nickname){
			$error .= $_nickname." - bu nik artıq mövcuddur<br />";
		}else{
			$ins_sql .= ",`nickname`='".$_nickname."'";
		}		
			
		$_password = htmlspecialchars($_POST["password"]);
		$_repassword = htmlspecialchars($_POST["repassword"]); 
		if(trim($_password)=="" or trim($_repassword)=="" or $_password!=$_repassword){ 
			$error .= 'Şifreni boş buraxmaq olmaz.Şifrə və təkrar şifrə eyni olmalıdır<br />';
		}else{
			$md5_pass = md5($_password);  
			$ins_sql.=",`password`='".$_password."',`md5_pass`='".$md5_pass."'";
		}		

		$_sex = intval($_POST["sex"]);
		if($_sex!=0 and $_sex!=1){
			$error .= 'Cins düzgün qeyd olunmayıb';
		}else{
			$ins_sql.=',`sex`="'.$_sex.'"';
		}
		
		$_birthday = htmlspecialchars($_POST["birthday"]);
		if($_birthday!=""){
			$birthday = strtotime($_birthday);
			$age = date("Y") - date("Y",$birthday);
			$ins_sql.=',`birthday`="'.$_birthday.'"';
			$ins_sql.=',`age`="'.$age.'"';
		}  
		if($error!=""){
			echo '<span style="color:red">'.$error.'</span>';
		}else{ 
			$insert = mysql_query('INSERT INTO `aloaz_db`.`user` SET '.$ins_sql);
			if($insert){
				echo "<b>İstifadeçi qeyd oldu</b><br />"; 

			}else{
				echo "Xeta baş verdi<br />";
			}
		}         

	}else{
		$created_at = date("d-m-Y H:i");
		echo "<b>Yeni istifadeçi yarat</b>";
		echo '<form name="form" method="post" action="admin-panel.php?mod=register">';
		echo '<br/>';
		echo '<label>ID(id 1002-den kiçik ola bilmez)</label><br /><input type="text" name="user_id" value=""><br />'; 
		echo '<label>Nik</label><br /><input type="text" name="new_nickname" value=""><br />'; 
		echo '<label>Qeydiyyat tarixi</label><br /><input type="text" name="created_at" value="'.$created_at.'"><br />'; 
		echo '<label>Şifre</label><br /><input type="text" name="password" value=""><br />'; 
		echo '<label>Şifre tekrarı</label><br /><input type="text" name="repassword" value=""><br />'; 
		echo '<label>Haqqında</label><br /><textarea name="about"></textarea><br />'; 
		echo '<label>Cins</label><br /><select name="sex"><option value="0">Kişi</option><option value="1">Qadın</option></select><br />';
		echo '<label>Doğum tarixi</label><br /><input type="text" name="birthday" value="1990-01-01">';
		echo '<input type="hidden" name="csrf_token" value="'.makeToken().'">';
		echo '<br /><br /><input type="submit" name="submit" value="Qeydiyyat" /><br/>';
	} 		 
	echo '<br /><br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
 

break;
case 'team';
echo '<div class="mnav"><a href="main.php">'.$title.'</a>  » <a href="admin-panel.php">Admin</a>  » Vezifeli istifadeçiler</div>';
echo '<div class="layer">';
// DELETE A Status
		$_del = intval($_GET['del']);
		$_uid = intval($_GET['uid']);

		if($_del == 1){
			echo '<div class="notif" align="center">';
			echo 'Silmek istediyinize eminsiniz?<br/>';
			echo '<a href="?mod=team">'.$__lng['xeyr'].'</a> / ';
			echo '<a href="?mod=team&amp;uid='.$_uid.'&amp;del=2">'.$__lng['beli'].'</a><br/>';
			echo '</div>';
		}
		if($_del == 2){
				$sql = mysql_query("select id,user_status from `aloaz_db`.`user` where id='".$_uid."' and user_status>0 limit 1");
				$row = mysql_fetch_assoc($sql);
				if($row){
					//$id = $row["id"];
					$user_status = $row['user_status'];
					$status_query = mysql_query("SELECT id FROM `aloaz_db`.`user_status` WHERE `user_id`='".$_uid."' and `ended`=0");
					if(mysql_num_rows($status_query)>0){
						$status_row = mysql_fetch_assoc($status_query);
						mysql_query("UPDATE `aloaz_db`.`user_status` SET `ended`=1 WHERE `id`='".$status_row["id"]."'");
						mysql_query("UPDATE `aloaz_db`.`user` SET `user_status`=0,`invisible`=0,`unseen`=0 WHERE id='".$_uid."' LIMIT 1");
						$log_text = $_uid.' idli istifadecinin '.$user_status.' statusu alindi';
						mysql_query("INSERT INTO `aloaz_db`.`operation_logs` SET `user_id`='".$id."',`to_id`='".$_uid."',`nickname`='".$login."',`text`='".$log_text."',`date`='".date("Y-m-d H:i:s")."'");
						echo "Status silindi<br /><br />";
					}else{
						echo 'Bu istifadecinin vezifesi yoxdur<br /><br />';
					}
				}else{
					echo 'Bu istifadeçi tapılmadı yada vezifesi yoxdur<br /><br />';
					break;
				}
					
				
			
		}

	$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 10");
echo '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Admin</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">'.$vip_status.'</span><br/>';
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';


$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 3");
echo '<img src="img/crown-gold.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Boss</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	$user_status_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user_status` WHERE `user_id`='".$vip_id."' and `status`=3 and `ended`=0 ORDER BY `id` DESC"));
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">Bitir: '.date("d-m-Y H:i",$user_status_row["end_time"]).'</span><br/>';
		echo "<a href='admin-panel.php?mod=team&amp;uid=".$vip_id."&amp;del=1'>Statusu al</a>";
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 2");
echo '<img src="img/crown-silver.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Moder</b> ('.mysql_num_rows($vip_query).'): <br/>';
echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	$user_status_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user_status` WHERE `user_id`='".$vip_id."' and `status`=2 and `ended`=0 ORDER BY `id` DESC"));
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">Bitir: '.date("d-m-Y H:i",$user_status_row["end_time"]).'</span><br/>';
		echo "<a href='admin-panel.php?mod=team&amp;uid=".$vip_id."&amp;del=1'>Statusu al</a>";
		echo '</td></tr>';
	}
echo '</table>';
echo '<br/>';

$vip_query = mysql_query("SELECT `id`, `nickname`, `sex`, `profile_photo`, `last_post` FROM `aloaz_db`.`user` WHERE `user_status` = 1");
echo '<img src="img/crown-bronze.png" style="width:18px;float:left;padding-right:5px;" alt="." /> <b>Vip</b> ('.mysql_num_rows($vip_query).'): <br/>';

echo '<table cellpadding="2">';
while($vip_row = mysql_fetch_array($vip_query)){
	$vip_id = $vip_row['id'];
	$vip_login = $vip_row['nickname'];
	$vip_sex = $vip_row['sex'];
	$vip_profile_photo = $vip_row['profile_photo'];
	$vip_status = $vip_row['last_post'];
	
	$user_status_row = mysql_fetch_assoc(mysql_query("SELECT * FROM `aloaz_db`.`user_status` WHERE `user_id`='".$vip_id."' and `status`=1 and `ended`=0 ORDER BY `id` DESC"));
	
	if(strlen($vip_status) > 50) $vip_status = mb_substr($vip_status,0,50,"utf-8");
	
	if($vip_sex == 0) $sex_icon = 'man'; else $sex_icon = 'woman';
	if(empty($vip_profile_photo)) $img_file = '../img/'.$sex_icon.'.gif';
	else $img_file = 'https://m.alo.az/udata'.$vip_profile_photo;

		echo '<tr><td><a href="profile.php?uid='.$vip_id.'&amp;back=online"><img src="'.$img_file.'" alt="man" style="border: 1px solid #d7d7d7;width:40px;" /></a></td>
		<td width="300px"><a href="profile.php?uid='.$vip_id.'">'.$vip_login.'</a><br/>'; 
		echo '<span style="font-size:11px">Bitir: '.date("d-m-Y H:i",$user_status_row["end_time"]).'</span><br/>';
		echo "<a href='admin-panel.php?mod=team&amp;uid=".$vip_id."&amp;del=1'>Statusu al</a>";
		echo '</td></tr>';
	}
echo '</table>';
echo '</div>';
echo '<br /><br/><a href="javascript:history.back(1)">« '.$__lng['geri'].'</a>';
break;

}
if(!empty($mod)){
	//echo '<br/><a href="pointserv.php">'.$__lng['bal xidmetleri'].'</a><br/>';
}
echo '</div>';
include 'inc/footer.php';
?>
