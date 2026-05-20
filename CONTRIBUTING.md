# Contributing

Thanks for taking the time to improve this repo. A few ground rules.

## Scope

This is a teaching repo. Examples should be:

- **Minimal** — the smallest amount of code that shows the vulnerability and the fix side by side.
- **Self-contained** — a reader should be able to look at one folder and understand the topic.
- **Honest** — the `vulnerable.php` file actually has the vulnerability; the `fixed.php` file actually fixes it. No hand-waving.

Real-world apps need depth-in-defense (CSP, WAFs, rate limiting, monitoring). Examples here stay narrow on purpose; defense layers go in the topic README under "Rules of thumb".

## Adding a new topic

```
examples/<topic>/
    README.md       # what the vulnerability is, how to reproduce, how to fix
    vulnerable.php  # the smallest reproduction
    fixed.php       # the minimal fix
```

Then:

1. Add a row to the topic table in the top-level `README.md`.
2. If the example needs a new DB table, extend `db/schema.sql`.
3. If the fix can be unit-tested without a browser, add a test under `tests/`.

## Running the lab

```sh
docker compose up
```

Then visit `http://localhost:8080/examples/<topic>/vulnerable.php`.

## Running the tests

```sh
composer install
composer test
```

CI runs PHPUnit on PHP 8.1, 8.2, and 8.3.

## Commit style

Plain, imperative subject lines. No gitmoji, no emojis. Examples:

```
add session fixation example
fix typo in xss readme
move sql schema into db/
```
