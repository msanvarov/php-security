<?php
declare(strict_types=1);

// Cookie sent over plain HTTP, readable by JavaScript, no SameSite.
// An XSS or a network attacker on the same Wi-Fi can steal it.
setcookie('session', bin2hex(random_bytes(16)), [
    'expires' => time() + 86400,
]);

echo 'Cookie set without any hardening.';
