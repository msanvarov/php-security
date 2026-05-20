<?php
declare(strict_types=1);

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_verify(string $token): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    $expected = $_SESSION['_csrf'] ?? '';
    if ($expected === '' || !hash_equals($expected, $token)) {
        http_response_code(419);
        exit('CSRF token mismatch');
    }
}
