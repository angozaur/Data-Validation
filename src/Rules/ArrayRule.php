<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class ArrayRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return is_array($value);
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be an array.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'array' rule for field '{$field}' does not accept parameters.");
        }
    }
}
