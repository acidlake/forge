<?php
namespace Base\Interfaces;

interface ExceptionInterface
{
    // Method to retrieve the errors or details associated with the exception
    public function getErrors(): array;

    // Method to set the error details (optional)
    public function setErrors(array $errors): void;
}
