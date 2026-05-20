<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';
session_start();
$_SESSION['user'] ??= 'demo-user';

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // No token check, no SameSite cookie, no Origin/Referer check.
    // Any cross-site form auto-submitted while the user is logged in works.
    $stmt = $pdo->prepare('DELETE FROM users WHERE name = :name');
    $stmt->execute(['name' => $_SESSION['user']]);
    echo "Deleted account for {$_SESSION['user']}";
    exit;
}
?>
<!doctype html>
<title>CSRF (vulnerable)</title>
<form method="post">
  <button type="submit">Delete my account</button>
</form>
