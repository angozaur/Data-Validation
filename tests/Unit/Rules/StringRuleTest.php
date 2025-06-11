<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\StringRule;

class StringRuleTest extends TestCase
{
    private StringRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new StringRule();
    }

    public function testValidatePassesWithNonEmptyString(): void
    {
        $this->assertTrue($this->rule->validate('name', 'John', [], []));
    }

    public function testValidatePassesWithEmptyString(): void
    {
        $this->assertTrue($this->rule->validate('name', '', [], []));
    }

    public function testValidatePassesWithUnicodeString(): void
    {
        $this->assertTrue($this->rule->validate('name', '世界', [], []));
    }

    public function testValidateFailsWithInteger(): void
    {
        $this->assertFalse($this->rule->validate('value', 123, [], []));
    }

    public function testValidateFailsWithFloat(): void
    {
        $this->assertFalse($this->rule->validate('value', 123.45, [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('value', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('value', ['John'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('value', new \stdClass(), [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('name', [], null);
        $this->assertEquals('The :attribute must be a string.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('name', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}