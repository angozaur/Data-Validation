<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class BooleanRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return in_array($value, [true, false, 0, 1, '0', '1'], true);
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be a boolean value.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'boolean' rule for field '{$field}' does not accept parameters.");
        }
    }
}
