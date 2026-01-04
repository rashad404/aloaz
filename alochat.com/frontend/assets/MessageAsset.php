<?php


namespace frontend\assets;

use yii\web\AssetBundle;


class MessageAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        //'css/message.css',
        'css/messages1.css',
        'css/wink.css',
       ];
    public $js = [
        'js/chat_mobile.js',
    ];
    public $depends = [
        'frontend\assets\AppAsset'
    ];
}
