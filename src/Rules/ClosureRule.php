<?php

namespace YorCreative\DataValidation\Rules;

use Closure;

class ClosureRule implements ValidationRuleInterface
{
    private Closure $closure;
    private ?string $defaultMessageTemplate;
    private ?string $lastErrorMessageFromFail = null;

    public function __construct(Closure $closure, ?string $defaultMessageTemplate = null)
    {
        $this->closure = $closure;
        $this->defaultMessageTemplate = $defaultMessageTemplate ?? 'The :attribute is invalid (custom validation).';
    }

    public function validate(string $field, $value, array $parameters, array $data): bool
    {
        $validationPassed = true;
        $this->lastErrorMessageFromFail = null;
        $fail = function ($message) use (&$validationPassed) {
            $validationPassed = false;
            $this->lastErrorMessageFromFail = $message;
        };

        try {
            $result = ($this->closure)($field, $value, $fail, $data);
            if ($result === false && $this->lastErrorMessageFromFail === null) {
                $validationPassed = false;
            }
        } catch (\Throwable $e) {
            $validationPassed = false;
            $this->lastErrorMessageFromFail = 'An error occurred during custom validation: ' . $e->getMessage();
        }

        return $validationPassed;
    }

    public function getErrorMessage(string $field, array $parameters, ?string $customMessage): string
    {
        if ($this->lastErrorMessageFromFail !== null) {
            return $this->lastErrorMessageFromFail;
        }
        return $customMessage ?? $this->defaultMessageTemplate;
    }

    public function validateParameters(string $field, array $parameters): void
    {
        // Closure rules may accept parameters, so no validation is enforced here
        // If specific parameter validation is needed, it should be handled in the closure itself
    }
}
