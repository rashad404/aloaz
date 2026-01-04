<?
$animation = new Imagick();
$animation->setFormat('gif');

$string = 'Iosif_782';
$bgColor = 'green';
$fontColor = 'yellow';
$fontName = 'comicsans';

$strlen = strlen($string);
$width = $strlen*8;
$height = 20;

$fontArray = array('arial', 'tahoma-bold', 'comicsans');

if(in_array($fontName, $fontArray)) $font = 'font/'.$fontName.'.ttf'; else $font = 'font/arial.ttf';

$background = new ImagickPixel($bgColor);

$draw = new ImagickDraw(); 

$fillcolor = new ImagickPixel($fontColor);
$draw->setFillColor( $fillcolor );

$draw->setFont($font);

for ($i = 0; $i <= strlen($string); $i++){
	$part = substr($string, 0, $i);

	$animation->newImage($width, $height, $background);
	$animation->annotateImage($draw, 5, 15, 0, $part);
	$animation->setImageDelay(30);
}

//$draw->setTextDecoration(imagick::DECORATION_UNDERLINE);

$animation->newImage($width, $height, $background);
$animation->annotateImage($draw, 5, 15, 0, $string);
$animation->setImageDelay(70);

header('Content-Type: image/gif');

echo $animation->getImagesBlob();

$fp = fopen('tmp/animegif.gif', 'w');
fwrite($fp, $animation->getImagesBlob());
fclose($fp);
?>