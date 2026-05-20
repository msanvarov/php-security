# Cross-Site Request Forgery (CSRF)

A CSRF attack tricks an authenticated browser into making a state-changing request to a site the user is logged into. The browser attaches the session cookie automatically, so the server can't tell the difference from a legitimate click.

## The attack

`vulnerable.php` accepts any POST as authentic. `attacker.html` is the kind of page an attacker would host elsewhere — when a logged-in user visits it, the form auto-submits and the account gets deleted.

To reproduce locally, with the lab running on `http://localhost:8080`:

1. Open `http://localhost:8080/examples/csrf/vulnerable.php` to establish a session.
2. Open `examples/csrf/attacker.html` from disk (file://). The hidden form posts to the vulnerable endpoint and the account is deleted.

## The fix

`fixed.php` calls `csrf_verify()` (see [`src/csrf.php`](../../src/csrf.php)) before mutating state. The token is stored in the session and embedded in the form as a hidden field. An attacker hosting a form on a different origin cannot read the token (same-origin policy blocks cross-site reads), so the forged submission fails.

`hash_equals` is used for the comparison so the verification runs in constant time and doesn't leak the expected token through timing differences.

## Rules of thumb

- Tokens belong on every state-changing request (POST/PUT/PATCH/DELETE). GET should never mutate state.
- Set `SameSite=Lax` (or `Strict`) on session cookies — see [`examples/cookies/fixed.php`](../cookies/fixed.php). It blocks the common cross-site POST case and is a defense-in-depth layer behind the token.
- Don't rely on `Referer` or `Origin` checks alone; both can be missing or spoofed in older clients. Use them as an extra signal, not the primary defense.
