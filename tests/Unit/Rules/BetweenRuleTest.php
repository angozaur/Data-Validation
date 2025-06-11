<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\BetweenRule;

class BetweenRuleTest extends TestCase
{
    public function testValidatePassesWithNumericInRange()
    {
        $rule = new BetweenRule();
        $this->assertTrue($rule->validate('age', 25, ['18', '30'], []));
    }

    public function testValidateFailsWithNumericBelowRange()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('age', 15, ['18', '30'], []));
    }

    public function testValidateFailsWithNumericAboveRange()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('age', 35, ['18', '30'], []));
    }

    public function testValidatePassesWithStringLengthInRange()
    {
        $rule = new BetweenRule();
        $this->assertTrue($rule->validate('name', 'John', ['3', '10'], []));
    }

    public function testValidateFailsWithStringLengthBelowRange()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('name', 'Jo', ['3', '10'], []));
    }

    public function testValidateFailsWithStringLengthAboveRange()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('name', 'Johnathan Doe', ['3', '10'], []));
    }

    public function testValidatePassesWithArrayCountInRange()
    {
        $rule = new BetweenRule();
        $this->assertTrue($rule->validate('items', [1, 2, 3], ['2', '5'], []));
    }

    public function testValidateFailsWithArrayCountBelowRange()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('items', [1], ['2', '5'], []));
    }

    public function testValidateFailsWithArrayCountAboveRange()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('items', [1, 2, 3, 4, 5, 6], ['2', '5'], []));
    }

    public function testValidateFailsWithNull()
    {
        $rule = new BetweenRule();
        $this->assertFalse($rule->validate('test', null, ['2', '5'], []));
    }

    public function testGetErrorMessage()
    {
        $rule = new BetweenRule();
        $message = $rule->getErrorMessage('age', ['18', '30'], null);
        $this->assertEquals('The age must be between 18 and 30.', $message);
    }

    public function testGetErrorMessageWithCustomMessage()
    {
        $rule = new BetweenRule();
        $customMessage = 'Custom error for :attribute between :min and :max.';
        $message = $rule->getErrorMessage('age', ['18', '30'], $customMessage);
        $this->assertEquals('Custom error for age between 18 and 30.', $message);
    }
}