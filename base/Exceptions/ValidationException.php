<?php
namespace Base\Exceptions;

use Base\Interfaces\ExceptionInterface;
use Exception;

class ValidationException extends Exception implements ExceptionInterface
{
    protected array $errors;

    public function __construct(
        array $errors,
        $message = "Validation failed.",
        $code = 422
    ) {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    // Optional: Method to allow setting errors after initialization (if needed)
    public function setErrors(array $errors): void
    {
        $this->errors = $errors;
    }
}
