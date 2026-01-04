<?php # Script 1
echo 'Font width: ' . imagefontwidth(4);exit;	
/*
 * This page creates a simple image.
 * The image makes use of a TrueType font.
 */

// Establish image factors:
$text = 'Sample text';
$font_size = 12; // Font size is in pixels.
$font_file = 'font/arial.ttf'; // This is the path to your font file.

// Retrieve bounding box:
$type_space = imagettfbbox($font_size, 0, $font_file, $text);
var_dump($try_space); exit;
// Determine image width and height, 10 pixels are added for 5 pixels padding:
$image_width = abs($type_space[4] - $type_space[0]) + 10;
$image_height = abs($type_space[5] - $type_space[1]) + 10;
 
// Create image:
$image = imagecreatetruecolor($image_width, $image_height);

// Allocate text and background colors (RGB format):
$text_color = imagecolorallocate($image, 255, 255, 255);
$bg_color = imagecolorallocate($image, 0, 0, 0);

// Fill image:
imagefill($image, 0, 0, $bg_color);

// Fix starting x and y coordinates for the text:
$x = 5; // Padding of 5 pixels.
$y = $image_height - 5; // So that the text is vertically centered.

// Add TrueType text to image:
imagettftext($image, $font_size, 0, $x, $y, $text_color, $font_file, $text);

// Generate and send image to browser:
header('Content-type: image/png');
imagepng($image);

// Destroy image in memory to free-up resources:
imagedestroy($image);

?>