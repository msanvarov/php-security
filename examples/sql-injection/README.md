# SQL Injection

An SQL injection happens when untrusted input is concatenated into a query string. The database can't tell user data apart from query syntax, so an attacker can change what the query does.

## The attack

`vulnerable.php` builds its query by interpolation:

```php
$sql = "SELECT id, email FROM users WHERE email = '$email'";
```

Submit this as the email:

```
' OR '1'='1
```

The resulting query is `SELECT id, email FROM users WHERE email = '' OR '1'='1'`, which matches every row in `users`. More aggressive payloads can stack statements (`'; DROP TABLE users; --`) or exfiltrate data through `UNION SELECT`.

## The fix

`fixed.php` uses a prepared statement with a named parameter. The driver sends the SQL and the value separately, so the value is never parsed as SQL:

```php
$stmt = $pdo->prepare('SELECT id, email FROM users WHERE email = :email');
$stmt->execute(['email' => $email]);
```

Same input now returns zero rows, because `' OR '1'='1` is just a literal string compared against the `email` column.

## Rules of thumb

- Always use prepared statements with bound parameters. Don't concatenate user data into SQL, ever.
- Use `PDO::ATTR_EMULATE_PREPARES => false` (see `src/db.php`) so the driver does real server-side prepares.
- If you need a dynamic identifier (table or column name), use an allowlist — parameters can only bind values, not identifiers.
