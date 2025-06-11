<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\DateRule;

class DateRuleTest extends TestCase
{
    private DateRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new DateRule();
    }

    public function testValidatePassesWithIsoDate(): void
    {
        $this->assertTrue($this->rule->validate('date', '2025-06-03', [], []));
    }

    public function testValidatePassesWithTextDate(): void
    {
        $this->assertTrue($this->rule->validate('date', 'June 3, 2025', [], []));
    }

    public function testValidatePassesWithSlashedDate(): void
    {
        $this->assertTrue($this->rule->validate('date', '06/03/2025', [], []));
    }

    public function testValidateFailsWithInvalidDate(): void
    {
        $this->assertFalse($this->rule->validate('date', '2025-13-03', [], []));
    }

    public function testValidateFailsWithMalformedDate(): void
    {
        $this->assertFalse($this->rule->validate('date', 'invalid', [], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('date', '', [], []));
    }

    public function testValidateFailsWithNonStringValue(): void
    {
        $this->assertFalse($this->rule->validate('date', 123, [], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('date', null, [], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('date', ['2025-06-03'], [], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('date', new \stdClass(), [], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('date', [], null);
        $this->assertEquals('The :attribute must be a valid date.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('date', [], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}