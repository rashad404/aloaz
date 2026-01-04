<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class DiscoveryAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/bootstrap-slider.css',
    ];

    public $js = [
        'js/bootstrap-slider.js',
        'js/discovery.js',
        'js/get_cities.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset',
    ];
}
