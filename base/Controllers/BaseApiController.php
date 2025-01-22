<?php
namespace Base\Controllers;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\BaseApiControllerInterface;
use Base\Interfaces\ModelSerializerHelperInterface;
use Base\Exceptions\ValidationException;

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
        if (!in_array($code, [400, 422, 500, 403, 404], true)) {
            $code = 400;
        }

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

    public function handleValidation($callback)
    {
        try {
            return $callback();
        } catch (ValidationException $e) {
            $errors = $e->getErrors(); // Assuming your ValidationException has a method to get errors.
            $formattedErrors = $this->formatValidationErrors($errors);
            return $this->validationError($formattedErrors);
        }
    }

    private function formatValidationErrors(array $errors): array
    {
        $formatted = [];
        foreach ($errors as $field => $messages) {
            $formatted[] = [
                "field" => $field,
                "messages" => $messages, // This assumes $messages is an array.
            ];
        }
        return $formatted;
    }
}
