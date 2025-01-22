<?php
namespace Base\Controllers;

use Base\Interfaces\BaseApiControllerInterface;

class BaseApiController implements BaseApiControllerInterface
{
    public function success(
        $data = [],
        string $message = "Success",
        $meta = []
    ): array {
        return [
            "success" => true,
            "data" => $data,
            "message" => $message,
            "meta" => $meta,
            "errors" => null,
        ];
    }

    public function error(
        string $message,
        int $code = 400,
        $errors = null
    ): array {
        http_response_code($code);
        return [
            "success" => false,
            "data" => null,
            "message" => $message,
            "meta" => [],
            "errors" => $errors,
        ];
    }

    public function validationError(array $errors): array
    {
        return $this->error("Validation failed", 422, $errors);
    }
}
