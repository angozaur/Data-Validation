<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class Ipv6Rule implements ValidationRuleInterface
{
    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        return is_string($value) && filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false;
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be a valid IPv6 address.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'ipv6' rule for field '{$field}' does not accept parameters.");
        }
    }
}
