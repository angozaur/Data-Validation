<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\MaxRule;

class MaxRuleTest extends TestCase
{
    private MaxRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new MaxRule();
    }

    public function testValidatePassesWithNumericEqualToMax(): void
    {
        $this->assertTrue($this->rule->validate('age', 100, ['100'], []));
    }

    public function testValidatePassesWithNumericLessThanMax(): void
    {
        $this->assertTrue($this->rule->validate('age', 99, ['100'], []));
    }

    public function testValidatePassesWithStringEqualToMaxLength(): void
    {
        $this->assertTrue($this->rule->validate('name', 'John', ['4'], []));
    }

    public function testValidatePassesWithStringLessThanMaxLength(): void
    {
        $this->assertTrue($this->rule->validate('name', 'Jon', ['4'], []));
    }

    public function testValidatePassesWithArrayEqualToMaxCount(): void
    {
        $this->assertTrue($this->rule->validate('items', [1, 2, 3], ['3'], []));
    }

    public function testValidatePassesWithArrayLessThanMaxCount(): void
    {
        $this->assertTrue($this->rule->validate('items', [1, 2], ['3'], []));
    }

    public function testValidatePassesWithEmptyString(): void
    {
        $this->assertTrue($this->rule->validate('name', '', ['5'], []));
    }

    public function testValidatePassesWithEmptyArray(): void
    {
        $this->assertTrue($this->rule->validate('items', [], ['5'], []));
    }

    public function testValidateFailsWithNumericGreaterThanMax(): void
    {
        $this->assertFalse($this->rule->validate('age', 101, ['100'], []));
    }

    public function testValidateFailsWithStringGreaterThanMaxLength(): void
    {
        $this->assertFalse($this->rule->validate('name', 'Johnny', ['5'], []));
    }

    public function testValidateFailsWithArrayGreaterThanMaxCount(): void
    {
        $this->assertFalse($this->rule->validate('items', [1, 2, 3, 4], ['3'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('value', null, ['5'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('value', new \stdClass(), ['5'], []));
    }

    public function testValidatePassesWithDecimalMax(): void
    {
        $this->assertTrue($this->rule->validate('price', 99.99, ['100.5'], []));
    }

    public function testValidateFailsWithDecimalExceedingMax(): void
    {
        $this->assertFalse($this->rule->validate('price', 100.6, ['100.5'], []));
    }

    public function testValidatePassesWithNegativeMax(): void
    {
        $this->assertTrue($this->rule->validate('balance', -10, ['-5'], []));
    }

    public function testValidatePassesWithScientificNotation(): void
    {
        $this->assertTrue($this->rule->validate('value', 1e4, ['10000'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('age', ['100'], null);
        $this->assertEquals('The :attribute may not be greater than :max.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute with max :max.';
        $message = $this->rule->getErrorMessage('age', ['100'], $customMessage);
        $this->assertEquals('Custom error for :attribute with max :max.', $message);
    }
}