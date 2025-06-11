<?php

namespace YorCreative\DataValidation\Tests\Feature;

use PHPUnit\Framework\TestCase;
use YorCreative\DataValidation\Validator;

class ValidationFeatureTest extends TestCase
{

    public function testComplexNestedDataWithWildcards(): void
    {
        $data = [
            'orders' => [
                ['items' => [['name' => 'Apple'], ['name' => 'Banana']]],
                ['items' => [['name' => 'Carrot']]],
            ],
        ];
        $rules = ['orders.*.items.*.name' => 'required'];
        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->validate());
    }

    public function testNestedRequiredIfWithWildcards(): void
    {
        $data = [
            'users' => [
                ['status' => 'active'],
                ['status' => 'inactive'],
            ],
        ];
        $rules = ['users.*.email' => 'required_if:users.*.status,active'];
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->validate());
        $this->assertArrayHasKey('users.0.email', $validator->errors());
        $this->assertStringContainsString('The users 0 email field is required.', $validator->errors()['users.0.email'][0]);
    }

    public function testInvalidNestedDataWithWildcards(): void
    {
        $data = [
            'users' => [
                ['profile' => ['name' => null]],
            ],
        ];
        $rules = ['users.*.profile.name' => 'required'];
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->validate());
        $this->assertArrayHasKey('users.0.profile.name', $validator->errors());
        $this->assertStringContainsString('users 0 profile name field is required', strtolower($validator->errors()['users.0.profile.name'][0]));
    }

    public function testCustomAttributesInNestedFields(): void
    {
        $data = ['user' => ['profile' => ['name' => null]]];
        $rules = ['user.profile.name' => 'required'];
        $attributes = ['user.profile.name' => 'User Name'];
        $validator = Validator::make($data, $rules, [], $attributes);
        $this->assertFalse($validator->validate());
        $this->assertArrayHasKey('user.profile.name', $validator->errors());
        $this->assertStringContainsString('The User Name field is required.', $validator->errors()['user.profile.name'][0]);
    }
}