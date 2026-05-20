<?php
declare(strict_types=1);

setcookie('session', bin2hex(random_bytes(16)), [
    'expires' => time() + 86400,
    'path' => '/',
    // Set only over HTTPS in production. The lab runs HTTP for simplicity.
    'secure' => isset($_SERVER['HTTPS']),
    // Not readable from JavaScript. Limits the blast radius of any XSS.
    'httponly' => true,
    // Blocks cross-site POST attacks (see ../csrf/).
    'samesite' => 'Lax',
]);

echo 'Cookie set with Secure, HttpOnly, SameSite=Lax.';
