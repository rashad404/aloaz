<?php

namespace frontend\assets;

use yii\web\View;
use yii\web\AssetBundle;

class HomePageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/home.css',
        'css/ihover.css',
        'css/check-radio.css'

    ];

    public $js = [
        'js/modernizr.min.js',
        'js/jquery.mousewheel.js',
        'js/jquery.easing.min.js',
        'js/googleAnalytics.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
