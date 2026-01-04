<?
$animation = new Imagick();
$animation->setFormat('gif');

$string = 'gulciceksssssssss';
$bgColor = 'green';
$fontColor = 'yellow';
$fontName = 'comicsans';

$strlen = strlen($string);
$width = $strlen*10;
$height = 20;

$mywidth = $strlen*6;
$margin  = ($width - $mywidth)/2;
$fontArray = array('arial', 'tahoma-bold', 'comicsans');
if(in_array($fontName, $fontArray)) $font = 'font/'.$fontName.'.ttf'; else $font = 'font/arial.ttf';

$background = new ImagickPixel($bgColor);

$draw = new ImagickDraw(); 

$fillcolor = new ImagickPixel($fontColor);
$draw->setFillColor( $fillcolor );

$draw->setFont($font);


	$part = '';
	$animation->newImage($width, $height, $background);
	$animation->annotateImage($draw, $margin, 15, 0, $part);
	$animation->setImageDelay(30);
	
	 


//$draw->setTextDecoration(imagick::DECORATION_UNDERLINE);

$animation->newImage($width, $height, $background);
$animation->annotateImage($draw, $margin, 15, 0, $string);
$animation->setImageDelay(70);

header('Content-Type: image/gif');

echo $animation->getImagesBlob();

$fp = fopen('tmp/animegif.gif', 'w');
fwrite($fp, $animation->getImagesBlob());
fclose($fp);
?>