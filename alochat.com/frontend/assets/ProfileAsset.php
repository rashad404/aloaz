<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class ProfileAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap-slider.css',
    ];
    public $js = [
        'js/get_cities.js',
        'js/bootstrap-slider.js',

    ];
    public $depends = [
        'frontend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
