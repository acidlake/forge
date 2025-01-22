<?php
namespace Base\Controllers;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\BaseApiControllerInterface;
use Base\Interfaces\ModelSerializerHelperInterface;

class BaseApiController implements BaseApiControllerInterface
{
    use ContainerAwareTrait;

    public function success(
        $data = [],
        string $message = "Success",
        $meta = []
    ): array {
        /**
        @var ModelSerializerHelperInterface $serializer
        */
        $serializer = $this->resolve(ModelSerializerHelperInterface::class);
        $data = $serializer::serialize($data);

        echo json_encode([
            "success" => true,
            "data" => $data,
            "message" => $message,
            "meta" => $meta,
            "errors" => null,
        ]);

        exit();
    }

    public function error(
        string $message,
        int $code = 400,
        $errors = null
    ): array {
        http_response_code($code);
        echo json_encode([
            "success" => false,
            "data" => null,
            "message" => $message,
            "meta" => [],
            "errors" => $errors,
        ]);

        exit();
    }

    public function validationError(array $errors): array
    {
        return $this->error("Validation failed", 422, $errors);
    }
}
