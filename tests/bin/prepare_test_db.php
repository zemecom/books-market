<?php

$host = getenv('DB_HOST') ?: 'db';
$port = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_TEST_NAME') ?: 'books_catalog_test';
$user = getenv('DB_USER') ?: 'app';
$password = getenv('DB_PASSWORD') ?: 'app';

try {
    $pdo = new PDO(sprintf('mysql:host=%s;port=%s;dbname=%s', $host, $port, $dbName), $user, $password);
    $pdo->query('SELECT 1');
} catch (Throwable $exception) {
    fwrite(STDERR, "Skipping test DB prepare: {$exception->getMessage()}\n");
    exit(0);
}

exit(0);
