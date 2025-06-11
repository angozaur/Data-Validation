<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\SameRule;

class SameRuleTest extends TestCase
{
    private SameRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new SameRule();
    }

    public function testValidatePassesWithSameValues(): void
    {
        $this->assertTrue($this->rule->validate('password', 'secret', ['confirm', 'secret'], []));
    }

    public function testValidatePassesWithBothNull(): void
    {
        $this->assertTrue($this->rule->validate('password', null, ['confirm', null], []));
    }

    public function testValidateFailsWithDifferentValues(): void
    {
        $this->assertFalse($this->rule->validate('password', 'secret', ['confirm', 'different'], []));
    }

    public function testValidateFailsWithNullOtherValue(): void
    {
        $this->assertFalse($this->rule->validate('password', 'secret', ['confirm', null], []));
    }

    public function testValidateFailsWithTypeMismatch(): void
    {
        $this->assertFalse($this->rule->validate('password', '123', ['code', 123], []));
    }

    public function testValidateFailsWithMissingOtherValue(): void
    {
        $this->assertFalse($this->rule->validate('password', 'secret', ['confirm'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('password', ['confirm'], null);
        $this->assertEquals('The :attribute must match :other.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute matching :other.';
        $message = $this->rule->getErrorMessage('password', ['confirm'], $customMessage);
        $this->assertEquals('Custom error for :attribute matching :other.', $message);
    }
}