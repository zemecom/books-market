<?php

$env = require __DIR__ . '/env.php';

return [
    'connectionString' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s',
        $env['dbHost'],
        $env['dbPort'],
        $env['dbName'],
    ),
    'emulatePrepare' => true,
    'username' => $env['dbUser'],
    'password' => $env['dbPassword'],
    'charset' => 'utf8mb4',
    'tablePrefix' => '',
    'enableParamLogging' => YII_DEBUG,
    'enableProfiling' => YII_DEBUG,
];
