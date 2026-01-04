<?php
/*****************************\
# API: Sim.az				  |
# Author: Yusubov Pərviz	  |
******************************/

class simaz
{
	var $data = array();
	private $row;
	private $mysql;

	function __construct(){
		$this->url = 'http://up.sim.az';
		$this->request_uri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$this->post = (array) $_POST;
		$this->system = $this->post['_data'];
		$this->time = time();
	}
	
	public function connect() {
		$this->params(DOCUMENT_ROOT.'simaz/simaz_temps.php');
		$this->row = array(
			'partner_id' => $this->params['simaz_id'],
			'password'	 => $this->params['simaz_key'],
		);
	}
	
	function mysql_db($row) {
		$this->mysql = $row;
	}
	
	function mysql_connect() {
		if(!@mysql_connect ($this->mysql['localhost'], $this->mysql['db_user'], $this->mysql['db_pass'])) {
			return 'MySql - Login or Password error';
		}
		if(!@mysql_select_db($this->mysql['db_name'])){
			return 'MySql - db_name error';
		}
	}
	
    public function time( $str )
    {
        if ( $str < 60 && $str >= 0 ) {
            return $str . ' saniyyə';
        }
		else if ( $str < 3600 && $str >= 60 ) {
            $clock = round($str / 60);
			return $clock . ' dəqiqə';
        }
		else if ( $str < 86400 && $str >= 3600 ) {
			
            $clock = $str / 3600;
            list( $hour , $minute ) = split( '\.' , $clock );

			
			$clock = $hour . ' saat';
			
			if($minute) {
				$minute = round($minute * 6);
				if($minute > 0) {
					$clock .= ', '.$minute . ' dəqiqə';
				}
			}
			return $clock;
        }
		elseif ( $str >= 86400 ) {
			
            $clocktm = $str / 86400;
            list( $days ,  ) = split( '\.' , $clocktm );
			$clock = $days . ' gün';			
			
			$hour = round((( $clocktm - $days ) * 86400) / 3600);
			if($hour > 0) {
				$clock .= ', '.$hour. ' saat';
			}
			return $clock;
        }
    }
	
	function get($key, $array, $backuri = null) {
		
		if( $backuri && !preg_match('/^https?:\/\//', $backuri)) {
			$backuri = $this->home.'/'.$backuri;
		}
		$array['_'] = array(
			'id' => $this->row['partner_id'],
			'back' => ($backuri ? $backuri : $this->request_uri),
		);
		ob_clean();
		header('Location: '.$this->url.'/'.$key.'/'.base64_encode(urlencode(http_build_query($array, '', '&'))));
		die;
	}
	
	function result_body() {
		
		if( !$this->system['partner_id'] || !$this->system['password']) {
			return;
		}
		$this->connect();
		
		if($this->system['partner_id'] == $this->row['partner_id']) {
			if($this->system['password'] == $this->row['password']) {
				return $this->request_exists($this->system['ok']);
			}
			else {
				return array('status' => 'error', 'text' => 'Partner Password - Düz deyil.');
			}
		}
		else {
			return array('status' => 'error', 'text' => 'Partner ID - Düz deyil.');
		}
	}
	
	function request_exists($update = null) {
		if( method_exists($this, $this->system['case'])) {
			return $this->{$this->system['case']}($update);
		}
		return array('function' => 'unknown');
	}
	
	
	function domino($update) {
		
		$userid = intval($this->post['userid']); // user id
		$amount = intval($this->system['ok'] ? $this->system['amount'] : $this->post['amount']);
		
		
		if($errorSql = $this->mysql_connect()) {
			return array('status' => 'error', 'text' => $errorSql);
		}
	
		$point_kon = 2000 * $amount;
		if(!$update) {
			$select = mysql_query ("Select `user` from `users` where `id` = '".$userid."'");
			if(mysql_affected_rows() == 0) {
				return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> İstifadəçi adı tapılmadı.');
			}
			$row = mysql_fetch_assoc($select);
			return array('status' => 'data', 'amount' => $amount, 'data' => array('Xidmət' => 'Domino Point al', 'İstifadəçi' => $row['user'], 'Miqdar' => $point_kon.' - point'));
		}
		
		if(mysql_query ("UPDATE users SET `point`=`point` + ".$point_kon.", `azn_show`=`azn_show`+".$amount." where id='".$userid."' LIMIT 1;")){
			$select = mysql_query ("Select `user` from `users` where `id` = '".$userid."'");
			$row = mysql_fetch_assoc($select);
			
			$message = "Hormetli <b>{$row['user']}</b> hesabına {$amount} azn, Sim kontur ilə {$point_kon} point yükləndi. (Damino oyunu üçün)<br/>";
			mysql_query("insert into zapiski values(0,'Sim Kontur','0','".$message."','".$u_user."','".$userid."','".time()."','0','Sim Kontur','".date("d-M-Y [H:i]")."','1','1');");
			return array('status' => 'success', 'text' => 'Hesabınıza <b>'.$point_kon.'</b> point və <b>'.$amount.'</b> azn yüklendi.');
		}
		else{
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Hesabınıza bal yükləmək mümkün olmadı. Adminlə əlaqə saxlayın.');
		}
	}
	
	
	function buy_reg($update) {
		
		if($errorSql = $this->mysql_connect()) {
			return array('status' => 'error', 'text' => $errorSql);
		}
		if(is_numeric($this->post['pack']) && preg_match( '@^(\d\d\d\d)-(\d\d)-(\d\d)$@' , $this->post['date'] , $match ) == false) {
			return array('status'=> 'error', 'text' => 'Məlumat düzgün seçilməyib.');
		}
		
		$check_q = mysql_query("SELECT `id` FROM `buy_reg` WHERE `pack_id`='".$this->post['pack']."' AND `date`='".$this->post['date']."'");
		if(mysql_num_rows($check_q) > 0){
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Bu tarixdə qeydiyyat artıq alınıb. Başqa tarix seçin.');
		}
		
		if(strtotime($this->post['date']) < strtotime(date('Y-m-d')) or strlen($this->post['date']) != 10){
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Keçmiş vaxtı seçmək olmaz.');
		}
		
        $hour_query = mysql_query("SELECT `hour`,`price` FROM `buy_reg_cost` WHERE `id`='".$this->post['pack']."' and `price` != 'x'");
        if(!$hour_array = mysql_fetch_array($hour_query)) {
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Paket düzgün seçilməyib.');
		}
        $hour = $hour_array['hour'];
        $price = $hour_array['price'];

		if(strtotime($this->post['date'])==strtotime(date('Y-m-d')) && $hour <= date("H")){
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Keçmiş vaxtı seçmək olmaz.');
		}
		
		$week_plus = strtotime(date('Y-m-d'))+86400*7;
		if(strtotime($this->post['date']) >= $week_plus){
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> 1 həftədən çox vaxt seçmək olmaz.');
		}
		
		if (!preg_match("/^(http|https):/", $this->post['site_url'])) {
			$this->post['site_url'] = 'http://'.$this->post['site_url'];
		}
		
		if($update) {
			if($price > $this->system['amount']) {
				return array('status' => 'error', 'text' => 'Xidmət dəyəri '.$price.' manatdır, siz isə '.$this->system['amount'].' manat yüklədiniz.');
			}
			mysql_query("INSERT INTO `buy_reg` SET `pack_id` = '".$this->post['pack']."', `date` = '".$this->post['date']."', `site_url` = '".$this->post['site_url']."', `amount` = '".$this->system['amount']."', `active` = '1';");
			$parse = parse_url($this->post['site_url']);
			$select = @mysql_query ("Select * from advertiser_rating where site='". $parse['host']."'");
			if (mysql_affected_rows() == 0) {
				mysql_query("INSERT INTO `advertiser_rating` SET `site` = '{$parse['host']}', `cost` = '{$this->system['amount']}';");
			}else{
				mysql_query("UPDATE `advertiser_rating` SET `cost`=`cost` + '".$this->system['amount']."' WHERE `site` = '". $parse['host']."'");
			}
			return array('status' => 'success', 'text' => 'Yönlendirmə aktiv olundu.');
		}
		return array('status' => 'data', 'amount' => $price, 'data' => array( 'Xidmət' => 'Qeydiyyatın alınması', 'Müddət' => $hour.':00 - '.$hour.':59', 'Tarix' => $this->post['date']));
	}
	
	public function bal($update) {
		
		$userid = intval($this->post['userid']);
		$amount = intval($this->system['ok'] ? $this->system['amount'] : $this->post['amount']);
		$pointList = $this->array_numeric($this->params['bal_tariffs']);
		$point = $pointList[$amount];

		if(!$point) {
			$point = 0;
			ksort($pointList);
			foreach( $pointList as $k => $v ) {
				if($k <= $amount) {
					$point = ($v / $k) * $amount;
				}
			}
		}
		
		if($errorSql = $this->mysql_connect()) {
			return array('status' => 'error', 'text' => $errorSql);
		}
		
		$select = mysql_query ("Select `nickname` from `aloaz_db`.`user` where `id` = '".$userid."'");
		if(mysql_affected_rows() == 0) {
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> İstifadəçi adı tapılmadı.');
		}
		$row = mysql_fetch_assoc($select);
		
		if(!$update) {
			return array('status' => 'data', 'amount' => $amount, 'data' => array('Xidmət' => 'Bal Yükləmə', 'İstifadəçi' => $row['nickname'], 'Miqdar' => $point.' - bal'));
		}
		
		if(mysql_query ("UPDATE `aloaz_db`.`user` SET `coins`=`coins` + ".$point.", `all_coins`=`all_coins` + ".$point." where id='".$userid."' LIMIT 1;")){
			// $metn_nn = "Hörmetli <b>{$row['nickname']}</b> Azercell Şifrə ilə <b>$amount</b> azn kontur tesdiq edildi ve hesabiniza <b>$point</b> bal ve <b>$amount</b> azn yüklendi! Tebrikler.<br/>";
			// mysql_query("insert into zapiski values(0,'Sim Kontur','0','".$metn_nn."','".$u_user."','".$userid."','".time()."','0','Sim Kontur','".date("d-M-Y [H:i]")."','1','1');");
			return array('status' => 'success', 'text' => 'Hesabınıza <b>'.$point.'</b> bal yüklendi.');
		}
		else{
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Hesabınıza bal yükləmək mümkün olmadı. Adminlə əlaqə saxlayın.');
		}
	}

	public function reklam($update) {
		
		$case = $this->post['place'];
		$amount = intval($this->system['ok'] ? $this->system['amount'] : $this->post['amount']);

		$secunds = 0;
		if( $this->params[$this->replace[$case]['key']] == 'true' ) {
			$list = $this->array_numeric($this->params[$this->replace[$case]['key'].'_price']);

			if( sizeof( $list ) > 0 ) {
				
				$secunds = 0;
				ksort($list);
				foreach( $list as $price => $time ) {
					
					if( strstr($time, '.') ) {
						$split = preg_split('/\./', $time, -1, PREG_SPLIT_NO_EMPTY);
						$time = (( $split[0] * 60 ) + (strlen($split[1]) == '1' ? $split[1] * 10 : substr($split[1], 0, 2))) / 60;
					}
					if( !$secunds ) {
						$secunds = ($time / $price) * $amount * 3600;
					}
					if($price <= $amount) {
						$secunds = ($time / $price) * $amount * 3600;
					}
				}
			}
		}

		if(!$secunds) {
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Belə xidmət mövcut deyil.');
		}

		if(!$update) {
			return array('status' => 'data', 'amount' => $amount, 'data' => array('Xidmət' => 'Reklam Yerləşir', 'Reklam Yeri' => $this->replace[$case]['value'], 'Müddət' => $this->time($secunds), 'Sizin Reklam' => '<a target="_blank" href="'.$this->post['url'].'">'.$this->post['title'].'</a>'));
		}

		if($errorSql = $this->mysql_connect()) {
			return array('status' => 'error', 'text' => $errorSql);
		}
		
		$title = $this->strto($this->post['title']);
		$url = $this->post['url'];
		if (!preg_match("/^(http|https):/", $url)) {
			$url = 'http://'.$url;
		}
		$mysql = mysql_query("Insert into `advertisers` Set `title`='".$title."', `url`='".$url."', `action`='2', `code`='{$this->system['code']}', `index`='".$case."', `time`='".(time() + $secunds)."'");
		
		$data = urlencode(json_encode(array(
			'url' => $url,
			'title' => $title,
		)));
		
		$parse = parse_url($url);
		$select = @mysql_query ("Select * from advertiser_rating where site='". $parse['host']."'");
		if (mysql_affected_rows() == 0) {
			mysql_query("INSERT INTO `advertiser_rating` SET `site` = '{$parse['host']}', `cost` = '{$amount}', `data` = '{$data}';");
		}else{
			mysql_query("UPDATE `advertiser_rating` SET `cost`=`cost` + '".$amount."', `data` = '".$data."' WHERE `site` = '". $parse['host']."'");
		}
		return array('status' => 'success', 'text' => 'Sizin Reklam uğurla yerləşdirildi.');
	}

	
	
	
	public function ban($update) {
		
		$userid = intval($this->post['userid']);
		$act = intval($this->post['act']);

		$price = $this->params['banned_'.$act.'_price'];
		$amount = intval($this->system['ok'] ? $this->system['amount'] : $price);

		if(!$price && $this->params['banned_'.$act] != 'true') {
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Belə xidmət mövcut deyil.');
		}
		else if($price > $amount) {
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> Siz yetərli məbləği seçmədiz.');
		}
		
		if($errorSql = $this->mysql_connect()) {
			return array('status' => 'error', 'text' => $errorSql);
		}
		
		$select = mysql_query ("Select `user`,`banned` from `users` where `id` = '".$userid."'");
		if(mysql_affected_rows() == 0) {
			return array('status' => 'error', 'text' => '<font color="red">Xəta:</font> İstifadəçi adı tapılmadı.');
		}
		$row = mysql_fetch_assoc($select);
		
		if(!$update) {
			return array('status' => 'data', 'amount' => $amount, 'data' => array('Xidmət' => 'Nikin qaytarılması', 'İstifadəçi' => $row['user']));
		}
		if($act == '1') {
			mysql_query ("UPDATE `users` SET `kik`= '0' where id = '".$userid."';");
		}
		else if($act == '2' && $row['banned'] == '1') {
			mysql_query ("UPDATE `users` SET `banned`= '0' where id = '".$userid."';");
		}
		else if($act == '3' && $row['banned'] == '2') {
			mysql_query ("UPDATE `users` SET `banned`= '0' where id = '".$userid."';");
		}
		return array('status' => 'success', 'text' => 'Nikiniz uğurla qaytarıldı.');
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function strto($a,$type=0)
	{
		$preg = ($type==0) ? '/([.?!]+)/' : '/([.?! ]+)/';
		$arr = preg_split($preg, $a, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		$txt = '';
		foreach($arr AS $v) {
			if(substr($v,0,1)==' ') {
				$txt .= ' '.ucfirst(strtolower(substr($v,1)));
			}
			else {
				$txt .= substr($v,0,1).substr(strtolower($v),1);
			}
		}
		return $txt;
	}

	function isUrl( $str ) {  
		return filter_var( $str, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) !== false;  
	}

	function params( $file ) {
		
		if( $this->params ) {
			return $this->params;
		}
		
		$this->file = $file;
		$_error = 'file not fond';
		if(file_exists($this->file)) {
			$_error = 'chmod error';
			if(decoct(fileperms($this->file) & 0777) >= 666 ) {
				$_error = null;
			}
		}
		if( $_error ) {
			die($_error);
		}
		@include( $this->file );
		if( !is_array($params) ) {
			die( ' params - empty' );
		}
		$this->params = $params;
		return $this->params;
	}
	
	function cuci( $cuci ) {
		$key = substr($cuci, -1);
		$cicu=array('1'=>''.$cuci.'-ci','2'=>''.$cuci.'-ci','3'=>''.$cuci.'-c&#252;','4'=>''.$cuci.'-c&#252;','5'=>''.$cuci.'-ci','6'=>''.$cuci.'-c&#305;','7'=>''.$cuci.'-ci','8'=>''.$cuci.'-ci','9'=>''.$cuci.'-cu','0'=>''.$cuci.'-cu','11'=>'Noyabr','12'=>'Dekabr');
		$result = $cicu[$key];
		return $result;
	}
	
	
	function request( ) {
		$data = array();
		if( sizeof($_POST) > 0 ) {
			
			if( sizeof( $HTTP_POST_VARS ) > 0 ) {
				foreach( $HTTP_POST_VARS as $key => $val ) {
					$data[$key] = $val;
				}
			} else {
				foreach( $_POST as $key => $val ) {
					$data[$key] = ( isset($_REQUEST[$key]) ? $_REQUEST[$key] : $val);
				}
			}
		}
		return $data;
	}
	
	function message( $str , $array = array( ) )
	{
		$str = preg_replace( "/(\s){1,}/" , '$1' , $str );
		$str = preg_replace( '{( ?.)\1{4,}}' , '$1$1$1$1' , $str );
		$message = htmlspecialchars( html_entity_decode( $str , ENT_QUOTES ) );
		$replaces = array (
			"\&quot;" => "&quot;" ,
			"\'"      => "&apos;" ,
			"\\\\"    => "&#92;" ,
			"<"       => "&lt;" ,
			">"       => "&gt;" ,
			"\n"      => "<br/>" ,
		);
		$replaces = array_merge($replaces, $array);
		foreach ( $replaces as $rkey => $rvalue ) {

			if( $rkey && $rvalue ) {
				$message = str_replace( htmlspecialchars(html_entity_decode($rkey)) , $rvalue , $message );
			}
		}
		return $message;
	}
	
	function checkData( $mydate )
	{
		list($dd , $mm , $yy) = explode( '-' , $mydate );
		if ( is_numeric( $yy ) && is_numeric( $mm ) && is_numeric( $dd ) ) {
			return checkdate( $mm , $dd , $yy );
		}
		return false;
	}
	
	
	function SaveData( $Save, $file = null ) {
		
		$file = ( $file ) ? $file : $this->file;
		
		if( !is_array($Save)) {
			return null;
		}
		foreach( $Save as $key => $val ) {
			if( !isset($this->params[$key]) ) {
				die( $key .' - keys d\'not' );
			}
			$this->params[$key] = $val;
		}

		$probels = '          ';// 10
		$array = array("<?php\n/*\n* Sim.az Content\n* Author: Perviz\n*/\n\n\$params = array();");
		foreach( $this->params as $key => $val ) {
			$array[] = '$params[\''.$key.'\'] '.substr($probels, strlen($key)).' = \''.$val.'\';';
		}
		return file_put_contents($file, implode("\n", $array));
	}
	
	
	function wget( $url, $params = array() ) {
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		
		if(is_array($params) && sizeof($params) > 0) {
			curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query( $params, '', '&' ));
			curl_setopt($ch, CURLOPT_POST, true);
		}
		curl_setopt($ch, CURLOPT_HEADER, false);
		$output = curl_exec($ch); 
		curl_close($ch);
	return $output;
	}

	function parse( $action, $params = array() ) {
		$w = $this->wget($this->url.'/parse/'.$action, array_merge(
			array(
				'id'	 => $this->params['simaz_id'],
				'passwd' => $this->params['simaz_key']
			),
			$params)
		);
		
		if( $w ) {
			parse_str($w, $result);
			if( isset($result['status']) ) {
				return $result;
			}
		}
		return array('status' => 0, 'errorMsg' => 'disconnect');
	}
	
	function array_numeric($str) {
		$exp = explode(',',$str);
		$i=0;
		$array = array();
		while($value = $exp[$i]) {
			list($key, $val) = split('=', $value);
			if(is_numeric($key) and is_numeric($val)) {
				$array[trim($key)] = trim($val);
			}
			$i++;
		}
		ksort($array);
	return $array;
	}

    public function OTime( $time , $split = null , $rev = null)
    {
        $new = $tkick = ( $rev ) ? $this->time - $time : $time - $this->time;
		
        if ( $tkick <= 60 && $tkick >= 0 ) {
            $tkick = strlen( $tkick ) == '1' ? '0' . $tkick : $tkick;
            return $tkick . ' saniyyə';
        } elseif ( $tkick <= 3600 && $tkick >= 60 ) {
            $tkick = $new / 60;
            list( $one , $two ) = split( '\.' , $tkick );
			if ( $split ) {
				return ($one + 1) . ' dəqiqə';
			}
            $one = strlen( $one ) == '1' ? '0' . $one : $one;
            return $one . ' dəqiqə ' . $this->sTime( ('0.' . $two) * 60 + $this->time );
        } elseif ( $tkick < 86400 && $tkick > 3600 ) {
            $tkick = $new / 3600;
            list( $one , $two ) = split( '\.' , $tkick );
            $two = ceil( ('0.' . $two) * 60 );
			if ( $split ) {
				return ($one + 1) . ' saat';
			}
            $two = strlen( $two ) == '1' ? '0' . $two : $two;
            return $one . ' saat ' . $two . ' dəqiqə';
        } elseif ( $tkick < 2592000 && $tkick > 86400 ) {
            $tkick = $new / 86400;
            list( $one , $two ) = split( '\.' , $tkick );
            $two = ceil( ('0.' . $two) * 24 );
			if ( $split ) {
				return ($one + 1) . ' gün';
			}
            return $one . ' gün ' . $two . ' saat';
        } elseif ( $tkick >= 217728000 ) {# il 
            $tkick = $new / 31104000;
            list( $one , $two ) = split( '\.' , $tkick );
            $two = ceil( ('0.' . $two) * 12 ) - 1;
			if ( $split ) {
				return ($one + 1) . ' il';
			}
            return $one . ' il, ' . $two . ' ay';
		} elseif ( $tkick > 2592000 ) { #1 ay
            $tkick = $new / 2592000;
            list( $one , $two ) = split( '\.' , $tkick );
            $two = ceil( ('0.' . $two) * 30 ) - 1;
			if ( $split ) {
				return ($one + 1) . ' ay';
			}
            return $one . ' ay, ' . $two . ' gün';
        }
    }
	
	# minute -
    public function rTime( $time ) {
		return $this->OTime( $time, null, true );
    }
	# minute secund, -
    public function rsTime( $time ) {
		return $this->OTime( $time, true, true );
    }
	
	# minute +
	public function srTime( $time ) {
		return $this->OTime( $time, null, null );
    }
	# minute secund +
    public function sTime( $time ) {
		return $this->OTime( $time, true, null );
    }
	
	function cron_result() {
		if( !sizeof($this->params) ) {
			return;
		}
		
		# Reytingin sifrlanmagi
		if( $this->checkData($this->params['reyting_stop_time']) ) {
			
			if( strtotime($this->params['reyting_stop_time']) < $this->time) {
				$new_date = date('d-m-Y', strtotime($this->params['reyting_stop_time'].' next month'));
				$this->SaveData(array('reyting_stop_time' => $new_date));
				
				$i = '1';
				$message = array();
				$sql = mysql_query("SELECT `id`,`user` FROM `users` WHERE `azn_show` > '0' ORDER BY `azn_show` DESC LIMIT 3;");
				while( $inf = mysql_fetch_assoc($sql) ) {
					
					$gifts = array();
					if( $this->params['gift_'.$i] == 'true' ) {
						$message[$i] = $this->cuci($i)." yerin sahibi <b>{$inf['user']}</b> oldu";

						#rutbe - hediyyesinin avtomatik verilmesi.
						if( $this->params['gift_level_'.$i] != 'false' && is_numeric($this->params['gift_level_'.$i]) ) {
							$levels = @mysql_fetch_assoc(mysql_query("SELECT `name` FROM `levels` WHERE `level` = '{$this->params['gift_level_'.$i]}';"));
							if( $levels['name'] ) {
								$gifts['query'][] = " `level` = '{$this->params['gift_level_'.$i]}', `panel` = '2' ";
								
								$date = 'Ömürlük';
								if( $this->params['gift_level_date_'.$i] != 'false' ) {
									$gifts['query'][] = " `rutbe` = '".(($this->params['gift_level_date_'.$i] * 86400) + $this->time)."' ";
									$date = $this->params['gift_level_date_'.$i].' günlük';
								}
								$gifts['message'][] = "{$date} <b>{$levels['name']} rütbəsi</b>";  // 90 gunluk admin rutbesi 
							}
						}
						
						#bal - hediyyesinin avtomatik verilmesi.
						if( $this->params['gift_bal_'.$i] != 'false' && is_numeric($this->params['gift_bal_'.$i]) ) {
							$gifts['query'][] = " `bal` = `bal` + '{$this->params['gift_bal_'.$i]}' ";
							$gifts['message'][] = "<b>{$this->params['gift_bal_'.$i]}</b> Bal ";  // 100 bal
						}
						
						#post - hediyyesinin avtomatik verilmesi.
						if( $this->params['gift_post_'.$i] != 'false' && is_numeric($this->params['gift_post_'.$i]) ) {
							$gifts['query'][] = " `posts` = `posts` + '{$this->params['gift_post_'.$i]}' ";
							$gifts['message'][] = "<b>{$this->params['gift_post_'.$i]}</b> Post ";  // 100 posts
						}
						
						if( sizeof($gifts) > 0 ) {
							$message[$i] .= " və (".implode(', ', $gifts['message']).") qazandı."; // ve (90 gunluk admin rutbesi, 100 bal, 100 posts) qazandi.
							mysql_query("UPDATE `users` SET ".implode(',' , $gifts['query'])." WHERE `id` = '{$inf['id']}';");
						}
						else {
							unset($message[$i]);
						}
					}
					$i++;
				}
				
				if( sizeof($message) > 0 ) {
					
					# //son 7 gun erzinde aktiv olan istifadeciler netice haqqinda mektub.
					$time = $this->time - ( 7 * 86400 );
					$title = $sender = 'Konturla Bal Yarışını Qalibleri';
					$message = "Hörmətli istifadəçilər \"Sim kontur bal yarışı\"nın məlum turu yekunlaşdı. Qaliblər Haqqında: ".implode(' ' , $message)."<br/>----<br/>Növbəti tura start verildi. Sim konturla bal yükləyin növbəti yarışın qalibi Siz olun. Uğurlar Hərkəsə!";

					$sql = mysql_query("SELECT `id`,`user` FROM `users` WHERE `time` > '{$time}';");
					while( $inf = mysql_fetch_assoc($sql) ) {
						
						mysql_query("INSERT INTO `zapiski` SET".
							" `idtowhom`= '{$inf['id']}',".
							" `towhom` 	= '{$inf['user']}',".
							" `time` 	= '{$this->time}',".
							" `who` 	= '{$sender}',".
							" `topic`	= '{$title}',".
							" `message` = '{$message}';"
						);
					}
				}
				mysql_query("UPDATE `users` SET `azn_show` = 0 WHERE 1;");
			}
		}
		
		

		# Bonus Reytingin sifrlanmagi sayt adminleri ucun
		if( $this->checkData($this->params['client_bonus_stop_time']) ) {
			
			if( strtotime($this->params['client_bonus_stop_time']) < $this->time) {
				$new_date = date('d-m-Y', strtotime($this->params['client_bonus_stop_time'].' next month'));
				$this->SaveData(array('client_bonus_stop_time' => $new_date));
				
				$timeList = array(
					'hour' => 3600,
					'day' => 86400
				);
				
				$ratingIds = array();
				$sql = mysql_query("SELECT `id` FROM `advertiser_rating` WHERE `cost` != '0' ORDER BY `cost` DESC LIMIT 3;");
				while( $inf = mysql_fetch_assoc($sql) ) {
					$ratingIds[] = $inf['id'];
				}
				
				if( !sizeof($ratingIds) ) {
					return;
				}
				
				$i = 1;
				$sql = mysql_query("SELECT * FROM `advertiser_rating` WHERE `id` IN(".implode(', ', $ratingIds).") ORDER BY `cost` DESC LIMIT 3;");
				while( $inf = mysql_fetch_assoc($sql) ) {
					
					if($this->params['client_bonus_'.$i] == 'true' ) {
						
						if( $inf['data'] ) {
							$data = json_decode(urldecode($inf['data']), true);
						}
						else {
							$data = array(
								'url' => 'http://'.$inf['site'],
								'title' => ucfirst($inf['site']).' - Bonus qazandı',
							);
						}
						
						if(sizeof($data) > 0) {
							$timeout = (($this->params['client_date_type_'.$i] == 'day' ? 86400 : 3600) * $this->params['client_date_'.$i]) + $this->time;
							mysql_query("INSERT INTO `advertisers` SET "
								. "`title`	= '{$data['title']}', "
								. "`url`	= '{$data['url']}',"
								. "`action`	= '3',"
								. "`code`	= 'Bonus Reyting',"
								. "`index`	= '{$this->params['client_bonus_place_'.$i]}',"
								. "`time`	= '{$timeout}';"
							);
						}
					}
					$i++;
				}
				mysql_query("TRUNCATE `advertiser_rating`");
			}
		}
	}
	
	function strlen( $a ) {
		return strlen( utf8_decode( stripslashes( $a ) ) );
	}
	

	function next_id($a,$b='10')
	{
	 global $_GET;

		$page = (!isset($_GET['page'])) ? 0 : $_GET['page'];
		$start = (!isset($page)) ? 0 : ($page * $b);
		$end = (!isset($page)) ? $b : ($start + $b);
		if(ceil($a/$b) < $page)
		{
			$start = 0;
			$end = $b;
		}
	 return array('start'=>$start, 'a'=>$a, 'max_page'=>$b, 'page'=>$page);
	}


	function page_next($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = TRUE)
	{
		$total_pages = ceil($num_items/$per_page);
		if ($total_pages == 1)
		{
			return '';
		}

			$start_item = $start_item * $per_page;
			$on_page = floor($start_item / $per_page) + 1;
			$page_string = '';

		if ($add_prevnext_text)
		{
			if ($on_page == 1)
			{
				$page_string = 'Evvelki | <a href="'.$base_url."&amp;page=".($on_page).'">N&#246;vbeti</a><br/>';
			}
			if ($on_page == $total_pages)
			{
				$page_string = '<a href="'.$base_url."&amp;page=".(($on_page - 2)).'">Evvelki</a> | N&#246;vbeti<br/>';
			}
		}
		if ($total_pages > 10)
		{
			$init_page_max = ($total_pages > 3) ? 3 : $total_pages;
			for($i = 1; $i < $init_page_max + 1; $i++)
			{
				$page_string .= ($i == $on_page) ? '<b>'.$i.'</b>' : '<a href="'.$base_url."&amp;page=".(($i - 1)).'">'.$i.'</a>';
				if ($i <  $init_page_max)
				{
					$page_string .= ",";
				}
			}
			if ($total_pages > 3)
			{
				if ($on_page > 1  && $on_page < $total_pages)
				{
					$page_string .= ($on_page > 5) ? '...' : ',';
					$init_page_min = ($on_page > 4) ? $on_page : 5;
					$init_page_max = ($on_page < $total_pages - 4) ? $on_page : $total_pages - 4;
					for($i = $init_page_min - 1; $i < $init_page_max + 2; $i++)
					{
						$page_string .= ($i == $on_page) ? '<b>'.$i.'</b>' : '<a href="'.$base_url."&amp;page=".(($i - 1)).'">'.$i.'</a>';
						if ($i <  $init_page_max + 1)
						{
							$page_string .= ',';
						}
					}
					$page_string .= ($on_page < $total_pages - 4) ? '...' : ',';
				}
				else
				{
					$page_string .= '...';
				}
				for($i = $total_pages - 2; $i < $total_pages + 1; $i++)
				{
					$page_string .= ($i == $on_page) ? '<b>'.$i.'</b>'  : '<a href="'.$base_url."&amp;page=".(($i - 1)).'">'.$i.'</a>';
					if($i <  $total_pages)
					{
						$page_string .= ",";
					}
				}
			}
		}
		else
		{
			for($i = 1; $i < $total_pages + 1; $i++)
			{
				$page_string .= ($i == $on_page) ? '<b>'.$i.'</b>' : '<a href="'.$base_url."&amp;page=".(($i - 1)).'">'.$i.'</a>';
				if ($i <  $total_pages)
				{
					$page_string .= ',';
				}
			}
		}
		if ($add_prevnext_text)
		{
			if ($on_page > 1  && $on_page < $total_pages)
			{
				$page_string = '<a href="'.$base_url."&amp;page=".(($on_page - 2)).'">Evvelki</a> | <a href="'.$base_url."&amp;page=".($on_page).'">N&#246;vbeti</a><br/>'.$page_string;
			}

			if ($on_page < $total_pages)
			{
				$page_string .= '';
			}
		}
		return $page_string."<br/>";
		echo "<br/>";
	}

	
	
}
$simaz = new simaz();