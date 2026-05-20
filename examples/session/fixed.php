<?php
declare(strict_types=1);

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

if (($_POST['name'] ?? '') !== '') {
    // Rotate the ID on privilege change so a pre-login fixation token is useless.
    session_regenerate_id(true);

    $_SESSION['user'] = $_POST['name'];
    $_SESSION['fingerprint'] = hash('sha256', ($_SERVER['HTTP_USER_AGENT'] ?? '') . '|' . session_id());
    echo "Welcome {$_SESSION['user']}. New session ID: " . session_id();
    exit;
}
?>
<!doctype html>
<title>Session (fixed)</title>
<form method="post">
  <label>Name <input name="name"></label>
  <button type="submit">Sign in</button>
</form>
<p>Session ID: <?= htmlspecialchars(session_id(), ENT_QUOTES) ?></p>
