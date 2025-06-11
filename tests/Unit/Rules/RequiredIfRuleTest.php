<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\RequiredIfRule;

class RequiredIfRuleTest extends TestCase
{
    private RequiredIfRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new RequiredIfRule();
    }

    public function testValidatePassesWhenOtherValueNotInExpected(): void
    {
        $data = ['status' => 'inactive', 'email' => null];
        $this->assertTrue($this->rule->validate('email', null, ['status', 'active'], $data));
    }

    public function testValidatePassesWhenValuePresentAndOtherValueMatches(): void
    {
        $data = ['status' => 'active', 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('email', 'user@example.com', ['status', 'active'], $data));
    }

    public function testValidateFailsWhenValueNullAndOtherValueMatches(): void
    {
        $data = ['status' => 'active', 'email' => null];
        $this->assertFalse($this->rule->validate('email', null, ['status', 'active'], $data));
    }

    public function testValidateFailsWhenValueEmptyAndOtherValueMatches(): void
    {
        $data = ['status' => 'active', 'email' => ''];
        $this->assertFalse($this->rule->validate('email', '', ['status', 'active'], $data));
    }

    public function testValidatePassesWithNestedOtherField(): void
    {
        $data = ['user' => ['status' => 'active'], 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('email', 'user@example.com', ['user.status', 'active'], $data));
    }

    public function testValidatePassesWithEmptyParameters(): void
    {
        $data = ['status' => 'active'];
        $this->assertTrue($this->rule->validate('email', null, [], $data));
    }

    public function testValidatePassesWithNonExistentOtherField(): void
    {
        $data = ['status' => 'active'];
        $this->assertTrue($this->rule->validate('email', null, ['other', 'active'], $data));
    }

    public function testValidatePassesWithMultipleExpectedValues(): void
    {
        $data = ['status' => 'active', 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('email', 'user@example.com', ['status', 'active', 'pending'], $data));
    }

    public function testValidatePassesWithWildcardOtherField(): void
    {
        $data = ['users' => [['status' => 'active']], 'email' => 'user@example.com'];
        $this->assertTrue($this->rule->validate('email', 'user@example.com', ['users.*.status', 'active'], $data));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('field', ['status', 'active'], null);
        $this->assertEquals('The :attribute field is required when :other is :values.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute.';
        $message = $this->rule->getErrorMessage('email', ['status', 'active'], $customMessage);
        $this->assertEquals('Custom error for :attribute.', $message);
    }
}