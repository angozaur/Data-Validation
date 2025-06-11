<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\StartsWithRule;

class StartsWithRuleTest extends TestCase
{
    private StartsWithRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new StartsWithRule();
    }

    public function testValidatePassesWithSinglePrefix(): void
    {
        $this->assertTrue($this->rule->validate('url', 'https://', ['https://'], []));
    }

    public function testValidatePassesWithMultiplePrefixes(): void
    {
        $this->assertTrue($this->rule->validate('url', 'http://', ['https://', 'http://'], []));
    }

    public function testValidatePassesWithNumericPrefix(): void
    {
        $this->assertTrue($this->rule->validate('code', '123abc', ['123'], []));
    }

    public function testValidateFailsWithNoMatchingPrefix(): void
    {
        $this->assertFalse($this->rule->validate('url', 'ftp://', ['https://', 'http://'], []));
    }

    public function testValidateFailsWithCaseMismatch(): void
    {
        $this->assertFalse($this->rule->validate('url', 'HTTPS://', ['https://'], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('url', '', ['https://'], []));
    }

    public function testValidateFailsWithNonStringValue(): void
    {
        $this->assertFalse($this->rule->validate('url', 123, ['https://'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('url', null, ['https://'], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('url', ['https://'], ['https://'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('url', new \stdClass(), ['https://'], []));
    }

    public function testValidateFailsWithEmptyPrefix(): void
    {
        $this->assertFalse($this->rule->validate('url', 'https://', [''], []));
    }

    public function testValidatePassesWithUnicodePrefix(): void
    {
        $this->assertTrue($this->rule->validate('text', '世界hello', ['世界'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('url', ['https://', 'http://'], null);
        $this->assertEquals('The :attribute must start with one of: :values.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute starting with :values.';
        $message = $this->rule->getErrorMessage('url', ['https://', 'http://'], $customMessage);
        $this->assertEquals('Custom error for :attribute starting with :values.', $message);
    }
}