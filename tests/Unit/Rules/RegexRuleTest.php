<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\RegexRule;

class RegexRuleTest extends TestCase
{
    private RegexRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new RegexRule();
    }

    public function testValidatePassesWithMatchingString(): void
    {
        $this->assertTrue($this->rule->validate('code', 'ABC123', ['/^[A-Z]{3}[0-9]{3}$/'], []));
    }

    public function testValidateFailsWithNonMatchingString(): void
    {
        $this->assertFalse($this->rule->validate('code', 'AB12', ['/^[A-Z]{3}[0-9]{3}$/'], []));
    }

    public function testValidateFailsWithNonStringValue(): void
    {
        $this->assertFalse($this->rule->validate('code', 123, ['/^[0-9]+$/'], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('code', '', ['/^[A-Z]+$/'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('code', null, ['/^[0-9]+$/'], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('code', ['ABC123'], ['/^[A-Z]+$/'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('code', new \stdClass(), ['/^[A-Z]+$/'], []));
    }

    public function testValidateFailsWithEmptyPattern(): void
    {
        $this->assertFalse($this->rule->validate('code', 'test', [''], []));
    }

    public function testValidateFailsWithInvalidPattern(): void
    {
        $this->assertFalse($this->rule->validate('code', 'test', ['/[/'], []));
    }

    public function testValidatePassesWithUnicodeString(): void
    {
        $this->assertTrue($this->rule->validate('text', '世界', ['/^\p{L}+$/u'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('code', ['/^[A-Z]+$/'], null);
        $this->assertEquals('The :attribute format is invalid.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('code', ['/^[A-Z]+$/'], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}