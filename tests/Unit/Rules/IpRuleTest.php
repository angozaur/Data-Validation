<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\IpRule;

class IpRuleTest extends TestCase
{
    private IpRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new IpRule();
    }

    public function testValidatePassesWithIPv4(): void
    {
        $this->assertTrue($this->rule->validate('ip', '192.168.1.1', [], []));
    }

    public function testValidatePassesWithIPv6(): void
    {
        $this->assertTrue($this->rule->validate('ip', '2001:0db8:85a3:0000:0000:8a2e:0370:7334', [], []));
    }

    public function testValidatePassesWithCompressedIPv6(): void
    {
        $this->assertTrue($this->rule->validate('ip', '2001:db8::8a2e:370:7334', [], []));
    }

    public function testValidateFailsWithInvalidIPv4(): void
    {
        $this->assertFalse($this->rule->validate('ip', '256.1.2.3', [], []));
    }

    public function testValidateFailsWithInvalidIPv6(): void
    {
        $this->assertFalse($this->rule->validate('ip', '2001:0db8:85a3:0000:0000:8a2e:0370:733g', [], []));
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
        $this->assertEquals('The :attribute must be a valid IP address.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('ip', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}