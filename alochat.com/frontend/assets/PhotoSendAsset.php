<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class PhotoSendAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/fileinput.css' ,
        'css/photo_gallery.css',

    ];

    public $js = [
        'js/fileinput_send.js',
        'js/photo_gallery.js'


    ];
    public $depends = [
        'frontend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
