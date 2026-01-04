<?php
function getCurrencies(){
	$xml = @file_get_contents('http://www.cbar.az/currencies/'.date('d.m.Y').'.xml');
	$simplexml = simplexml_load_string($xml);
	
	$uptime = time() + 6*3600;
	$currencyContent .= $uptime.'||';
	
	foreach ($simplexml->xpath('//Valute') as $Valute) {
		$Nominal = $Valute->Nominal;
		$Name = $Valute->Name;
		$Value = $Valute->Value;
		$Code = $Valute->Code;
		$currencyContent .= $Nominal.' '.$Name.': '.$Value.' AZN<br/>';
	}
	$currencyContent = str_replace("Ə", "E", $currencyContent);
	$currencyContent = str_replace("ə", "e", $currencyContent);

	$cache = file('tmp/currencies');
	$explode = explode('||', $cache[0]);
	if($explode[0] < time()){
		if($fp = @fopen('tmp/currencies', 'w')){
			fwrite($fp, $currencyContent);
			fclose($fp);
		}
		else{
			return 'Unable to update.';
		}
	}
	return $explode[1];
}
?>