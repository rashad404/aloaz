<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [

           'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=admin_alochat',
            'username' => 'aloaz_chat', //'admin_admin',
            'password' => '=OMoU{h@kMKo', //'NBxY4vPd',NNG1Q59638cL3
            'charset' => 'utf8',

            'enableSchemaCache' => true,
            'schemaCacheDuration' => 3600,
            'schemaCache' => 'cache',

           /* 'class' => 'yii\db\Connection',
           'dsn' => 'mysql:host=localhost;dbname=alochat',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',*/


        ],

        'session' => [
            'class' => 'yii\web\DbSession',
            'cookieParams' => ['httponly' => true, 'lifetime' => 30*86400],
            'timeout' => 30*86400
            // 'db' => 'mydb',  // the application component ID of the DB connection. Defaults to 'db'.
            // 'sessionTable' => 'my_session', // session table name. Defaults to 'session'.
        ],
        'formatter' => [

            'sizeFormatBase' => 1024,
            'datetimeFormat'=>'php:d-M-Y H:i:s',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'localhost',
                'username' => 'noreply@alochat.com',
                'password' => 'CgNZTb0f',
                'port' => '587',
                'encryption' => 'tls',
            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['user','admin']

        ],

    ],
];
