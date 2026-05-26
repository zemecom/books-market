<?php

$env = require __DIR__ . '/env.php';

return [
    'connectionString' => sprintf(
        'mysql:host=%s;port=%s;dbname=%s',
        $env['dbHost'],
        $env['dbPort'],
        $env['dbTestName'],
    ),
    'emulatePrepare' => true,
    'username' => $env['dbUser'],
    'password' => $env['dbPassword'],
    'charset' => 'utf8mb4',
    'tablePrefix' => '',
    'enableParamLogging' => false,
    'enableProfiling' => false,
];
