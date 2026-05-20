<?php
declare(strict_types=1);

session_start();

// Default session cookie has no Secure/HttpOnly/SameSite and the ID is never
// regenerated. An attacker who learns the session ID before login (session
// fixation) keeps it after the user signs in.
if (($_POST['name'] ?? '') !== '') {
    $_SESSION['user'] = $_POST['name'];
    echo "Welcome {$_SESSION['user']}. Session ID is still: " . session_id();
    exit;
}
?>
<!doctype html>
<title>Session (vulnerable)</title>
<form method="post">
  <label>Name <input name="name"></label>
  <button type="submit">Sign in</button>
</form>
<p>Session ID: <?= htmlspecialchars(session_id(), ENT_QUOTES) ?></p>
