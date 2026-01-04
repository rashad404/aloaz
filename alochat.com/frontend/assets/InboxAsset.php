<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class InboxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/inbox.css',
    ];
    public $js = [
        'js/chat.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset'
    ];
}
