<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\UuidRule;

class UuidRuleTest extends TestCase
{
    private UuidRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new UuidRule();
    }

    public function testValidatePassesWithValidUuid(): void
    {
        $this->assertTrue($this->rule->validate('id', '123e4567-e89b-12d3-a456-426614174000', [], []));
    }

    public function testValidatePassesWithUpperCaseUuid(): void
    {
        $this->assertTrue($this->rule->validate('id', '123E4567-E89B-12D3-A456-426614174000', [], []));
    }

    public function testValidateFailsWithInvalidUuid(): void
    {
        $this->assertFalse($this->rule->validate('id', '123e4567-e89b-12d3-a456-42661417400', [], []));
    }

    public function testValidateFailsWithNonUuidString(): void
    {
        $this->assertFalse($this->rule->validate('id', 'not-a-uuid', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('id', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('id', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('id', ['123e4567-e89b-12d3-a456-426614174000'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('id', new \stdClass(), [], []));
    }

    public function testValidateFailsWithInvalidVersion(): void
    {
        $this->assertFalse($this->rule->validate('id', '123e4567-e89b-62d3-a456-426614174000', [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('id', [], null);
        $this->assertEquals('The :attribute must be a valid UUID.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('id', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}