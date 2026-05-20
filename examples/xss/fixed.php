<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';

$pdo = db();

function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO comments (name, body) VALUES (:name, :body)');
    $stmt->execute([
        'name' => $_POST['name'] ?? '',
        'body' => $_POST['body'] ?? '',
    ]);
    header('Location: fixed.php');
    exit;
}

$comments = $pdo->query('SELECT name, body FROM comments ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<title>XSS (fixed)</title>
<form method="post">
  <label>Name <input name="name"></label>
  <label>Comment <textarea name="body" rows="3"></textarea></label>
  <button type="submit">Post</button>
</form>
<hr>
<?php foreach ($comments as $c): ?>
  <article>
    <strong><?= e($c['name']) ?></strong>
    <p><?= e($c['body']) ?></p>
  </article>
<?php endforeach; ?>
