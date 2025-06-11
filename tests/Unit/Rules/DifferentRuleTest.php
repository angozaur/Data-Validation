<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\DifferentRule;

class DifferentRuleTest extends TestCase
{
    private DifferentRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new DifferentRule();
    }

    public function testValidatePassesWithDifferentValues(): void
    {
        $data = ['password' => 'secret', 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('password', 'secret', ['email'], $data));
    }

    public function testValidatePassesWithNullOtherValue(): void
    {
        $data = ['password' => 'secret', 'email' => null];
        $this->assertTrue($this->rule->validate('password', 'secret', ['email'], $data));
    }

    public function testValidatePassesWithNestedField(): void
    {
        $data = ['password' => 'secret', 'user' => ['email' => 'user@example.com']];
        $this->assertTrue($this->rule->validate('password', 'secret', ['user.email'], $data));
    }

    public function testValidateFailsWithSameValues(): void
    {
        $data = ['password' => 'secret', 'email' => 'secret'];
        $this->assertFalse($this->rule->validate('password', 'secret', ['email'], $data));
    }

    public function testValidateFailsWithEmptyOtherField(): void
    {
        $data = ['password' => 'secret'];
        $this->assertFalse($this->rule->validate('password', 'secret', [], $data));
    }

    public function testValidateFailsWithNonExistentOtherField(): void
    {
        $data = ['password' => 'secret'];
        $this->assertTrue($this->rule->validate('password', 'secret', ['email'], $data)); // secret !== null
    }

    public function testValidateFailsWithWildcardOtherField(): void
    {
        $data = ['password' => 'secret', 'users' => [['email' => 'user@example.com']]];
        $this->assertTrue($this->rule->validate('password', 'secret', ['users.*.email'], $data)); // secret !== null
    }

    public function testValidatePassesWithDifferentTypes(): void
    {
        $data = ['password' => '123', 'code' => 123];
        $this->assertTrue($this->rule->validate('password', '123', ['code'], $data)); // '123' !== 123
    }

    public function testValidatePassesWithBothNull(): void
    {
        $data = ['password' => null, 'email' => null];
        $this->assertFalse($this->rule->validate('password', null, ['email'], $data)); // null === null
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('password', ['email'], null);
        $this->assertEquals('The :attribute must be different from :other.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute differing from :other.';
        $message = $this->rule->getErrorMessage('password', ['email'], $customMessage);
        $this->assertEquals('Custom error for :attribute differing from :other.', $message);
    }
}