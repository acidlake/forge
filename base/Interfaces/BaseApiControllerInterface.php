<?php
namespace Base\Interfaces;

interface BaseApiControllerInterface
{
    public function success(
        $data = [],
        string $message = "Success",
        $meta = []
    ): array;

    public function error(
        string $message,
        int $code = 400,
        $errors = null
    ): array;

    public function validationError(array $errors): array;
}
