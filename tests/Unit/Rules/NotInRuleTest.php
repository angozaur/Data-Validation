<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\NotInRule;

class NotInRuleTest extends TestCase
{
    private NotInRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new NotInRule();
    }

    public function testValidatePassesWithStringNotInList(): void
    {
        $this->assertTrue($this->rule->validate('status', 'pending', ['active', 'inactive'], []));
    }

    public function testValidatePassesWithIntegerNotInList(): void
    {
        $this->assertTrue($this->rule->validate('code', 4, [1, 2, 3], []));
    }

    public function testValidatePassesWithNullNotInList(): void
    {
        $this->assertTrue($this->rule->validate('option', null, ['yes', 'no'], []));
    }

    public function testValidatePassesWithArrayNotInList(): void
    {
        $array = ['key' => 'value'];
        $this->assertTrue($this->rule->validate('data', $array, [['other' => 'data']], []));
    }

    public function testValidatePassesWithUnicodeNotInList(): void
    {
        $this->assertTrue($this->rule->validate('name', '世界', ['こんにちは'], []));
    }

    public function testValidateFailsWithStringInList(): void
    {
        $this->assertFalse($this->rule->validate('status', 'active', ['active', 'inactive'], []));
    }

    public function testValidateFailsWithIntegerInList(): void
    {
        $this->assertFalse($this->rule->validate('code', 1, [1, 2, 3], []));
    }

    public function testValidateFailsWithNullInList(): void
    {
        $this->assertFalse($this->rule->validate('option', null, ['yes', 'no', null], []));
    }

    public function testValidatePassesWithTypeMismatchStringVsInteger(): void
    {
        $this->assertTrue($this->rule->validate('code', '1', [1, 2, 3], []));
    }

    public function testValidatePassesWithTypeMismatchIntegerVsString(): void
    {
        $this->assertTrue($this->rule->validate('code', 1, ['1', '2', '3'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('status', ['active', 'inactive'], null);
        $this->assertEquals('The :attribute must not be one of: :values.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute with values :values.';
        $message = $this->rule->getErrorMessage('status', ['active', 'inactive'], $customMessage);
        $this->assertEquals('Custom error for :attribute with values :values.', $message);
    }
}