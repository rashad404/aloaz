<?php

namespace frontend\assets;

use yii\web\View;
use yii\web\AssetBundle;

class FirstPageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/normalize.css',
        'css/first.css',

    ];

    public $js = [
        'https://www.google.com/recaptcha/api.js',
        'js/modernizr.min.js',
        'js/jquery.mousewheel.js',
        'js/jquery.easing.min.js',
        'js/ninjaScroll.js',
        'js/first.js',
        'js/googleAnalytics.js'

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}
