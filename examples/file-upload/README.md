# File Uploads

Two things go wrong with naive file uploads:

1. **Path traversal / arbitrary write** — the client-supplied filename ends up on disk verbatim, possibly with `../` segments.
2. **Remote code execution** — a `.php` file lands inside the document root, and the next request executes it.

## The attack

`vulnerable.php` calls `move_uploaded_file($tmp, "$uploadDir/$name")` with the client name. Upload a file called `shell.php` with this content:

```php
<?php system($_GET['c']);
```

Then request `…/uploads/shell.php?c=id`. The web server hands the file to the PHP interpreter and you have remote code execution on the host.

## The fix

`fixed.php`:

- stores uploads outside the document root (so PHP/Apache won't execute them even if one slips through);
- enforces a maximum size;
- detects the MIME from file contents with `finfo`, ignores the client-supplied type;
- accepts only an allowlist of MIME types;
- generates the storage filename itself, so the client can't influence the path.

## Rules of thumb

- Store uploads outside the document root. Serve them through a controller that authorizes the request, not as static files.
- Allowlist MIME types and extensions; don't blocklist. New executable extensions are added all the time (`.phtml`, `.phar`, `.htaccess`).
- Validate the MIME from the file itself with `finfo`. The `$_FILES['file']['type']` value is whatever the client put in the request.
- Never use any part of `$_FILES['file']['name']` in the storage path. Generate the name yourself; if you need the original for display, store it separately.
- Cap size with `MAX_FILE_SIZE`, `upload_max_filesize`, and a server-side check.
