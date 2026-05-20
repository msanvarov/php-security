<?php
declare(strict_types=1);

$uploadDir = __DIR__ . '/uploads';
@mkdir($uploadDir, 0755, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Trusts the client-supplied filename and MIME type.
    // Upload a file named "shell.php" and it lands inside the document root,
    // ready to execute on the next request.
    $name = $_FILES['file']['name'];
    move_uploaded_file($_FILES['file']['tmp_name'], "$uploadDir/$name");
    echo "Saved as $uploadDir/$name";
    exit;
}
?>
<!doctype html>
<title>File upload (vulnerable)</title>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="file">
  <button type="submit">Upload</button>
</form>
