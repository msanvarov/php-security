<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class XssEscapingTest extends TestCase
{
    public function testRawOutputContainsExecutableScriptTag(): void
    {
        $payload = "<script>alert(1)</script>";
        $rendered = "<p>$payload</p>";

        $this->assertStringContainsString('<script>', $rendered);
    }

    public function testEscapedOutputNeutralisesScriptTag(): void
    {
        $payload = "<script>alert(1)</script>";
        $rendered = '<p>' . e($payload) . '</p>';

        $this->assertStringNotContainsString('<script>', $rendered);
        $this->assertStringContainsString('&lt;script&gt;', $rendered);
    }

    public function testEscapedOutputNeutralisesAttributePayload(): void
    {
        $payload = '" onmouseover="alert(1)';
        $rendered = '<input value="' . e($payload) . '">';

        $this->assertStringNotContainsString('onmouseover="alert(1)', $rendered);
    }
}
