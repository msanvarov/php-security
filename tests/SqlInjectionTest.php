<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class SqlInjectionTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec('CREATE TABLE users (id INTEGER PRIMARY KEY, email TEXT)');
        $this->pdo->exec("INSERT INTO users (email) VALUES ('alice@example.com'), ('bob@example.com')");
    }

    public function testInterpolationReturnsEveryRowForTautologyPayload(): void
    {
        $payload = "' OR '1'='1";
        $sql = "SELECT id FROM users WHERE email = '$payload'";

        $rows = $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(2, $rows, 'tautology payload should leak every row');
    }

    public function testPreparedStatementTreatsPayloadAsLiteral(): void
    {
        $payload = "' OR '1'='1";
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute(['email' => $payload]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(0, $rows, 'prepared statement should treat payload as a literal value');
    }

    public function testPreparedStatementStillMatchesLegitimateInput(): void
    {
        $stmt = $this->pdo->prepare('SELECT id FROM users WHERE email = :email');
        $stmt->execute(['email' => 'alice@example.com']);

        $this->assertCount(1, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
