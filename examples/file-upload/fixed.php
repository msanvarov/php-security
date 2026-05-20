<?php
declare(strict_types=1);

// Storage location lives OUTSIDE the document root, so even if an attacker
// uploads a .php file it can never be executed by the web server.
$uploadDir = __DIR__ . '/../../var/uploads';
@mkdir($uploadDir, 0755, true);

const MAX_BYTES = 2 * 1024 * 1024;
const ALLOWED_MIME = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
];

function fail(string $msg): void
{
    http_response_code(400);
    echo $msg;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file = $_FILES['file'] ?? null;

    if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
        fail('upload failed');
    }
    if ($file['size'] > MAX_BYTES) {
        fail('file too large');
    }

    // Detect the real MIME from the file contents, never trust $file['type'].
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $ext = ALLOWED_MIME[$mime] ?? null;
    if ($ext === null) {
        fail('unsupported file type');
    }

    // Generate the storage name ourselves; never reuse the client-supplied one.
    $storedName = bin2hex(random_bytes(16)) . '.' . $ext;

    if (!move_uploaded_file($file['tmp_name'], "$uploadDir/$storedName")) {
        fail('could not store file');
    }

    echo "Stored as $storedName";
    exit;
}
?>
<!doctype html>
<title>File upload (fixed)</title>
<form method="post" enctype="multipart/form-data">
  <input type="file" name="file" accept="image/jpeg,image/png,image/webp">
  <button type="submit">Upload</button>
</form>
