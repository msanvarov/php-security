<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';

$pdo = db();
$found = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';

    // String interpolation in SQL. Submitting:  ' OR '1'='1
    // turns this into:  SELECT * FROM users WHERE email = '' OR '1'='1'
    // which matches every row.
    $sql = "SELECT id, email FROM users WHERE email = '$email'";
    $found = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!doctype html>
<title>SQL injection (vulnerable)</title>
<form method="post" autocomplete="off">
  <label>Email <input name="email" value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>"></label>
  <button type="submit">Look up</button>
</form>
<?php if ($found !== null): ?>
  <p>Matched <?= count($found) ?> row(s).</p>
  <pre><?= htmlspecialchars(print_r($found, true), ENT_QUOTES) ?></pre>
<?php endif; ?>
