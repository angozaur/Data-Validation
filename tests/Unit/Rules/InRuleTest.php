<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\InRule;

class InRuleTest extends TestCase
{
    private InRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new InRule();
    }

    public function testValidatePassesWithStringValue(): void
    {
        $this->assertTrue($this->rule->validate('status', 'active', ['active', 'inactive'], []));
    }

    public function testValidatePassesWithIntegerValue(): void
    {
        $this->assertTrue($this->rule->validate('code', 1, [1, 2, 3], []));
    }

    public function testValidatePassesWithNullValue(): void
    {
        $this->assertTrue($this->rule->validate('option', null, ['yes', 'no', null], []));
    }

    public function testValidatePassesWithArrayValue(): void
    {
        $array = ['key' => 'value'];
        $this->assertTrue($this->rule->validate('data', $array, [$array, 'other'], []));
    }

    public function testValidatePassesWithUnicodeValue(): void
    {
        $this->assertTrue($this->rule->validate('name', '世界', ['こんにちは', '世界'], []));
    }

    public function testValidateFailsWithMissingValue(): void
    {
        $this->assertFalse($this->rule->validate('status', 'pending', ['active', 'inactive'], []));
    }

    public function testValidateFailsWithTypeMismatchStringVsInteger(): void
    {
        $this->assertFalse($this->rule->validate('code', '1', [1, 2, 3], []));
    }

    public function testValidateFailsWithTypeMismatchIntegerVsString(): void
    {
        $this->assertFalse($this->rule->validate('code', 1, ['1', '2', '3'], []));
    }

    public function testValidateFailsWithNonMatchingNull(): void
    {
        $this->assertFalse($this->rule->validate('option', null, ['yes', 'no'], []));
    }

    public function testValidateFailsWithNonMatchingArray(): void
    {
        $this->assertFalse($this->rule->validate('data', ['key' => 'value'], [['other' => 'data']], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('status', ['active', 'inactive'], null);
        $this->assertEquals('The :attribute must be one of: :values.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute with values :values.';
        $message = $this->rule->getErrorMessage('status', ['active', 'inactive'], $customMessage);
        $this->assertEquals('Custom error for :attribute with values :values.', $message);
    }
}