<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\ArrayRule;

class ArrayRuleTest extends TestCase
{
    private ArrayRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new ArrayRule();
    }

    public function testValidatePassesWithEmptyArray(): void
    {
        $this->assertTrue($this->rule->validate('field', [], [], []));
    }

    public function testValidatePassesWithNonEmptyArray(): void
    {
        $this->assertTrue($this->rule->validate('field', ['a', 'b'], [], []));
    }

    public function testValidateFailsWithString(): void
    {
        $this->assertFalse($this->rule->validate('field', 'not an array', [], []));
    }

    public function testValidateFailsWithInteger(): void
    {
        $this->assertFalse($this->rule->validate('field', 123, [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('field', null, [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('field', new \stdClass(), [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('field', [], null);
        $this->assertEquals('The :attribute must be an array.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('field', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}