<?
function countryAz($country){
    $country = trim($country);
    $countryLower = strtolower($country);

    $array = array(
        'china' => 'Çin',
        'italy' => 'İtaliya',
        'usa' => 'ABŞ',
        'spain' => 'İspaniya',
        'iran' => 'İran',
        'france' => 'Fransa',
        'c. korea' => 'C.Koreya',
        'sitzerland' => 'İsveçrə',
        'uk' => 'İngiltərə',
        'netherlands' => 'Hollandiya',
        'austria' => 'Avstriya',
        'belgium' => 'Beçlika',
        'norway' => 'Norveç',
        'portugal' => 'Portuqaliya',
        'sweden' => 'İsveç',
        'australia' => 'Avstraliya',
        'brazil' => 'Braziliya',
        'canada' => 'Kanada',
        'malaysia' => 'Malayziya',
        'denmark' => 'Danimarka',
        'israel' => 'İsrail',
        'turkey' => 'Türkiyə',
        'czechia' => 'Çexiya',
        'japan' => 'Yaponiya',
        'azerbaijan' => 'Azərbaycan'
    );

    if(strlen($array[$countryLower]) > 0) return $array[$countryLower]; else return $country;
}

echo countryAz('Azerbahijan');
?>