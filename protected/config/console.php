<?php

$config = require __DIR__ . '/common.php';
$config['name'] = 'Books Market Console';
$config['components']['db'] = require __DIR__ . '/database.php';
$config['commandMap'] = [
    'fixture' => [
        'class' => 'application.commands.FixtureCommand',
    ],
    'migrate' => [
        'class' => 'system.cli.commands.MigrateCommand',
        'migrationPath' => 'application.migrations',
        'migrationTable' => 'migration',
        'connectionID' => 'db',
    ],
];

return $config;
