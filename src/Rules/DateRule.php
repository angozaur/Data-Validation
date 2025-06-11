<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class DateRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return is_string($value) && $value !== '' && strtotime($value) !== false;
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be a valid date.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'date' rule for field '{$field}' does not accept parameters.");
        }
    }
}
