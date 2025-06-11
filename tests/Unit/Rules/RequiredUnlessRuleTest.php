<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\RequiredUnlessRule;

class RequiredUnlessRuleTest extends TestCase
{
    private RequiredUnlessRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new RequiredUnlessRule();
    }

    public function testValidatePassesWhenOtherValueInExpected(): void
    {
        $data = ['status' => 'active', 'email' => null];
        $this->assertTrue($this->rule->validate('email', null, ['status', 'active'], $data));
    }

    public function testValidatePassesWhenValuePresentAndOtherValueNotInExpected(): void
    {
        $data = ['status' => 'inactive', 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('email', 'user@example.com', ['status', 'active'], $data));
    }

    public function testValidateFailsWhenValueNullAndOtherValueNotInExpected(): void
    {
        $data = ['status' => 'inactive', 'email' => null];
        $this->assertFalse($this->rule->validate('email', null, ['status', 'active'], $data));
    }

    public function testValidateFailsWhenValueEmptyAndOtherValueNotInExpected(): void
    {
        $data = ['status' => 'inactive', 'email' => ''];
        $this->assertFalse($this->rule->validate('email', '', ['status', 'active'], $data));
    }

    public function testValidatePassesWithNestedOtherField(): void
    {
        $data = ['user' => ['status' => 'inactive'], 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('email', 'user@example.com', ['user.status', 'active'], $data));
    }

    public function testValidateFailsWithEmptyParameters(): void
    {
        $data = ['status' => 'active'];
        $this->assertFalse($this->rule->validate('email', null, [], $data));
    }

    public function testValidatePassesWithNonExistentOtherField(): void
    {
        $data = ['status' => 'active'];
        $this->assertFalse($this->rule->validate('email', null, ['other', 'active'], $data));
    }

    public function testValidatePassesWithMultipleExpectedValues(): void
    {
        $data = ['status' => 'active', 'email' => null];
        $this->assertTrue($this->rule->validate('email', null, ['status', 'active', 'pending'], $data));
    }

    public function testValidatePassesWithWildcardOtherField(): void
    {
        $data = ['users' => [['status' => 'active']], 'email' => null];
        $this->assertFalse($this->rule->validate('email', null, ['users.*.status', 'active'], $data));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('field', ['status', 'active'], null);
        $this->assertEquals('The :attribute field is required unless :other is :values.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('email', ['status', 'active'], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}