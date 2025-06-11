<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class IntegerRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return is_int($value) || (is_string($value) && ctype_digit(ltrim($value, '-')));
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? sprintf('The %s must be an integer.', $field);
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'integer' rule for field '{$field}' does not accept parameters.");
        }
    }
}
