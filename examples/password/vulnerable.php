<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';

$pdo = db();
$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    // md5 is fast, unsalted, and has known collisions. Rainbow tables crack
    // common passwords in milliseconds.
    $hash = md5($password);

    $stmt = $pdo->prepare(
        'INSERT INTO users (email, name, password) VALUES (:email, :name, :password)
         ON DUPLICATE KEY UPDATE password = VALUES(password)'
    );
    $stmt->execute([
        'email' => "$name@example.com",
        'name' => $name,
        'password' => $hash,
    ]);

    $message = "Stored md5 hash: $hash";
}
?>
<!doctype html>
<title>Password hashing (vulnerable)</title>
<form method="post" autocomplete="off">
  <label>Name <input name="name"></label>
  <label>Password <input type="password" name="password"></label>
  <button type="submit">Register</button>
</form>
<?php if ($message): ?><p><?= htmlspecialchars($message, ENT_QUOTES) ?></p><?php endif; ?>
