<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\JsonRule;

class JsonRuleTest extends TestCase
{
    private JsonRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new JsonRule();
    }

    public function testValidatePassesWithObjectJson(): void
    {
        $this->assertTrue($this->rule->validate('data', '{"key":"value"}', [], []));
    }

    public function testValidatePassesWithArrayJson(): void
    {
        $this->assertTrue($this->rule->validate('data', '[1,2,3]', [], []));
    }

    public function testValidatePassesWithStringJson(): void
    {
        $this->assertTrue($this->rule->validate('data', '"hello"', [], []));
    }

    public function testValidatePassesWithNumberJson(): void
    {
        $this->assertTrue($this->rule->validate('data', '123', [], []));
    }

    public function testValidatePassesWithBooleanJson(): void
    {
        $this->assertTrue($this->rule->validate('data', 'true', [], []));
    }

    public function testValidatePassesWithNullJson(): void
    {
        $this->assertTrue($this->rule->validate('data', 'null', [], []));
    }

    public function testValidateFailsWithInvalidJson(): void
    {
        $this->assertFalse($this->rule->validate('data', '{key: value}', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('data', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('data', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('data', ['key' => 'value'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('data', new \stdClass(), [], []));
    }

    public function testValidateFailsWithMalformedJson(): void
    {
        $this->assertFalse($this->rule->validate('data', '{"key": "value"', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('data', [], null);
        $this->assertEquals('The :attribute must be a valid JSON string.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('data', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}