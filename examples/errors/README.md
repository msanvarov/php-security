# Error Disclosure

Verbose error pages are extremely useful in development and extremely dangerous in production. A stack trace tells an attacker the database driver, hostname, credentials format, file paths, and library versions — which feeds the next attack.

## The attack

`vulnerable.php` has `display_errors=1`. Visiting it during a database outage prints something like:

```
PDOException: SQLSTATE[HY000] [1045] Access denied for user 'root'@'10.0.0.5'
(using password: YES) in /var/www/examples/errors/vulnerable.php:8
```

That single line gives an attacker the database user, the host the app runs on, and the file layout.

## The fix

`fixed.php`:

- turns off `display_errors`,
- turns on `log_errors` to a file outside the web root,
- registers an exception handler that logs the full detail and shows the user a generic 500.

## Rules of thumb

- `display_errors=0` and `log_errors=1` in every production `php.ini`. Treat the default as a configuration bug.
- Send the log to a file outside the document root, or to syslog/a log aggregator.
- Don't render `Throwable::getMessage()` to the user — even sanitized messages leak the driver and operation.
- In a framework, set the equivalent (`APP_DEBUG=false`, `app.debug = false`) before deploying.
