<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\Ipv4Rule;

class Ipv4RuleTest extends TestCase
{
    private Ipv4Rule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new Ipv4Rule();
    }

    public function testValidatePassesWithValidIPv4(): void
    {
        $this->assertTrue($this->rule->validate('ip', '192.168.1.1', [], []));
    }

    public function testValidatePassesWithPrivateIPv4(): void
    {
        $this->assertTrue($this->rule->validate('ip', '10.0.0.1', [], []));
    }

    public function testValidatePassesWithLoopbackIPv4(): void
    {
        $this->assertTrue($this->rule->validate('ip', '127.0.0.1', [], []));
    }

    public function testValidateFailsWithInvalidIPv4(): void
    {
        $this->assertFalse($this->rule->validate('ip', '256.1.2.3', [], []));
    }

    public function testValidateFailsWithIPv6(): void
    {
        $this->assertFalse($this->rule->validate('ip', '2001:0db8:85a3:0000:0000:8a2e:0370:7334', [], []));
    }

    public function testValidateFailsWithCompressedIPv6(): void
    {
        $this->assertFalse($this->rule->validate('ip', '2001:db8::8a2e:370:7334', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('ip', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('ip', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('ip', ['192.168.1.1'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('ip', new \stdClass(), [], []));
    }

    public function testValidateFailsWithNonIpString(): void
    {
        $this->assertFalse($this->rule->validate('ip', 'not.an.ip', [], []));
    }

    public function testValidateFailsWithPartialIPv4(): void
    {
        $this->assertFalse($this->rule->validate('ip', '192.168.1', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('ip', [], null);
        $this->assertEquals('The :attribute must be a valid IPv4 address.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('ip', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}