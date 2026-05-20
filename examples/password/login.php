<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';

$pdo = db();
$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT password FROM users WHERE name = :name');
    $stmt->execute(['name' => $name]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && password_verify($password, $row['password'])) {
        $result = 'authenticated';

        // Rehash if the cost factor has changed since the password was set.
        if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
            $upd = $pdo->prepare('UPDATE users SET password = :password WHERE name = :name');
            $upd->execute(['password' => password_hash($password, PASSWORD_DEFAULT), 'name' => $name]);
        }
    } else {
        // Same message on both branches so we don't tell an attacker which
        // half of the credentials is wrong.
        $result = 'invalid name or password';
    }
}
?>
<!doctype html>
<title>Login</title>
<form method="post" autocomplete="off">
  <label>Name <input name="name"></label>
  <label>Password <input type="password" name="password"></label>
  <button type="submit">Sign in</button>
</form>
<?php if ($result): ?><p><?= htmlspecialchars($result, ENT_QUOTES) ?></p><?php endif; ?>
