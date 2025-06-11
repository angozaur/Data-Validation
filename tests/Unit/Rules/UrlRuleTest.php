<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\UrlRule;

class UrlRuleTest extends TestCase
{
    private UrlRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new UrlRule();
    }

    public function testValidatePassesWithHttpUrl(): void
    {
        $this->assertTrue($this->rule->validate('url', 'http://example.com', [], []));
    }

    public function testValidatePassesWithHttpsUrl(): void
    {
        $this->assertTrue($this->rule->validate('url', 'https://www.example.com/path', [], []));
    }

    public function testValidatePassesWithFtpUrl(): void
    {
        $this->assertTrue($this->rule->validate('url', 'ftp://ftp.example.com', [], []));
    }

    public function testValidateFailsWithInvalidUrl(): void
    {
        $this->assertFalse($this->rule->validate('url', 'htp://example', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('url', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('url', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('url', ['http://example.com'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('url', new \stdClass(), [], []));
    }

    public function testValidateFailsWithNonUrlString(): void
    {
        $this->assertFalse($this->rule->validate('url', 'not-a-url', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('url', [], null);
        $this->assertEquals('The :attribute must be a valid URL.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('url', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}