<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\EmailRule;

class EmailRuleTest extends TestCase
{
    private EmailRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new EmailRule();
    }

    public function testValidatePassesWithSimpleEmail(): void
    {
        $this->assertTrue($this->rule->validate('email', 'user@example.com', [], []));
    }

    public function testValidatePassesWithComplexEmail(): void
    {
        $this->assertTrue($this->rule->validate('email', 'user.name+tag@sub.domain.com', [], []));
    }

    public function testValidateFailsWithInvalidEmail(): void
    {
        $this->assertFalse($this->rule->validate('email', 'user@.com', [], []));
    }

    public function testValidateFailsWithMissingDomain(): void
    {
        $this->assertFalse($this->rule->validate('email', 'user@', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('email', '', [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('email', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('email', ['user@example.com'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('email', new \stdClass(), [], []));
    }

    public function testValidateFailsWithNonStringCharacters(): void
    {
        $this->assertFalse($this->rule->validate('email', 'user@exa mple.com', [], []));
    }

    public function testValidateFailsWithOverlyLongEmail(): void
    {
        $longEmail = str_repeat('a', 255) . '@example.com';
        $this->assertFalse($this->rule->validate('email', $longEmail, [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('email', [], null);
        $this->assertEquals('The :attribute must be a valid email address.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('email', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}