<?

function rusToAz($str){
    $tr = array(
        "А"=>"A","Б"=>"B","В"=>"B","Г"=>"G",
        "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
        "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"H",
        "О"=>"O","П"=>"P","Р"=>"P","С"=>"C","Т"=>"T",
        "У"=>"U","Ф"=>"F","Х"=>"X","Ц"=>"TS","Ч"=>"CH",
        "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"b","Ы"=>"YI","Ь"=>"b",
        "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
        "в"=>"b","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"p",
        "с"=>"c","т"=>"t","у"=>"y","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
    );
    return strtr($str,$tr);
}

function detect_encoding($string){
	static $list = array('utf-8', 'windows-1251');

	foreach ($list as $item) {
	$sample = iconv($item, $item, $string);
	if (md5($sample) == md5($string))
	  return $item;
	}
	return null;
}

function check_encoding($data){
	if(detect_encoding($data) == "windows-1251") $data = utf8_encode($data);
	return $data;
}

function checkIllegalChars($string) {
    $table = array(
         'Š'=>'S', 'š'=>'s', 'Đ'=>'D', 'đ'=>'d', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
         'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
         'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
         'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'B',
         'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
         'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
         'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
         'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'Ḕ'=>'E', 'ḕ'=>'e', 'Ṧ'=>'S', 'ṧ'=>'s', 'Ɓ'=>'B', 'ɓ'=>'b', 'Ă'=>'A'
    );
	$i = 0;
	forEach($table as $key => $value) {
		if (strpos($string,$key) !== false) {
			$i++;
		}
	}
	if($i>0) return true; else return false;
}
//, 'В'=>'B', 'Е'=>'E'
function normalizeLatin($string) {
     $table = array(
         'Š'=>'S', 'š'=>'s', 'Đ'=>'D', 'đ'=>'d', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c',
         'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
         'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O',
         'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'B',
         'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e',
         'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o',
         'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b',
         'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', 'Ḕ'=>'E', 'ḕ'=>'e', 'Ṧ'=>'S', 'ṧ'=>'s', 'Ɓ'=>'B', 'ɓ'=>'b', 'Ă'=>'A',
		 'Ễ'=>'E', 'Ẩ'=>'A', 'Ź'=>'Z', 'Ḿ'=>'M', 'Ḃ'=>'B', 'Ḛ'=>'E', 'Ẵ'=>'A', 'Ȳ'=>'Y', 'β'=>'B', 'Μ'=>'M'
     );
     
     return strtr($string, $table);
}

function normalizeLatina($string) {
	$table = array(
	'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 
	'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', '×' => 'x', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 
	'Ý' => 'Y', 'Þ' => 'b', 'ß' => 'B', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 
	'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 's', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 
	'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ý' => 'y', 'þ' => 'b', 'ÿ' => 'y', 'Ā' => 'A', 'ā' => 'a', 'Ă' => 'A', 'ă' => 'a', 'Ą' => 'A', 'ą' => 'a', 'Ć' => 'C', 'ć' => 'c', 
	'Ĉ' => 'C', 'ĉ' => 'c', 'Ċ' => 'C', 'ċ' => 'c', 'Č' => 'C', 'č' => 'c', 'Ď' => 'D', 'ď' => 'd', 'Đ' => 'D', 'đ' => 'd', 'Ē' => 'E', 'ē' => 'e', 'Ĕ' => 'E', 'ĕ' => 'e', 
	'Ė' => 'E', 'ė' => 'e', 'Ę' => 'E', 'ę' => 'e', 'Ě' => 'E', 'ě' => 'e', 'Ĝ' => 'G', 'ĝ' => 'g', 'Ğ' => 'G', 'ğ' => 'g', 'Ġ' => 'G', 'ġ' => 'g', 'Ģ' => 'G', 'ģ' => 'g', 
	'Ĥ' => 'H', 'ĥ' => 'h', 'Ħ' => 'H', 'ħ' => 'h', 'Ĩ' => 'I', 'ĩ' => 'i', 'Ī' => 'I', 'ī' => 'i', 'Ĭ' => 'I', 'ĭ' => 'i', 'Į' => 'I', 'į' => 'i', 'İ' => 'I', 'ı' => 'i', 
	'Ĳ' => 'IJ', 'ĳ' => 'ij', 'Ĵ' => 'j', 'ĵ' => 'j', 'Ķ' => 'K', 'ķ' => 'k', 'ĸ' => 'k', 'Ĺ' => 'L', 'ĺ' => 'I', 'Ļ' => 'L', 'ļ' => 'I', 'Ľ' => 'L', 'ľ' => 'I', 'Ŀ' => 'L', 
	'ŀ' => 'I', 'Ł' => 'L', 'ł' => 'L', 'Ń' => 'N', 'ń' => 'n', 'Ņ' => 'N', 'ņ' => 'n', 'Ň' => 'N', 'ň' => 'n', 'ŉ' => 'n', 'Ŋ' => 'N', 'ŋ' => 'n', 'Ō' => 'O', 'ō' => 'o', 
	'Ŏ' => 'O', 'ŏ' => 'o', 'Ő' => 'O', 'ő' => 'o', 'Œ' => 'E', 'œ' => 'a', 'Ŕ' => 'R', 'ŕ' => 'r', 'Ŗ' => 'R', 'ŗ' => 'r', 'Ř' => 'R', 'ř' => 'r', 'Ś' => 'S', 'ś' => 's', 
	'Ŝ' => 'S', 'ŝ' => 's', 'Ş' => 'S', 'ş' => 's', 'Š' => 'S', 'š' => 's', 'Ţ' => 'T', 'ţ' => 't', 'Ť' => 'T', 'ť' => 't', 'Ŧ' => 'T', 'ŧ' => 't', 'Ũ' => 'U', 'ũ' => 'u', 
	'Ū' => 'U', 'ū' => 'u', 'Ŭ' => 'U', 'ŭ' => 'u', 'Ů' => 'U', 'ů' => 'u', 'Ű' => 'U', 'ű' => 'u', 'Ų' => 'U', 'ų' => 'u', 'Ŵ' => 'W', 'ŵ' => 'w', 'Ŷ' => 'Y', 'ŷ' => 'y', 
	'Ÿ' => 'Y', 'Ź' => 'Z', 'ź' => 'z', 'Ż' => 'Z', 'ż' => 'z', 'Ž' => 'Z', 'ž' => 'z', 'ſ' => 'f', 'ƀ' => 'B', 'Ɓ' => 'B', 'Ƃ' => 'B', 'ƃ' => 'B', 'Ƅ' => 'b', 'ƅ' => 'b', 
	'Ɔ' => 'C', 'Ƈ' => 'C', 'ƈ' => 'c', 'Ɖ' => 'D', 'Ɗ' => 'D', 'Ƌ' => 'B', 'ƌ' => 'b', 'ƍ' => 'Q', 'Ǝ' => 'E', 'Ə' => 'Ə', 'Ɛ' => 'E', 'Ƒ' => 'F', 'ƒ' => 'f', 'Ɠ' => 'G', 
	'Ɣ' => 'Y', 'ƕ' => 'N', 'Ɩ' => 'I', 'Ɨ' => 'I', 'Ƙ' => 'K', 'ƙ' => 'k', 'ƚ' => 'I', 'ƛ' => 'Y', 'Ɯ' => 'W', 'Ɲ' => 'N', 'ƞ' => 'n', 'Ɵ' => 'O', 'Ơ' => 'O', 'ơ' => 'o', 
	'Ƣ' => 'q', 'ƣ' => 'q', 'Ƥ' => 'P', 'ƥ' => 'B', 'Ʀ' => 'R', 'Ƨ' => 'S', 'ƨ' => 's', 'Ʃ' => 'E', 'ƪ' => 'l', 'ƫ' => 't', 'Ƭ' => 'T', 'ƭ' => 't', 'Ʈ' => 'T', 'Ư' => 'U', 
	'ư' => 'u', 'Ʊ' => 'O', 'Ʋ' => 'U', 'Ƴ' => 'Y', 'ƴ' => 'Y', 'Ƶ' => 'Z', 'ƶ' => 'z', 'Ʒ' => 'E', 'Ƹ' => 'E', 'ƹ' => 'E', 'ƺ' => 'E', 'ƻ' => 'Z', 'Ƽ' => 'B', 'ƽ' => 'b', 
	'ƾ' => 'b', 'ƿ' => 'd', 'ǀ' => 'l', 'ǁ' => 'l', 'ǂ' => 'ǂ', 'ǃ' => 'I', 'Ǆ' => 'Ǆ', 'ǅ' => 'ǅ', 'ǆ' => 'dz', 'Ǉ' => 'LJ', 'ǈ' => 'Lj', 'ǉ' => 'lj', 'Ǌ' => 'NJ', 'ǋ' => 'NJ', 
	'ǌ' => 'nj', 'Ǎ' => 'A', 'ǎ' => 'a', 'Ǐ' => 'I', 'ǐ' => 'i', 'Ǒ' => 'O', 'ǒ' => 'o', 'Ǔ' => 'U', 'ǔ' => 'u', 'Ǖ' => 'U', 'ǖ' => 'u', 'Ǘ' => 'U', 'ǘ' => 'u', 'Ǚ' => 'U', 
	'ǚ' => 'u', 'Ǜ' => 'U', 'ǜ' => 'u', 'ǝ' => 'ə', 'Ǟ' => 'A', 'ǟ' => 'a', 'Ǡ' => 'A', 'ǡ' => 'a', 'Ǣ' => 'A', 'ǣ' => 'a', 'Ǥ' => 'G', 'ǥ' => 'g', 'Ǧ' => 'G', 'ǧ' => 'g', 
	'Ǩ' => 'K', 'ǩ' => 'k', 'Ǫ' => 'Q', 'ǫ' => 'q', 'Ǭ' => 'Q', 'ǭ' => 'q', 'Ǯ' => 'Z', 'ǯ' => 'Z', 'ǰ' => 'J', 'Ǳ' => 'DZ', 'ǲ' => 'Dz', 'ǳ' => 'dz', 'Ǵ' => 'G', 'ǵ' => 'g', 
	'Ƕ' => 'H', 'Ƿ' => 'D', 'Ǹ' => 'N', 'ǹ' => 'n', 'Ǻ' => 'A', 'ǻ' => 'a', 'Ǽ' => 'A', 'ǽ' => 'a', 'Ǿ' => 'O', 'ǿ' => 'o', 'Ȁ' => 'A', 'ȁ' => 'a', 'Ȃ' => 'A', 'ȃ' => 'a', 
	'Ȅ' => 'E', 'ȅ' => 'e', 'Ȇ' => 'E', 'ȇ' => 'e', 'Ȉ' => 'I', 'ȉ' => 'i', 'Ȋ' => 'I', 'ȋ' => 'i', 'Ȍ' => 'O', 'ȍ' => 'o', 'Ȏ' => 'O', 'ȏ' => 'o', 'Ȑ' => 'R', 'ȑ' => 'r', 
	'Ȓ' => 'R', 'ȓ' => 'r', 'Ȕ' => 'U', 'ȕ' => 'u', 'Ȗ' => 'U', 'ȗ' => 'u', 'Ș' => 'S', 'ș' => 's', 'Ț' => 'T', 'ț' => 't', 'Ȝ' => 'B', 'ȝ' => 'b', 'Ȟ' => 'H', 'ȟ' => 'h', 
	'Ƞ' => 'N', 'ȡ' => 'd', 'Ȣ' => 'b', 'ȣ' => 'b', 'Ȥ' => 'Z', 'ȥ' => 'z', 'Ȧ' => 'A', 'ȧ' => 'a', 'Ȩ' => 'E', 'ȩ' => 'e', 'Ȫ' => 'O', 'ȫ' => 'o', 'Ȭ' => 'O', 'ȭ' => 'o', 
	'Ȯ' => 'O', 'ȯ' => 'o', 'Ȱ' => 'O', 'ȱ' => 'o', 'ȳ' => 'y', 'ȴ' => 'l', 'ȵ' => 'n', 'ȶ' => 'ȶ', 'ȸ' => 'f', 'ȹ' => 'f', 'Ⱥ' => 'A', 'Ȼ' => 'C', 'ȼ' => 'c', 'Ƚ' => 'L', 
	'Ⱦ' => 'T', 'ȿ' => 's', 'ɀ' => 'Z', 'Ɂ' => 'Ɂ', 'ɂ' => 'ɂ', 'Ƀ' => 'B', 'Ʉ' => 'U', 'Ʌ' => 'A', 'Ɇ' => 'E', 'ɇ' => 'e', 'Ɉ' => 'J', 'ɉ' => 'j', 'Ɋ' => 'Q', 'ɋ' => 'q', 
	'Ɍ' => 'R', 'ɍ' => 'r', 'Ɏ' => 'Y', 'ɏ' => 'y', 'ɐ' => 'e', 'ɑ' => 'a', 'ɒ' => 'a', 'ɓ' => 'b', 'ɔ' => 'c', 'ɕ' => 'e', 'ɖ' => 'd', 'ɗ' => 'd', 'ɘ' => 'e', 'ə' => 'ə', 
	'ɚ' => 'ə', 'ɛ' => 'e', 'ɜ' => 'e', 'ɝ' => 'e', 'ɞ' => 'b', 'ɟ' => 'J', 'ɠ' => 'g', 'ɡ' => 'g', 'ɢ' => 'g', 'ɣ' => 'Y', 'ɤ' => 'y', 'ɥ' => 'Y', 'ɦ' => 'n', 'ɧ' => 'n', 
	'ɨ' => 'i', 'ɩ' => 'i', 'ɪ' => 'i', 'ɫ' => 'i', 'ɬ' => 'i', 'ɭ' => 'l', 'ɮ' => 'B', 'ɯ' => 'w', 'ɰ' => 'w', 'ɱ' => 'm', 'ɲ' => 'n', 'ɳ' => 'n', 'ɴ' => 'n', 'ɵ' => 'o', 
	'ɶ' => 'e', 'ɷ' => 'o', 'ɸ' => 'f', 'ɹ' => 'l', 'ɺ' => 'l', 'ɻ' => 'l', 'ɼ' => 'r', 'ɽ' => 'r', 'ɾ' => 'r', 'ɿ' => 'j', 'ʀ' => 'R', 'ʁ' => 'B', 'ʂ' => 's', 'ʃ' => 'L', 
	'ʄ' => 'L', 'ʅ' => 'l', 'ʆ' => 'L', 'ʇ' => 't', 'ʈ' => 't', 'ʉ' => 'u', 'ʊ' => 'v', 'ʋ' => 'v', 'ʌ' => 'A', 'ʍ' => 'M', 'ʎ' => 'h', 'ʏ' => 'Y', 'ʐ' => 'z', 'ʑ' => 'z', 
	'ʒ' => 'z', 'ʓ' => 'z', 'ʔ' => '?', 'ʕ' => 'c', 'ʖ' => 'c', 'ʗ' => 'C', 'ʘ' => 'o', 'ʙ' => 'B', 'ʚ' => 'b', 'ʛ' => 'G', 'ʜ' => 'H', 'ʝ' => 'I', 'ʞ' => 'K', 'ʟ' => 'L', 
	'ʠ' => 'q', 'ʡ' => '?', 'ʢ' => 'c', 'ʣ' => '', 'ʤ' => '', 'ʥ' => '', 'ʦ' => '', 'ʧ' => '', 'ʨ' => '', 'ʩ' => '', 'ʪ' => '', 'ʫ' => '', 'ʬ' => '', 'ʮ' => 'Y', 'ʯ' => 'Y', 
	'Ḁ' => 'A', 'ḁ' => 'a', 'Ḃ' => 'B', 'ḃ' => 'b', 'Ḅ' => 'B', 'ḅ' => 'b', 'Ḇ' => 'B', 'ḇ' => 'b', 'Ḉ' => 'C', 'ḉ' => 'c', 'Ḋ' => 'D', 'ḋ' => 'd', 'Ḍ' => 'D', 'ḍ' => 'd', 
	'Ḏ' => 'D', 'ḏ' => 'd', 'Ḑ' => 'D', 'ḑ' => 'd', 'Ḓ' => 'D', 'ḓ' => 'd', 'Ḕ' => 'E', 'ḕ' => 'e', 'Ḗ' => 'E', 'ḗ' => 'e', 'Ḙ' => 'E', 'ḙ' => 'e', 'Ḛ' => 'E', 'ḛ' => 'e', 
	'Ḝ' => 'E', 'ḝ' => 'e', 'Ḟ' => 'F', 'ḟ' => 'f', 'Ḡ' => 'G', 'ḡ' => 'g', 'Ḣ' => 'H', 'ḣ' => 'h', 'Ḥ' => 'H', 'ḥ' => 'h', 'Ḧ' => 'H', 'ḧ' => 'h', 'Ḩ' => 'H', 'ḩ' => 'h', 
	'Ḫ' => 'H', 'ḫ' => 'h', 'Ḭ' => 'I', 'ḭ' => 'i', 'Ḯ' => 'I', 'ḯ' => 'i', 'Ḱ' => 'K', 'ḱ' => 'k', 'Ḳ' => 'K', 'ḳ' => 'k', 'Ḵ' => 'K', 'ḵ' => 'k', 'Ḷ' => 'L', 'ḷ' => 'I', 
	'Ḹ' => 'L', 'ḹ' => 'l', 'Ḻ' => 'L', 'ḻ' => 'l', 'Ḽ' => 'L', 'ḽ' => 'l', 'Ḿ' => 'M', 'ḿ' => 'm', 'Ṁ' => 'M', 'ṁ' => 'm', 'Ṃ' => 'M', 'ṃ' => 'm', 'Ṅ' => 'N', 'ṅ' => 'n', 
	'Ṇ' => 'N', 'ṇ' => 'n', 'Ṉ' => 'N', 'ṉ' => 'n', 'Ṋ' => 'N', 'ṋ' => 'n', 'Ṍ' => 'O', 'ṍ' => 'o', 'Ṏ' => 'O', 'ṏ' => 'o', 'Ṑ' => 'O', 'ṑ' => 'o', 'Ṓ' => 'O', 'ṓ' => 'o', 
	'Ṕ' => 'P', 'ṕ' => 'p', 'Ṗ' => 'P', 'ṗ' => 'p', 'Ṙ' => 'R', 'ṙ' => 'r', 'Ṛ' => 'R', 'ṛ' => 'r', 'Ṝ' => 'R', 'ṝ' => 'r', 'Ṟ' => 'R', 'ṟ' => 'r', 'Ṡ' => 'S', 'ṡ' => 's', 
	'Ṣ' => 'S', 'ṣ' => 's', 'Ṥ' => 'S', 'ṥ' => 's', 'Ṧ' => 'S', 'ṧ' => 's', 'Ṩ' => 'S', 'ṩ' => 's', 'Ṫ' => 'T', 'ṫ' => 't', 'Ṭ' => 'T', 'ṭ' => 't', 'Ṯ' => 'T', 'ṯ' => 't', 
	'Ṱ' => 'T', 'ṱ' => 't', 'Ṳ' => 'U', 'ṳ' => 'u', 'Ṵ' => 'U', 'ṵ' => 'u', 'Ṷ' => 'U', 'ṷ' => 'u', 'Ṹ' => 'U', 'ṹ' => 'u', 'Ṻ' => 'U', 'ṻ' => 'u', 'Ṽ' => 'V', 'ṽ' => 'v', 
	'Ṿ' => 'V', 'ṿ' => 'v', 'Ẁ' => 'W', 'ẁ' => 'w', 'Ẃ' => 'W', 'ẃ' => 'w', 'Ẅ' => 'W', 'ẅ' => 'w', 'Ẇ' => 'W', 'ẇ' => 'w', 'Ẉ' => 'W', 'ẉ' => 'w', 'Ẋ' => 'X', 'ẋ' => 'x', 
	'Ẍ' => 'X', 'ẍ' => 'x', 'Ẏ' => 'Y', 'ẏ' => 'y', 'Ẑ' => 'Z', 'ẑ' => 'z', 'Ẓ' => 'Z', 'ẓ' => 'z', 'Ẕ' => 'Z', 'ẕ' => 'z', 'ẗ' => 't', 'ẘ' => 'w', 'ẙ' => 'y', 'ẚ' => 'a', 
	'ẛ' => 'f', 'ẞ' => 'B', 'Ạ' => 'A', 'ạ' => 'a', 'Ả' => 'A', 'ả' => 'a', 'Ấ' => 'A', 'ấ' => 'a', 'Ầ' => 'A', 'ầ' => 'a', 'Ẩ' => 'A', 'ẩ' => 'a', 'Ẫ' => 'A', 'ẫ' => 'a', 
	'Ậ' => 'A', 'ậ' => 'a', 'Ắ' => 'A', 'ắ' => 'a', 'Ằ' => 'A', 'ằ' => 'a', 'Ẳ' => 'A', 'ẳ' => 'a', 'Ẵ' => 'A', 'ẵ' => 'a', 'Ặ' => 'A', 'ặ' => 'a', 'Ẹ' => 'E', 'ẹ' => 'e', 
	'Ẻ' => 'E', 'ẻ' => 'e', 'Ẽ' => 'E', 'ẽ' => 'e', 'Ế' => 'E', 'ế' => 'e', 'Ề' => 'E', 'ề' => 'e', 'Ể' => 'E', 'ể' => 'e', 'Ễ' => 'E', 'ễ' => 'e', 'Ệ' => 'E', 'ệ' => 'e', 
	'Ỉ' => 'I', 'ỉ' => 'i', 'Ị' => 'I', 'ị' => 'i', 'Ọ' => 'O', 'ọ' => 'o', 'Ỏ' => 'O', 'ỏ' => 'o', 'Ố' => 'O', 'ố' => 'o', 'Ồ' => 'O', 'ồ' => 'o', 'Ổ' => 'O', 'ổ' => 'o', 
	'Ỗ' => 'O', 'ỗ' => 'o', 'Ộ' => 'O', 'ộ' => 'o', 'Ớ' => 'O', 'ớ' => 'o', 'Ờ' => 'O', 'ờ' => 'o', 'Ở' => 'O', 'ở' => 'o', 'Ỡ' => 'O', 'ỡ' => 'o', 'Ợ' => 'O', 'ợ' => 'o', 
	'Ụ' => 'U', 'ụ' => 'u', 'Ủ' => 'U', 'ủ' => 'u', 'Ứ' => 'U', 'ứ' => 'u', 'Ừ' => 'U', 'ừ' => 'u', 'Ử' => 'U', 'ử' => 'u', 'Ữ' => 'U', 'ữ' => 'u', 'Ự' => 'U', 'ự' => 'u', 
	'Ỳ' => 'Y', 'ỳ' => 'y', 'Ỵ' => 'Y', 'ỵ' => 'y', 'Ỷ' => 'Y', 'ỷ' => 'y', 'Ỹ' => 'Y', 'ỹ' => 'y', 'ℓ' => 'l', '№' => 'N', 'Ω' => 'O', 'K' => 'K', 'Å' => 'A', '℮' => 'e'
	);
	return strtr($string, $table);
}

function detectBadWord($str){
	$array = array(
	"admin", 
	"rehberem", 
	"buraninsahibi", 
	"chatrehberi", 
	"sekslebiz", 
	"seksazbiz", 
	"seksilerru", 
	"sibelimbiz", 
	"seviwekbiz", 
	"toygecesinet", 
	"amciqimbiz", 
	"amciqlaru", 
	"amciqlabiz", 
	"bazdiqdabiz", 
	"bitanemlebiz", 
	"kalbimaz", 
	"sikiseybiz", 
	"siyinbiz", 
	"seksiqizbiz", 
	"eylenirikaz", 
	"ehtiraslibiz", 
	"geliniybiz", 
	"gedekaz", 
	"qehbelerru", 
	"qehbelerorg", 
	"qoyumru", 
	"rorg", 
	"qizgelinaz", 
	"qehbemtepsu", 
	"qehbembiz", 
	"gelinikbiz", 
	"sevgilimleru", 
	"ahgelintk", 
	"azeseksnet", 
	"pulsuzinternet", 
	"olmazaz", 
	"olaqaz", 
	"ehtirasexru", 
	"evlileraz", 
	"yandimaz", 
	"xuliqankabiz", 
	"xalaaz", 
	"ahgélìntк", 
	"noktebiz", 
	"sikderu", 
	"bebemaz", 
	"sagolbiz", 
	"yarimlaaz", 
	"birolaqbiz", 
	"qelbnet", 
	"qaydabiz", 
	"qizlarus", 
	"goruweknet", 
	"hosdedbiz", 
	"zamanimbiz", 
	"feqanaz", 
	"sevgide6iz", 
	"SEVGiDEBiZ", 
	"uzeyirlebiz", 
	"kicikaz", 
	"qaraqanorg", 
	"SAGLBiZ", 
	"QANAZAZ", 
	"QARAQANRG", 
	"BЕBEМAZ", 
	"bbeaz", 
	"KiLiPbiz", 
	"GeceAz", 
	"Nukaaz", 
	"DOSTАMBIZ", 
	"QeceЬiz", 
	"QuCAZ", 
	"gunewbiz", 
	"GeTMeAZ", 
	"BosaIaxBiz", 
	"daxildebiz", 
	"ibadetdebiz", 
	"BEBEMz", 
	"EBMz", 
	"NURAYRU", 
	"EMz", 
	"ecazkarnet", 
	"ZorIaRu", 
	"gulaz", 
	"etirafru", 
	"UZANAQBIZ", 
	"YORGUNAMAZ", 
	"Qizimnet", 
	"BEBEMno", 
	"lebema", 
	"nokteAz", 
	"noqteAz", 
	"BEIEMAZ", 
	"UZANAQBlZ", 
	"IEВEМAZ", 
	"IEIEМAZ", 
	"Ordamenimnickim", 
	"LUTEMRU", 
	"PULSUZQEYDOL", 
	"YURDWS", 
	"Buyurdaxilol", 
	"QeHBeLeRBiz", 
	"noqtews", 
	"admiiem", 
	"Olaqvvs", 
	"LUTUKRU", 
	"Qeydiyatdankec", 
	"geloraorda", 
	"ydamenimnick", 
	"balPostverecem", 
	"UZANAQBIZ", 
	"Poostverecem", 
	"gelsenburdakinick", 
	"ballhediуye", 
	"Sisitem", 
	"erotik", 
	"seks", 
	"gelinler", 
	"eyesaz", 
	"postverilir", 
	"balverilir", 
	"seviyyelimekan", 
	"herseypulsuz", 
	"qizamaz", 
	"Geeelinem",  
	"Seeks",  
	"XALAWKA",  
	"hormetaz", 
	"EHTlRASlM", 
	"EROTlKCHAT", 
	"PULSUZVlDEO", 
	"Orrdameniim", 
	"dariiıxirsangel", 
	"SAGOLBZ", 
	"yazdiqlariıminhamisiniverecem", 
	"refikeleerim", 
	"Orrdamenlimadim", 
	"menigozleeyirlergelsen", 
	"Oradamenlimadlm", 
	"gelloradaa", 
	"сoxmarraagli", 
	"ehtirasimws", 
	"ehtLrasLmws", 
	"ehtLrasimws", 
	"ehtirasLmws", 
	"ehtLrasimvvs", 
	"ehtirasimvvs", 
	"ehtrasimvvs", 
	"ehtirasmws", 
	"ehtirasimvv", 
	"ehtlrasimvv", 
	"ehtirasLvv", 
	"ehtirasimv", 
	"ehtirasimw", 
	"ehtlraslmw", 
	"ehtlraslmv", 
	"ehtlrasmv", 
	"ehtrasmv", 
	"bebema", 
	"ielema", 
	"ieiema", 
	"uurdws", 
	"bakcellb", 
	"bakceiib", 
	"bakcelib", 
	"bakceilb", 
	"bakceb", 
	"yurdvv", 
	"yurdws", 
	"yatmabiz", 
	"uatmab", 
	"yatmabz", 
	"kralaz", 
	"kraaz", 
	"kraiaz", 
	"postaktiv", 
	"postaktlv", 
	"postaktv", 
	"yatmablz", 
	"uurdv", 
	"getdimyazmabana", 
	"mvvs", 
	"mws", 
	"admingel", 
	"alovlub", 
	"yatmab"
	);
	
	$i = 0;
	$str = rusToAz($str);
	$str = normalizeLatin($str);
	$str = normalizeLatina($str);
	$str = preg_replace("/[^A-Za-z ]/", "", $str);
	$str = str_replace(" ", "", $str);
	$str = strtolower($str);
	forEach($array as $key => $value) {
		if (strpos($str,strtolower($value)) !== false) {
			$i++;
		}
	}
	if($i > 0){
		return true;
	}
	else{
		return false;
	}
}


function getOnline($type){
	$tmpfile = $_SERVER['DOCUMENT_ROOT'].'/inc/temp/online.dat';

	if((filemtime($tmpfile)+3) < time()){
		$cnt_all = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `last_activity` >= ".(time()-600)." AND `no_dating` = 1;"), 0);
		$cnt_women = mysql_result(mysql_query("SELECT COUNT(`id`) FROM `aloaz_db`.`user` WHERE `last_activity` > ".(time()-600)." AND `no_dating` = 1 AND `sex`=1;"), 0);
		$cnt_men = $cnt_all-$cnt_women;

		if($cnt_men > 0){
			$data = $cnt_all.'|'.$cnt_women.'|'.$cnt_men;
			$fp = fopen($tmpfile, 'w');
			fwrite($fp, $data);
			fclose($fp);
		}
	}
	
	$data = file($tmpfile);

	$expdata = explode("|", $data[0]);
	$onall = $expdata[0];
	$onwomen = $expdata[1];
	$onmen = $expdata[2];

	if($type == 'all') return $onall;
	else if($type == 'women') return $onwomen;
	else if($type == 'men') return $onmen;
	else return $onall;
}


function updateXal(){
	$tempfile = $_SERVER['DOCUMENT_ROOT'].'/inc/temp/xal.dat';
	$data = file($tempfile);
	$expdata = $data[0];

	if($data[0] != date('Y-m-d')){
		mysql_query("UPDATE `aloaz_db`.`user` SET `point` = '0' WHERE `point` > 0;"); 
		if(mysql_affected_rows()>0){
			$fp = fopen($tempfile, 'w');
			fwrite($fp, date('Y-m-d'));
			fclose($fp);
		}
		return true;
	}
}


function updatePosts(){
	$tempfile = $_SERVER['DOCUMENT_ROOT'].'/inc/temp/post.dat';
	$data = file($tempfile);
	$expdata = $data[0];

	if($data[0] != date('Y-m-d')){
		mysql_query("UPDATE `aloaz_db`.`user` SET `msg_count_day` = '0' WHERE `msg_count_day` > 0;"); 
		if(mysql_affected_rows()>0){
			$fp = fopen($tempfile, 'w');
			fwrite($fp, date('Y-m-d'));
			fclose($fp);
		}
		return true;
	}
}


function formatSizeUnits($bytes){
	if ($bytes >= 1073741824){
		$bytes = number_format($bytes / 1073741824, 2) . ' GB';
	}
	elseif ($bytes >= 1048576)
	{
		$bytes = number_format($bytes / 1048576, 2) . ' MB';
	}
	elseif ($bytes >= 1024)
	{
		$bytes = number_format($bytes / 1024, 2) . ' KB';
	}
	elseif ($bytes > 1)
	{
		$bytes = $bytes . ' bytes';
	}
	elseif ($bytes == 1)
	{
		$bytes = $bytes . ' byte';
	}
	else
	{
		$bytes = '0 bytes';
	}

	return $bytes;
}


function checkPhoneBan($phone){
	$query = mysql_query("SELECT `reason` FROM `aloaz_db`.`chat_phone_ban` WHERE `phone` = '".$phone."'");
	if(mysql_num_rows($query) > 0){
		$reason = mysql_result($query, 0);
		echo '<span style="color: red;">Diqqet!</span><br/>İstifade Şertlerini kobud şekilde pozduğunuza göre xidmetden istifadeniz mehdudlaşdırılıb.<br/><br/>';
		echo 'Medudlaşdırılma sebebi:<br/>';
		echo '<b>'.$reason.'</b><br/><br/>';
		echo 'Eger sebeble razı deyilsinizse info@alo.az ünvanına email yazıb meseleni aydınlaşdıra bilersiniz.<br/><br/><a href="logout.php">Çıxış</a>';
		echo '</div>';
		include $_SERVER['DOCUMENT_ROOT'].'/inc/footer.php';
		exit;
	}
}


function deleteOldMessages(){
	if(intval(date('H')) == 0 && intval(date('i')) < 10){
		$tempfile = $_SERVER['DOCUMENT_ROOT'].'/inc/temp/messages.dat';
		$data = file($tempfile);

		if($data[0] != date('Y-m-d')){
			$delete = mysql_query("DELETE FROM `chat_messages` WHERE `time` < '".(time() - 14*86400)."';");
			if($delete){
				$fp = fopen($tempfile, 'w');
				fwrite($fp, date('Y-m-d'));
				fclose($fp);
				
				return true;
			}
		}
	}
}


function monthName($month){
	$array = array('', 'Yanvar', 'Fevral', 'Mart', 'Aprel', 'May', 'İyun', 'İyul', 'Avqust', 'Sentyabr', 'Oktyabr', 'Noyabr', 'Dekabr');
	
	if(empty($month)) $month = date('n');
	return $array[$month];
}


?>
