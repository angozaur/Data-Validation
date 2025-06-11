<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

class UrlRule implements ValidationRuleInterface
{
    private const VALID_SCHEMES = ['http', 'https', 'ftp', 'ftps', 'mailto', 'file', 'data'];

    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        if (!is_string($value) || filter_var($value, FILTER_VALIDATE_URL) === false) {
            return false;
        }
        $parsed = parse_url($value);
        return isset($parsed['scheme']) && in_array(strtolower($parsed['scheme']), self::VALID_SCHEMES, true);
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        return $customMessage ?? 'The :attribute must be a valid URL.';
    }

    public function validateParameters(string $field, array $parameters): void
    {
        if (!empty($parameters)) {
            throw new InvalidArgumentException("The 'url' rule for field '{$field}' does not accept parameters.");
        }
    }
}
