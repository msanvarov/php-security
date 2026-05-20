<?php
declare(strict_types=1);

require __DIR__ . '/../../src/db.php';

$pdo = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO comments (name, body) VALUES (:name, :body)');
    $stmt->execute([
        'name' => $_POST['name'] ?? '',
        'body' => $_POST['body'] ?? '',
    ]);
    header('Location: vulnerable.php');
    exit;
}

$comments = $pdo->query('SELECT name, body FROM comments ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<title>XSS (vulnerable)</title>
<form method="post">
  <label>Name <input name="name"></label>
  <label>Comment <textarea name="body" rows="3"></textarea></label>
  <button type="submit">Post</button>
</form>
<hr>
<?php foreach ($comments as $c): ?>
  <article>
    <!-- Raw output: any <script>, <img onerror>, or <iframe> in the body executes
         in the browser of every visitor who loads this page. -->
    <strong><?= $c['name'] ?></strong>
    <p><?= $c['body'] ?></p>
  </article>
<?php endforeach; ?>
