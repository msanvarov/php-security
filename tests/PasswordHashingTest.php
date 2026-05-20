<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class PasswordHashingTest extends TestCase
{
    public function testMd5IsDeterministicAcrossUsers(): void
    {
        $this->assertSame(md5('hunter2'), md5('hunter2'), 'md5 leaks identical passwords');
    }

    public function testBcryptProducesDifferentHashesForSamePassword(): void
    {
        $a = password_hash('hunter2', PASSWORD_BCRYPT);
        $b = password_hash('hunter2', PASSWORD_BCRYPT);

        $this->assertNotSame($a, $b, 'bcrypt salt makes identical passwords look different');
    }

    public function testPasswordVerifyAcceptsCorrectPassword(): void
    {
        $hash = password_hash('hunter2', PASSWORD_BCRYPT);

        $this->assertTrue(password_verify('hunter2', $hash));
        $this->assertFalse(password_verify('wrong', $hash));
    }

    public function testNeedsRehashFlagsLowerCost(): void
    {
        $low = password_hash('hunter2', PASSWORD_BCRYPT, ['cost' => 10]);

        $this->assertTrue(
            password_needs_rehash($low, PASSWORD_BCRYPT, ['cost' => 12]),
            'cost-10 hash must be flagged for upgrade against cost-12'
        );
    }
}
