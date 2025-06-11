<?php

namespace YorCreative\DataValidation\Exceptions;

use Exception;

class ValidationException extends Exception
{
    private string $field;

    public function __construct(string $field, string $message)
    {
        parent::__construct($message);
        $this->field = $field;
    }

    public function getField(): string
    {
        return $this->field;
    }
}
