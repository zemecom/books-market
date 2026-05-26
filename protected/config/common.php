<?php

$env = require __DIR__ . '/env.php';

return [
    'basePath' => dirname(__DIR__),
    'name' => $env['appName'],
    'preload' => ['log'],
    'import' => [
        'application.components.*',
        'application.controllers.*',
        'application.domain.exceptions.*',
        'application.domain.services.*',
        'application.domain.valueObjects.*',
        'application.forms.*',
        'application.models.*',
        'application.repositories.*',
        'application.services.author.*',
        'application.services.book.*',
        'application.services.dto.*',
        'application.services.notification.*',
        'application.services.query.*',
        'application.services.report.*',
        'application.services.storage.*',
    ],
    'components' => [
        'user' => [
            'allowAutoLogin' => false,
            'loginUrl' => ['site/login'],
        ],
        'authManager' => [
            'class' => 'CDbAuthManager',
            'connectionID' => 'db',
            'itemTable' => 'authitem',
            'itemChildTable' => 'authitemchild',
            'assignmentTable' => 'authassignment',
            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => [
                '' => 'book/index',
                'login' => 'site/login',
                'logout' => 'site/logout',
                'report/top-authors' => 'report/topAuthors',
                'authors/<id:\d+>' => 'author/view',
                'books/<id:\d+>' => 'book/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        'services' => [
            'class' => 'ServiceContainer',
        ],
        'clock' => [
            'class' => 'Clock',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'log' => [
            'class' => 'CLogRouter',
            'routes' => [
                [
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ],
            ],
        ],
    ],
    'params' => [
        'appUrl' => $env['appUrl'],
        'coverUploadDir' => $env['coverUploadDir'],
        'smsTransport' => $env['smsTransport'],
        'smsPilotApiKey' => $env['smsPilotApiKey'],
        'smsPilotSender' => $env['smsPilotSender'],
        'smsPilotTestMode' => $env['smsPilotTestMode'],
        'adminLogin' => $env['adminLogin'],
        'adminPassword' => $env['adminPassword'],
        'userLogin' => $env['userLogin'],
        'userPassword' => $env['userPassword'],
    ],
];
