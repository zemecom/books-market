<?php

$yii = dirname(__DIR__) . '/vendor/yiisoft/yii/framework/yii.php';
$isTest = isset($_SERVER['HTTP_X_APP_ENV']) && $_SERVER['HTTP_X_APP_ENV'] === 'test';
$config = dirname(__DIR__) . '/protected/config/' . ($isTest ? 'test.php' : 'main.php');

defined('YII_DEBUG') or define('YII_DEBUG', getenv('APP_ENV') !== 'prod' || $isTest);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', YII_DEBUG ? 3 : 0);

require_once $yii;
Yii::createWebApplication($config)->run();
