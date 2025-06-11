<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\DateFormatRule;

class DateFormatRuleTest extends TestCase
{
    private DateFormatRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new DateFormatRule();
    }

    public function testValidatePassesWithValidDateFormat(): void
    {
        $this->assertTrue($this->rule->validate('date', '2025-06-03', ['Y-m-d'], []));
    }

    public function testValidatePassesWithDifferentFormat(): void
    {
        $this->assertTrue($this->rule->validate('date', '06/03/2025', ['m/d/Y'], []));
    }

    public function testValidateFailsWithInvalidDate(): void
    {
        $this->assertFalse($this->rule->validate('date', '2025-13-03', ['Y-m-d'], []));
    }

    public function testValidateFailsWithWrongFormat(): void
    {
        $this->assertFalse($this->rule->validate('date', '06/03/2025', ['Y-m-d'], []));
    }

    public function testValidateFailsWithNonStringValue(): void
    {
        $this->assertFalse($this->rule->validate('date', 123, ['Y-m-d'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('date', null, ['Y-m-d'], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('date', ['2025-06-03'], ['Y-m-d'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('date', new \stdClass(), ['Y-m-d'], []));
    }

    public function testValidateFailsWithEmptyFormat(): void
    {
        $this->assertFalse($this->rule->validate('date', '2025-06-03', [], []));
    }

    public function testValidateFailsWithPartialMatch(): void
    {
        $this->assertFalse($this->rule->validate('date', '2025-06', ['Y-m-d'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('date', ['Y-m-d'], null);
        $this->assertEquals('The :attribute does not match the format :format.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute with format :format.';
        $message = $this->rule->getErrorMessage('date', ['Y-m-d'], $customMessage);
        $this->assertEquals('Custom error for :attribute with format :format.', $message);
    }
}