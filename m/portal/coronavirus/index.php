<?
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('memory_limit', '-1');

include 'simple_html_dom.php';

$ch = curl_init("https://www.worldometers.info/coronavirus");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTREDIR, 3);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$output = curl_exec($ch);
curl_close($ch);

$output = str_replace('main_table_countries_today', 'maintablecountriestoday', $output);
//$output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $output);
//if(strpos($output,'main_table_countries_yesterday')) echo ';;;;;';

$html = str_get_html($output);
$data = $html->find('table[id=maintablecountriestoday]', 0);

$dom = new DOMDocument;
$dom->loadHTML($data);
$xpath = new DOMXPath($dom);

$dataArray = [];

$tr     = $dom->getElementsByTagName('tr');

$c = 0;
foreach ($tr as $element) {
    if($c > 0){
        $country       = $element->getElementsByTagName('td')->item(0)->textContent;
        $total_cases     = $element->getElementsByTagName('td')->item(1)->textContent;
        $new_cases     = $element->getElementsByTagName('td')->item(2)->textContent;
        $total_deaths       = $element->getElementsByTagName('td')->item(3)->textContent;
        $new_deaths       = $element->getElementsByTagName('td')->item(4)->textContent;
        $total_recovered       = $element->getElementsByTagName('td')->item(5)->textContent;
        $active_cases    = $element->getElementsByTagName('td')->item(6)->textContent;
        $critical    = $element->getElementsByTagName('td')->item(7)->textContent;

        array_push($dataArray, array(
            "country"      => $country,
            "total_cases"    => $total_cases,
            "new_cases"    => $new_cases,
            "total_deaths"      => $total_deaths,
            "new_deaths"      => $new_deaths,
            "total_recovered"      => $total_recovered,
            "active_cases"   => $active_cases,
            "critical"   => $critical
        ));
    }
        $c++;
}

print_r($dataArray) ;

?>