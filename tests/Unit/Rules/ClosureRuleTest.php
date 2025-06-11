<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use Closure;
use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\ClosureRule;

class ClosureRuleTest extends TestCase
{
    private ClosureRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testValidatePassesWithPassingClosure(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            // No fail call, so validation passes
        };
        $this->rule = new ClosureRule($closure);
        $this->assertTrue($this->rule->validate('field', 15, [], []));
        $this->assertEquals('The :attribute is invalid (custom validation).', $this->rule->getErrorMessage('field', [], null));
    }

    public function testValidateFailsWithFailingClosure(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            if ($value < 10) {
                $fail('The :attribute must be at least 10.');
            }
        };
        $this->rule = new ClosureRule($closure);
        $this->assertFalse($this->rule->validate('field', 5, [], []));
        $this->assertEquals('The :attribute must be at least 10.', $this->rule->getErrorMessage('field', [], null));
    }

    public function testValidateFailsWithNullValue(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            if ($value === null) {
                $fail('The :attribute cannot be null.');
            }
        };
        $this->rule = new ClosureRule($closure);
        $this->assertFalse($this->rule->validate('field', null, [], []));
        $this->assertEquals('The :attribute cannot be null.', $this->rule->getErrorMessage('field', [], null));
    }

    public function testValidateFailsWithException(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            throw new \Exception('Closure error');
        };
        $this->rule = new ClosureRule($closure);
        $this->assertFalse($this->rule->validate('field', 'value', [], []));
        $this->assertStringStartsWith('An error occurred during custom validation: Closure error', $this->rule->getErrorMessage('field', [], null));
    }

    public function testValidateUsesDataArray(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            if ($data['other'] !== 'expected') {
                $fail('The :attribute requires other field to be expected.');
            }
        };
        $this->rule = new ClosureRule($closure);
        $this->assertFalse($this->rule->validate('field', 'value', [], ['other' => 'wrong']));
        $this->assertEquals('The :attribute requires other field to be expected.', $this->rule->getErrorMessage('field', [], null));
    }

    public function testGetErrorMessageReturnsDefaultTemplateWhenValidationFailsWithoutFailMessage(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            return false; // Simulate failure without calling fail
        };
        $this->rule = new ClosureRule($closure);
        $this->assertFalse($this->rule->validate('field', 'value', [], []));
        $this->assertEquals('The :attribute is invalid (custom validation).', $this->rule->getErrorMessage('field', [], null));
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            $fail('Custom fail message.');
        };
        $customMessage = 'Custom error for :attribute.';
        $this->rule = new ClosureRule($closure);
        $this->rule->validate('field', 'value', [], []);
        $this->assertEquals('Custom fail message.', $this->rule->getErrorMessage('field', [], $customMessage));
    }

    public function testGetErrorMessageWithCustomDefaultTemplate(): void
    {
        $closure = function (string $attribute, $value, Closure $fail, array $data) {
            return false; // Simulate failure without calling fail
        };
        $defaultTemplate = 'Custom default for :attribute.';
        $this->rule = new ClosureRule($closure, $defaultTemplate);
        $this->assertFalse($this->rule->validate('field', 'value', [], []));
        $this->assertEquals('Custom default for :attribute.', $this->rule->getErrorMessage('field', [], null));
    }
}