<?php

namespace YorCreative\DataValidation\Tests\Unit\Rules;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Rules\EndsWithRule;

class EndsWithRuleTest extends TestCase
{
    private EndsWithRule $rule;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rule = new EndsWithRule();
    }

    public function testValidatePassesWithSingleSuffix(): void
    {
        $this->assertTrue($this->rule->validate('filename', 'image.jpg', ['.jpg'], []));
    }

    public function testValidatePassesWithMultipleSuffixes(): void
    {
        $this->assertTrue($this->rule->validate('filename', 'image.png', ['.jpg', '.png'], []));
    }

    public function testValidatePassesWithNumericSuffix(): void
    {
        $this->assertTrue($this->rule->validate('code', 'abc123', ['123'], []));
    }

    public function testValidateFailsWithNoMatchingSuffix(): void
    {
        $this->assertFalse($this->rule->validate('filename', 'image.gif', ['.jpg', '.png'], []));
    }

    public function testValidateFailsWithCaseMismatch(): void
    {
        $this->assertFalse($this->rule->validate('filename', 'image.JPG', ['.jpg'], []));
    }

    public function testValidateFailsWithEmptyString(): void
    {
        $this->assertFalse($this->rule->validate('filename', '', ['.jpg'], []));
    }

    public function testValidateFailsWithNonStringValue(): void
    {
        $this->assertFalse($this->rule->validate('filename', 123, ['.jpg'], []));
    }

    public function testValidateFailsWithNull(): void
    {
        $this->assertFalse($this->rule->validate('filename', null, ['.jpg'], []));
    }

    public function testValidateFailsWithArray(): void
    {
        $this->assertFalse($this->rule->validate('filename', ['image.jpg'], ['.jpg'], []));
    }

    public function testValidateFailsWithObject(): void
    {
        $this->assertFalse($this->rule->validate('filename', new \stdClass(), ['.jpg'], []));
    }

    public function testValidateFailsWithEmptySuffix(): void
    {
        $this->assertFalse($this->rule->validate('filename', 'image', [''], []));
    }

    public function testValidatePassesWithUnicodeSuffix(): void
    {
        $this->assertTrue($this->rule->validate('text', 'hello世界', ['世界'], []));
    }

    public function testGetErrorMessageReturnsDefaultTemplate(): void
    {
        $message = $this->rule->getErrorMessage('filename', ['.jpg', '.png'], null);
        $this->assertEquals('The :attribute must end with one of: :values.', $message);
    }

    public function testGetErrorMessageReturnsCustomTemplate(): void
    {
        $customMessage = 'Custom error for :attribute ending with :values.';
        $message = $this->rule->getErrorMessage('filename', ['.jpg', '.png'], $customMessage);
        $this->assertEquals('Custom error for :attribute ending with :values.', $message);
    }
}