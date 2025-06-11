<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\Ipv6Rule;

class Ipv6RuleTest extends TestCase
{
    private Ipv6Rule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new Ipv6Rule();
    }

    public function testValidatePassesWithFullIPv6(): void
    {
        $this->assertTrue($this->rule->validate('ip', '2001:0db8:85a3:0000:0000:8a2e:0370:7334', [], []));
    }

    public function testValidatePassesWithCompressedIPv6(): void
    {
        $this->assertTrue($this->rule->validate('ip', '2001:db8::8a2e:370:7334', [], []));
    }

    public function testValidatePassesWithLoopbackIPv6(): void
    {
        $this->assertTrue($this->rule->validate('ip', '::1', [], []));
    }

    public function testValidateFailsWithInvalidIPv6(): void
    {
        $this->assertFalse($this->rule->validate('ip', '2001:0db8:85a3:0000:0000:8a2e:0370:733g', [], []));
    }

    public function testValidateFailsWithIPv4(): void
    {
        $this->assertFalse($this->rule->validate('ip', '192.168.1.1', [], []));
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
        $this->assertFalse($this->rule->validate('ip', ['2001:db8::8a2e:370:7334'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('ip', new \stdClass(), [], []));
    }

    public function testValidateFailsWithNonIpString(): void
    {
        $this->assertFalse($this->rule->validate('ip', 'not.an.ip', [], []));
    }

    public function testValidateFailsWithPartialIPv6(): void
    {
        $this->assertFalse($this->rule->validate('ip', '2001:db8:', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('ip', [], null);
        $this->assertEquals('The :attribute must be a valid IPv6 address.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('ip', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}