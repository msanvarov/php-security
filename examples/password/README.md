# Password Hashing

Passwords should never be stored in a form a database breach can reverse. Fast hashes (md5, sha1, sha256) without a salt fall to rainbow tables in milliseconds; even with a salt they fall to GPU brute force.

## The attack

`vulnerable.php` stores `md5($password)`. The password `password` becomes `5f4dcc3b5aa765d61d8327deb882cf99` — a value that appears in every leaked rainbow table on the internet. Anyone who reads the `users` table reads the plaintext passwords.

## The fix

`fixed.php` uses `password_hash($password, PASSWORD_DEFAULT)`. That:

- generates a unique salt per password, so identical passwords get different hashes;
- picks a slow algorithm (bcrypt today) with a tunable cost factor;
- encodes the algorithm and cost into the stored string, so old hashes keep verifying even after you raise the cost.

`login.php` uses `password_verify()` (constant-time comparison) and `password_needs_rehash()` to upgrade old hashes when the user next signs in.

## Rules of thumb

- Use `password_hash` / `password_verify`. Don't roll your own with `hash()` and a custom salt.
- Don't pass your own salt to `password_hash` — the deprecated salt parameter only made it possible to ship a bad one.
- Set the bcrypt cost (`['cost' => 12]`) so a single hash takes ~100ms on your hardware. Re-tune every few years.
- For new systems, consider `PASSWORD_ARGON2ID` if it's available; it's the OWASP first-choice algorithm.
- Return the same error for "no such user" and "wrong password" so you don't leak which accounts exist.
