<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\NumericRule;

class NumericRuleTest extends TestCase
{
    private NumericRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new NumericRule();
    }

    public function testValidatePassesWithInteger(): void
    {
        $this->assertTrue($this->rule->validate('value', 123, [], []));
    }

    public function testValidatePassesWithFloat(): void
    {
        $this->assertTrue($this->rule->validate('value', 123.45, [], []));
    }

    public function testValidatePassesWithNumericString(): void
    {
        $this->assertTrue($this->rule->validate('value', '123', [], []));
    }

    public function testValidatePassesWithFloatString(): void
    {
        $this->assertTrue($this->rule->validate('value', '123.45', [], []));
    }

    public function testValidatePassesWithScientificNotation(): void
    {
        $this->assertTrue($this->rule->validate('value', '1e4', [], []));
    }

    public function testValidateFailsWithNonNumericString(): void
    {
        $this->assertFalse($this->rule->validate('value', 'abc', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('value', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('value', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('value', [123], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('value', new \stdClass(), [], []));
    }

    public function testValidateFailsWithHexadecimal(): void
    {
        $this->assertFalse($this->rule->validate('value', '0x1A', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('value', [], null);
        $this->assertEquals('The :attribute must be numeric.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('value', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}