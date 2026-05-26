<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/vendor/yiisoft/yii/framework/yii.php';

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

if (Yii::app() === null) {
    Yii::createConsoleApplication(dirname(__DIR__) . '/protected/config/console-test.php');
}

$db = new CDbConnection('sqlite::memory:');
$db->active = true;
Yii::app()->setComponent('db', $db);

$schemaSql = [
    'CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, description TEXT, isbn TEXT, publish_year INTEGER, published_at TEXT, cover_path TEXT, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE authors (id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, bio TEXT, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE book_author (book_id INTEGER, author_id INTEGER)',
    'CREATE TABLE author_subscription (id INTEGER PRIMARY KEY AUTOINCREMENT, author_id INTEGER, phone TEXT, phone_normalized TEXT, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE sms_notification_log (id INTEGER PRIMARY KEY AUTOINCREMENT, book_id INTEGER, phone TEXT, message TEXT, status TEXT, error_text TEXT, created_at TEXT, updated_at TEXT)',
    'CREATE TABLE users (id INTEGER PRIMARY KEY AUTOINCREMENT, login TEXT, password_hash TEXT, created_at TEXT, updated_at TEXT)',
];

foreach ($schemaSql as $sql) {
    $db->createCommand($sql)->execute();
}
