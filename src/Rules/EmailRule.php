<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class EmailRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be a valid email address.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'email' rule for field '{$field}' does not accept parameters.");
        }
    }
}
