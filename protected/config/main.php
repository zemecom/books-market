<?php

$config = require __DIR__ . '/common.php';
$config['components']['db'] = require __DIR__ . '/database.php';

return $config;
