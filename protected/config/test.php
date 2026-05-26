<?php

$config = require __DIR__ . '/common.php';
$config['components']['db'] = require __DIR__ . '/database-test.php';
$config['components']['urlManager']['showScriptName'] = true;
$config['params']['smsTransport'] = 'fake';

return $config;
