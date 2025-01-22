<?php
namespace Base\Interfaces;

interface ValidatorInterface
{
    // Method to validate the data with a set of rules
    public function validate(array $rules): ValidatorInterface;

    // Method to check if validation has failed
    public function fails(): bool;

    // Method to retrieve validation errors
    public function errors(): array;

    // Method to get the original data being validated
    public function getData(): array;
}
