<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';
require __DIR__ . '/../../src/csrf.php';

session_start();
$_SESSION['user'] ??= 'demo-user';

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify($_POST['_token'] ?? '');

    $stmt = $pdo->prepare('DELETE FROM users WHERE name = :name');
    $stmt->execute(['name' => $_SESSION['user']]);
    echo "Deleted account for {$_SESSION['user']}";
    exit;
}

$token = csrf_token();
?>
<!doctype html>
<title>CSRF (fixed)</title>
<form method="post">
  <input type="hidden" name="_token" value="<?= htmlspecialchars($token, ENT_QUOTES) ?>">
  <button type="submit">Delete my account</button>
</form>
