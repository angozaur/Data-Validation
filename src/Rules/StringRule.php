<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class StringRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return is_string($value);
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be a string.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'string' rule for field '{$field}' does not accept parameters.");
        }
    }
}
