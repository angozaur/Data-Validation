<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\BooleanRule;

class BooleanRuleTest extends TestCase
{
    private BooleanRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new BooleanRule();
    }

    public function testValidatePassesWithTrue(): void
    {
        $this->assertTrue($this->rule->validate('field', true, [], []));
    }

    public function testValidatePassesWithFalse(): void
    {
        $this->assertTrue($this->rule->validate('field', false, [], []));
    }

    public function testValidatePassesWithZeroInteger(): void
    {
        $this->assertTrue($this->rule->validate('field', 0, [], []));
    }

    public function testValidatePassesWithOneInteger(): void
    {
        $this->assertTrue($this->rule->validate('field', 1, [], []));
    }

    public function testValidatePassesWithZeroString(): void
    {
        $this->assertTrue($this->rule->validate('field', '0', [], []));
    }

    public function testValidatePassesWithOneString(): void
    {
        $this->assertTrue($this->rule->validate('field', '1', [], []));
    }

    public function testValidateFailsWithStringTrue(): void
    {
        $this->assertFalse($this->rule->validate('field', 'true', [], []));
    }

    public function testValidateFailsWithStringFalse(): void
    {
        $this->assertFalse($this->rule->validate('field', 'false', [], []));
    }

    public function testValidateFailsWithTwoInteger(): void
    {
        $this->assertFalse($this->rule->validate('field', 2, [], []));
    }

    public function testValidateFailsWithTwoString(): void
    {
        $this->assertFalse($this->rule->validate('field', '2', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('field', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('field', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('field', [], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('field', new \stdClass(), [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('field', [], null);
        $this->assertEquals('The :attribute must be a boolean value.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('field', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}