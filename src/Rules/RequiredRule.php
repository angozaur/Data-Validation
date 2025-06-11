<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class RequiredRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return !($value === null || $value === '');
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute field is required.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'required' rule for field '{$field}' does not accept parameters.");
        }
    }
}
