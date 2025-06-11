<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class NullableRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return true;
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute is invalid.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'nullable' rule for field '{$field}' does not accept parameters.");
        }
    }
}
