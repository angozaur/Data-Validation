<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class SameRule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        $otherValue = $parameters[1] ?? null;
        return $value === $otherValue;
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must match :other.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (count($parameters) !== 1) {
            throw new InvalidArgumentException("The 'same' rule for field '{$field}' requires exactly one parameter.");
        }
    }
}
