<?php

namespace YorCreative\DataValidation\Rules;

use InvalidArgumentException;

interface ValidationRuleInterface
{
    /**
     * Validate the given field value against the rule.
     *
     * @param string $field The field name being validated.
     * @param mixed $value The value to validate.
     * @param array $parameters Parameters for the rule (e.g., min value for 'min' rule).
     * @param array $data The full data array for cross-field validation.
     * @return bool True if validation passes, false otherwise.
     */
    public function validate(string $field, $value, array $parameters, array $data): bool;

    /**
     * Get the error message for a failed validation.
     *
     * @param string $field The field name.
     * @param array $parameters Rule parameters.
     * @param string|null $customMessage Custom message if provided.
     * @return string The formatted error message.
     */
    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string;

    /**
     * Validate the parameters provided for the rule.
     *
     * @param string $field The field name being validated.
     * @param array $parameters The parameters to validate.
     * @throws InvalidArgumentException If parameters are invalid.
     */
    public function validateParameters(string $field, array $parameters): void;
}
