<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\RequiredRule;

class RequiredRuleTest extends TestCase
{
    private RequiredRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new RequiredRule();
    }

    public function testValidatePassesWithNonEmptyString(): void
    {
        $this->assertTrue($this->rule->validate('name', 'John', [], []));
    }

    public function testValidatePassesWithZero(): void
    {
        $this->assertTrue($this->rule->validate('value', 0, [], []));
    }

    public function testValidatePassesWithFalse(): void
    {
        $this->assertTrue($this->rule->validate('value', false, [], []));
    }

    public function testValidatePassesWithArray(): void
    {
        $this->assertTrue($this->rule->validate('items', [1, 2], [], []));
    }

    public function testValidatePassesWithObject(): void
    {
        $this->assertTrue($this->rule->validate('object', new \stdClass(), [], []));
    }

    public function testValidatePassesWithWhitespaceString(): void
    {
        $this->assertTrue($this->rule->validate('name', ' ', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('name', null, [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('name', '', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('name', [], null);
        $this->assertEquals('The :attribute field is required.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('name', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}