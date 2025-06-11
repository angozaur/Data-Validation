<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\NullableRule;

class NullableRuleTest extends TestCase
{
    private NullableRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new NullableRule();
    }

    public function testValidatePassesWithNull(): void
    {
        $this->assertTrue($this->rule->validate('value', null, [], []));
    }

    public function testValidatePassesWithString(): void
    {
        $this->assertTrue($this->rule->validate('value', 'test', [], []));
    }

    public function testValidatePassesWithEmptyString(): void
    {
        $this->assertTrue($this->rule->validate('value', '', [], []));
    }

    public function testValidatePassesWithInteger(): void
    {
        $this->assertTrue($this->rule->validate('value', 123, [], []));
    }

    public function testValidatePassesWithArray(): void
    {
        $this->assertTrue($this->rule->validate('value', [1, 2, 3], [], []));
    }

    public function testValidatePassesWithObject(): void
    {
        $this->assertTrue($this->rule->validate('value', new \stdClass(), [], []));
    }

    public function testValidatePassesWithUnicodeString(): void
    {
        $this->assertTrue($this->rule->validate('value', '世界', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('value', [], null);
        $this->assertEquals('The :attribute is invalid.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('value', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $customMessage);
    }
}