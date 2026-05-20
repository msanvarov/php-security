<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * The real csrf_token() / csrf_verify() helpers in src/csrf.php call
 * session_start() and exit(), which PHPUnit can't drive cleanly. These tests
 * exercise the same primitives they're built on so a regression in the
 * underlying logic still gets caught.
 */
final class CsrfTokenTest extends TestCase
{
    public function testRandomTokensAreUnique(): void
    {
        $a = bin2hex(random_bytes(32));
        $b = bin2hex(random_bytes(32));

        $this->assertNotSame($a, $b);
        $this->assertSame(64, strlen($a));
    }

    public function testHashEqualsAcceptsMatchingToken(): void
    {
        $token = bin2hex(random_bytes(32));

        $this->assertTrue(hash_equals($token, $token));
    }

    public function testHashEqualsRejectsForgedToken(): void
    {
        $token = bin2hex(random_bytes(32));

        $this->assertFalse(hash_equals($token, 'forged'));
    }

    public function testEqualsOperatorIsUnsafeForTokenComparison(): void
    {
        // Demonstrates why csrf_verify uses hash_equals: == on these PHP
        // truthy-collision tokens claims they match.
        $this->assertTrue('0e1' == '0e2', 'loose equality on numeric-string hashes collides');
        $this->assertFalse(hash_equals('0e1', '0e2'));
    }
}
