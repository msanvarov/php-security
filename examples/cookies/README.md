# Cookie Flags

Session and auth cookies should be the hardest things in the app to steal. Default cookies are the easiest.

## The attack

`vulnerable.php` calls `setcookie('session', ...)` with no flags. That gives the attacker three doors:

- **Network** — without `Secure`, the cookie ships over plain HTTP on any non-HTTPS page.
- **XSS** — without `HttpOnly`, `document.cookie` is readable from JavaScript. Any reflected or stored XSS exfiltrates the session.
- **CSRF** — without `SameSite`, the browser attaches the cookie to cross-origin POST requests, so a malicious page can act as the logged-in user.

## The fix

`fixed.php` sets all three flags. Pick `SameSite=Strict` if your app never needs to be linked to from another site mid-session; `Lax` is the safe default for most apps.

## Rules of thumb

- `Secure`, `HttpOnly`, and `SameSite` on every cookie carrying identity (session ID, remember-me token, CSRF token).
- Set `session.cookie_secure=1`, `session.cookie_httponly=1`, `session.cookie_samesite=Lax` in `php.ini` so the PHP session cookie inherits the same flags.
- Prefer host-only cookies (don't set `domain`) unless you actually need to share across subdomains.
- Pair these flags with the CSRF token from [`examples/csrf/`](../csrf/) — they're complementary, not alternatives.
