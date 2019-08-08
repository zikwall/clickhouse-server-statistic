<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$rules = require __DIR__ . '/rules.php';

use app\modules\rest\components\ResponseHandler;
use app\modules\rest\components\RequestHandler;

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\modules\rest\Bootstrap', 'app\modules\user\Bootstrap'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        'user' => [
            'class' => 'app\modules\user\Module',
        ],
        'rest' => [
            'class' => 'app\modules\rest\Module'
        ]
    ],
    'components' => [
        'clickhouse' => [
            'class' => 'app\modules\clickhouse\components\Connection',
        ],
        'session' => [
            'class' => 'app\modules\user\components\Session',
            'sessionTable' => 'new_user_http_session',
            'useCookies' => true,
        ],
        'settings' => [
            'class' => 'app\modules\core\components\managers\SettingsManager',
            'moduleId' => 'base',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '1WOSDcifLy4yK5IUW4MizdoNIyydzxsC',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => '\yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                (new ResponseHandler())->beforeSend($event->sender);
            },
            'on afterSend' => function ($event) {
                (new ResponseHandler())->afterSend($event->sender);
            },
        ],
        'on beforeRequest' => function () {
            (new RequestHandler())->beforeSend();
        },
        'on afterRequest' => function () {
            (new RequestHandler())->afterSend();
        },
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'enableSession' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => $rules,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
