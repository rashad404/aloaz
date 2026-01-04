<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class PhotoUploadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/fileinput.css' ,
        'css/photo_gallery.css',

    ];

    public $js = [
        'js/fileinput.js',
        'js/photo_gallery.js'


    ];
    public $depends = [
        'frontend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
