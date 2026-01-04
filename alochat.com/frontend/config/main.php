<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'language' => 'en',
     //'bootstrap' => ['log'],
    'layout' =>'column2',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl'=>['/'],

        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                  //  'authUrl' => 'https://www.facebook.com/dialog/oauth',
                    'clientId' => '1431496470488744',
                    'clientSecret' => '6495878ccfb8118193a6f009d1018647',
                ],
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => '85215254494-4j6faapas1tcviecbtdhgh0b2plhueng.apps.googleusercontent.com',
                    'clientSecret' => 'dEfMTinBpd__r3HynKePxWCk',

                ],

            ],
        ],
        'request' => [
            'enableCookieValidation' => true,

            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '3EC2T47uvT_EJm6ovvmIhG0W7uKXTOjh',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'pattern' => 'u/<id:\d+>/',
                    'route' => 'profile/index'
                ],

                [
                    'pattern' => 'gift/<id:\d+>/',
                    'route' => 'profile/gifts'
                ],
                [
                    'pattern' => 'profile/add-friend/<id:\d+>/',
                    'route' => 'profile/add-friend'
                ],
                [
                    'pattern' => 'profile/confirm-friend/<id:\d+>/',
                    'route' => 'profile/confirm-friend'
                ],
                [
                    'pattern' => 'profile/like-share/<id:\d+>/',
                    'route' => 'profile/like-share'
                ],
                [
                    'pattern' => 'profile/like-image/<id:\d+>/',
                    'route' => 'profile/like-image'
                ],
                [
                    'pattern' => 'profile/delete-share/<id:\d+>/',
                    'route' => 'profile/delete-share'
                ],
                [
                    'pattern' => 'profile/home/<id:\d+>/',
                    'route' => 'profile/home'
                ],
                [
                    'pattern' => 'profile/post/<id:\d+>/',
                    'route' => 'profile/post'
                ],
                [
                    'pattern' => 'profile/images/<id:\d+>/',
                    'route' => 'profile/images'
                ],
                [
                    'pattern' => 'profile/image/<id:\d+>/',
                    'route' => 'profile/image'
                ],
                [
                    'pattern' => 'site/post/<id:\d+>/',
                    'route' => 'site/post'
                ],
                [
                    'pattern' => 'profile/friends/<id:\d+>/',
                    'route' => 'profile/friends'
                ],
                [
                    'pattern' => 'profile/photos/<id:\d+>/',
                    'route' => 'profile/photos'
                ],
                [
                    'pattern' => 'profile/gifts/<id:\d+>/',
                    'route' => 'profile/gifts'
                ],
                [
                    'pattern' => 'profile/deactive/<id:\d+>/',
                    'route' => 'profile/deactive'
                ],
                [
                    'pattern' => 'profile/timeline/<id:\d+>/',
                    'route' => 'profile/timeline'
                ],

                [
                    'pattern' => 'site/user/<id:\d+>/',
                    'route' => 'site/user'
                ],
                [
                    'pattern' => 'site/login-alo/<id:\d+>/',
                    'route' => 'alo/login-alo'
                ],
                [
                    'pattern' => 'alo/alo-image/<id:\d+>/',
                    'route' => 'alo/alo-image'
                ],
                [
                    'pattern' => 'alo/change-pass/<id:\d+>/',
                    'route' => 'alo/change-pass'
                ],
                [
                    'pattern' => 'coins',
                    'route' => 'coins/index'
                ],
                [
                    'pattern' => 'shares',
                    'route' => 'site/shares'
                ],
                [
                    'pattern' => 'notifications',
                    'route' => 'site/notifications'
                ],
                [
                    'pattern' => 'users',
                    'route' => 'site/users'
                ],
                [
                    'pattern' => 'search',
                    'route' => 'site/search'
                ],
                [
                    'pattern' => 'messages',
                    'route' => 'messages/index'
                ],

                [
                    'pattern' => 'l/<key:.*?>',
                    //'pattern' => 'login/<key:\>/',
                    'route' => 'alo/auto-login'
                ],

                [
                    'pattern' => '/<country:.*?>/site/test/<id:\d+>/',
                    'route' => 'site/test'
                ],
                [
                    'pattern' => 'site/post/<id:\d+>/',
                    'route' => 'site/post'
                ],
                [
                    'pattern' => '/<country:.*?>/site/post/<id:\d+>/',
                    'route' => 'site/post'
                ],
                [
                    'pattern' => 'site/register/',
                    'route' => 'site/register'
                ],
                [
                    'pattern' => '/<country:.*?>/site/register/',
                    'route' => 'site/register'
                ],
                [
                    'pattern' => '/<country:\w+>/',
                    'route' => 'site/index'
                ],

                [
                    'pattern' => '/',
                    'route' => 'site/index'
                ],
                [
                    'pattern' => '/site/index2',
                    'route' => 'site/index2'
                ],
                [
                    'pattern' => '/<country:\w+>/site/index2',
                    'route' => 'site/index2'
                ],

                /*'<country:.*?>/<controller>/<action>' => '<controller>/<action>',*/

            ]
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        'i18n' => [
            'translations' => [
                'app*' => [
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en-US',
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'privacy*' => [
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en-US',
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
                'about*' => [
                    'basePath' => '@frontend/messages',
                    'sourceLanguage' => 'en-US',
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],

        'ipgeobase' => [
            'class' => 'frontend\components\ipgeobase\IpGeoBase'
        ]

    ],

    'modules' => [

        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'] // adjust this to your needs
        ],
    ],

    'params' => $params,


];

if (YII_ENV_DEV) {
    //configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}