# Directory Listing

If the web server is configured with `Options +Indexes` (or with no `index.html`/`index.php` and no `Options -Indexes`), browsing to a directory shows every file in it. Backup files, half-finished scripts, and exported SQL dumps end up indexed by search engines.

## The fix

Drop a `.htaccess` next to any static asset directory:

```apache
Options -Indexes
```

Or, at the virtual host / `<Directory>` level:

```apache
<Directory /var/www/html>
    Options -Indexes
</Directory>
```

On nginx, the equivalent is the absence of `autoindex on;` (it's off by default).

The repo's top-level `.htaccess` shows the minimal config.

## Rules of thumb

- Serve only the document root. Everything else (vendor code, config, uploads, dotfiles) lives outside it.
- Disable directory listing globally, then opt back in for specific directories if you ever actually need it.
- Don't rely on obscurity — `.htaccess` only works if Apache is configured to honor it (`AllowOverride All`). On a misconfigured host the file is silently ignored.
