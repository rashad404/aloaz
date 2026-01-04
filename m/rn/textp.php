<?
$width = '600';
$height = '200';
$im = new Imagick();
$draw = new ImagickDraw();
$draw->setFontSize( 96 );
$fillcolor = new ImagickPixel( "blue" );
$draw->setFillColor( $fillcolor );
$draw->setGravity( Imagick::GRAVITY_CENTER );
$bgcolor = new ImagickPixel( "yellow" );
$text = 'Rubblewebs';
$im->newImage($width, $height, $bgcolor );
$im->annotateImage($draw, 0, 0, 0, $text);
$im->setImageFormat("png");
$im->writeImage( 'tmp/text.png' );
?>