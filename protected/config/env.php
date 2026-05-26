<?php

require_once dirname(__DIR__) . '/components/Env.php';

return [
    'appName' => getenv('APP_NAME') ?: 'Books Market',
    'appUrl' => getenv('APP_URL') ?: 'http://localhost:8080',
    'dbHost' => getenv('DB_HOST') ?: '127.0.0.1',
    'dbPort' => getenv('DB_PORT') ?: '3306',
    'dbName' => getenv('DB_NAME') ?: 'books_catalog',
    'dbTestName' => getenv('DB_TEST_NAME') ?: 'books_catalog_test',
    'dbUser' => getenv('DB_USER') ?: 'app',
    'dbPassword' => getenv('DB_PASSWORD') ?: 'app',
    'adminLogin' => getenv('ADMIN_LOGIN') ?: 'admin',
    'adminPassword' => getenv('ADMIN_PASSWORD') ?: 'admin123',
    'userLogin' => getenv('USER_LOGIN') ?: 'user',
    'userPassword' => getenv('USER_PASSWORD') ?: 'user123',
    'smsTransport' => getenv('SMS_TRANSPORT') ?: 'fake',
    'smsPilotApiKey' => getenv('SMSPILOT_API_KEY') ?: '',
    'smsPilotSender' => getenv('SMSPILOT_SENDER') ?: '',
    'smsPilotTestMode' => Env::bool('SMSPILOT_TEST'),
    'coverUploadDir' => getenv('COVER_UPLOAD_DIR') ?: dirname(__DIR__, 2) . '/public/uploads',
];
