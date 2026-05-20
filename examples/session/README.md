# Session Hijacking and Fixation

A session ID is a bearer token: whoever sends it is the user. Two common ways to lose one:

- **Hijacking** — the ID leaks through XSS, an unencrypted link, or a shared device.
- **Fixation** — the attacker plants a known session ID in the victim's browser before they sign in, then reuses it after authentication.

## The attack

`vulnerable.php` keeps the same session ID across the unauthenticated → authenticated transition. An attacker who pre-seeds a session ID (e.g. via a crafted URL on a site that accepts ID-in-URL, or via a stolen cookie) stays authenticated as the victim.

## The fix

`fixed.php` does three things:

1. Sets `Secure`, `HttpOnly`, `SameSite=Lax` on the session cookie.
2. Calls `session_regenerate_id(true)` immediately after login so any pre-login ID is destroyed.
3. Binds a fingerprint (user agent + session ID hash) to the session as a soft sanity check.

## Rules of thumb

- Regenerate the session ID on every privilege boundary: login, logout, role change, password reset, MFA challenge.
- Set the cookie flags from [`examples/cookies/`](../cookies/) on the session cookie too.
- Don't put session IDs in URLs (`session.use_only_cookies=1`). They leak through `Referer`, browser history, and server access logs.
- Have a server-side session table you can revoke. Stateless JWTs you can't invalidate are a footgun for sessions.
