<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\MinRule;

class MinRuleTest extends TestCase
{
    private MinRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new MinRule();
    }

    public function testValidatePassesWithNumericEqualToMin(): void
    {
        $this->assertTrue($this->rule->validate('age', 18, ['18'], []));
    }

    public function testValidatePassesWithNumericGreaterThanMin(): void
    {
        $this->assertTrue($this->rule->validate('age', 19, ['18'], []));
    }

    public function testValidatePassesWithStringEqualToMinLength(): void
    {
        $this->assertTrue($this->rule->validate('name', 'John', ['4'], []));
    }

    public function testValidatePassesWithStringGreaterThanMinLength(): void
    {
        $this->assertTrue($this->rule->validate('name', 'Johnny', ['4'], []));
    }

    public function testValidatePassesWithArrayEqualToMinCount(): void
    {
        $this->assertTrue($this->rule->validate('items', [1, 2, 3], ['3'], []));
    }

    public function testValidatePassesWithArrayGreaterThanMinCount(): void
    {
        $this->assertTrue($this->rule->validate('items', [1, 2, 3, 4], ['3'], []));
    }

    public function testValidateFailsWithNumericLessThanMin(): void
    {
        $this->assertFalse($this->rule->validate('age', 17, ['18'], []));
    }

    public function testValidateFailsWithStringLessThanMinLength(): void
    {
        $this->assertFalse($this->rule->validate('name', 'Jon', ['4'], []));
    }

    public function testValidateFailsWithArrayLessThanMinCount(): void
    {
        $this->assertFalse($this->rule->validate('items', [1, 2], ['3'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('value', null, ['5'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('value', new \stdClass(), ['5'], []));
    }

    public function testValidatePassesWithDecimalMin(): void
    {
        $this->assertTrue($this->rule->validate('price', 100.5, ['100.0'], []));
    }

    public function testValidateFailsWithDecimalLessThanMin(): void
    {
        $this->assertFalse($this->rule->validate('price', 99.9, ['100.0'], []));
    }

    public function testValidatePassesWithNegativeMin(): void
    {
        $this->assertTrue($this->rule->validate('balance', -5, ['-10'], []));
    }

    public function testValidatePassesWithScientificNotation(): void
    {
        $this->assertTrue($this->rule->validate('value', 1e4, ['1000'], []));
    }

    public function testValidatePassesWithEmptyStringAtZeroMin(): void
    {
        $this->assertTrue($this->rule->validate('name', '', ['0'], []));
    }

    public function testValidatePassesWithEmptyArrayAtZeroMin(): void
    {
        $this->assertTrue($this->rule->validate('items', [], ['0'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('age', ['18'], null);
        $this->assertEquals('The :attribute must be at least :min.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute with min :min.';
        $message = $this->rule->getErrorMessage('age', ['18'], $customMessage);
        $this->assertEquals('Custom error for :attribute with min :min.', $message);
    }
}