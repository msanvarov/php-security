# PHP Security

A small, runnable reference for the web vulnerabilities that show up most often in PHP code. Every topic has three files:

- `vulnerable.php` — the smallest reproduction of the flaw.
- `fixed.php` — the minimal change that fixes it.
- `README.md` — what the attack does, why the fix works, and the rules of thumb that fall out of it.

The goal is the kind of code you can read end-to-end in a minute and then apply to your own app.

[![CI](https://github.com/msanvarov/php-security/actions/workflows/ci.yml/badge.svg)](https://github.com/msanvarov/php-security/actions/workflows/ci.yml)

## Topics

| Topic | What it covers |
| --- | --- |
| [SQL injection](examples/sql-injection/) | String interpolation in queries vs. prepared statements |
| [XSS](examples/xss/) | Raw output of user input vs. `htmlspecialchars` at the boundary |
| [CSRF](examples/csrf/) | Unauthenticated state-changing POSTs vs. session-bound tokens |
| [Password hashing](examples/password/) | `md5` vs. `password_hash` / `password_verify` / `password_needs_rehash` |
| [Error disclosure](examples/errors/) | `display_errors` in production vs. log-only with a generic 500 |
| [Cookie flags](examples/cookies/) | Default cookies vs. `Secure` / `HttpOnly` / `SameSite` |
| [Session hijacking](examples/session/) | Static session IDs vs. regenerated IDs with hardened cookies |
| [File uploads](examples/file-upload/) | Trusted client filename/MIME vs. content sniffing + safe storage |
| [Directory listing](examples/directory-listing/) | `Options +Indexes` vs. `-Indexes` |

Pick a topic and open its README — each one stands on its own.

## Repo layout

```
.
├── examples/             one folder per topic, each with vulnerable.php + fixed.php + README
├── src/                  small shared helpers used by the examples (db, csrf, escaping)
├── db/                   SQL schema for the demo database
├── docker/               Dockerfile for the lab web server
├── tests/                PHPUnit tests that exercise the underlying behaviors
├── docker-compose.yml    runs PHP + MariaDB so the examples actually work
├── composer.json         autoload + phpunit
└── .github/workflows/    CI: lint + tests on PHP 8.1, 8.2, 8.3
```

## Running the lab

You need Docker.

```sh
docker compose up
```

That brings up Apache + PHP 8.3 on `http://localhost:8080` and MariaDB on `localhost:3306`. The schema in `db/schema.sql` is loaded on first boot.

Open any example in the browser:

- `http://localhost:8080/examples/sql-injection/vulnerable.php`
- `http://localhost:8080/examples/sql-injection/fixed.php`

…and the same pattern for every other topic.

## Running the tests

```sh
composer install
composer test
```

The tests don't need the lab; they use SQLite in-memory and pure-PHP helpers, so they run anywhere PHP 8.1+ is installed. CI runs them on every push.

## A warning about the `vulnerable.php` files

They are vulnerable on purpose. Don't deploy them anywhere a real user can reach them, and don't lift the patterns out of context — the surrounding lab assumes localhost, throwaway data, and an audience that's trying to break things.

## Contributing

See [CONTRIBUTING.md](CONTRIBUTING.md). New topics are welcome; the bar is that the vulnerable file is genuinely vulnerable and the fix is the smallest thing that closes it.

## License

[MIT](LICENSE).

## References

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP: The Right Way — Security](https://phptherightway.com/#security)
- [OWASP Cheat Sheets](https://cheatsheetseries.owasp.org/)
