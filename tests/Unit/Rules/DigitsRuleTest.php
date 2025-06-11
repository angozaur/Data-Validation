<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\DigitsRule;

class DigitsRuleTest extends TestCase
{
    private DigitsRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new DigitsRule();
    }

    public function testValidatePassesWithStringDigits(): void
    {
        $this->assertTrue($this->rule->validate('code', '123', ['3'], []));
    }

    public function testValidatePassesWithIntegerDigits(): void
    {
        $this->assertTrue($this->rule->validate('code', 123, ['3'], []));
    }

    public function testValidateFailsWithNonDigitString(): void
    {
        $this->assertFalse($this->rule->validate('code', '12a', ['3'], []));
    }

    public function testValidateFailsWithWrongLength(): void
    {
        $this->assertFalse($this->rule->validate('code', '1234', ['3'], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('code', '', ['3'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('code', null, ['3'], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('code', ['123'], ['3'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('code', new \stdClass(), ['3'], []));
    }

    public function testValidateFailsWithFloat(): void
    {
        $this->assertFalse($this->rule->validate('code', 123.0, ['3'], []));
    }

    public function testValidateFailsWithNegativeNumber(): void
    {
        $this->assertFalse($this->rule->validate('code', '-123', ['3'], []));
    }

    public function testValidateFailsWithZeroDigits(): void
    {
        $this->assertFalse($this->rule->validate('code', '', ['0'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('code', ['3'], null);
        $this->assertEquals('The :attribute must be :digits digits.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute with :digits digits.';
        $message = $this->rule->getErrorMessage('code', ['3'], $customMessage);
        $this->assertEquals('Custom error for :attribute with :digits digits.', $message);
    }
}