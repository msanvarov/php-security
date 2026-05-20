<?php
declare(strict_types=1);

// In production: log everything, show nothing.
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/../../var/php-errors.log');
error_reporting(E_ALL);

set_exception_handler(function (Throwable $e): void {
    error_log((string) $e);
    http_response_code(500);
    echo "Sorry, something went wrong. Please try again later.";
});

try {
    $pdo = new PDO('mysql:host=db;dbname=does_not_exist', 'root', 'wrong-password');
} catch (Throwable $e) {
    // Rethrow so the handler above logs the detail and the user gets a generic page.
    throw $e;
}
