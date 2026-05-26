<?php

$config = require __DIR__ . '/common.php';
$config['name'] = 'Books Market Test Console';
$config['components']['db'] = require __DIR__ . '/database-test.php';
$config['params']['smsTransport'] = 'fake';
$config['commandMap'] = [
    'migrate' => [
        'class' => 'system.cli.commands.MigrateCommand',
        'migrationPath' => 'application.migrations',
        'migrationTable' => 'migration',
        'connectionID' => 'db',
    ],
];

return $config;
