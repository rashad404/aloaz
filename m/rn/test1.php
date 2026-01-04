<?
   /*** a new Imagick object ***/ 
    $aniGif = new Imagick();
 
    /*** set the image format to gif ***/
    $aniGif->setFormat( "gif" );
 
    /*** a new ImagickPixel object for the colors ***/
    $color = new ImagickPixel( "white" );
 
    /*** set color to white ***/
    $color->setColor( "white" );
 
    /*** the text for the image ***/
    $string = "PHPRO.ORG";
 
    /*** a new draw object ***/
    $draw = new ImagickDraw();
 
    /*** set the draw font to helvetica ***/
    $draw->setFont( "Helvetica" );
 
    /*** loop over the text ***/
    for ( $i = 0; $i <= strlen( $string ); $i++ )
    {
        /*** grab a character ***/
        $part = substr( $string, 0, $i );
 
        /*** create a new gif frame ***/
        $aniGif->newImage( 100, 50, $color );
 
        /*** add the character to the image ***/
        $aniGif->annotateImage( $draw, 10, 10, 0, $part );
 
        /*** set the frame delay to 30 ***/
        $aniGif->setImageDelay( 30 );
    }
 
    /*** write the file ***/
    $aniGif->writeImages('tmp/anime.gif', $out);

    echo 'all done';
?>