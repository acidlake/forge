<?php

namespace Base\Controllers;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\BaseApiControllerInterface;
use Base\Interfaces\ModelSerializerHelperInterface;
use Base\Exceptions\ValidationException;

/**
 * Base API Controller
 *
 * Provides common methods for handling API responses and validation.
 */
class BaseApiController implements BaseApiControllerInterface
{
    use ContainerAwareTrait;

    /**
     * Respond with a success message and data.
     *
     * @param mixed $data The data to return in the response.
     * @param string $message A message describing the success.
     * @param array $meta Optional metadata to include in the response.
     * @return void
     */
    public function success(
        $data = [],
        string $message = "Success",
        $meta = []
    ): void {
        /**
         * @var ModelSerializerHelperInterface $serializer
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

    /**
     * Respond with a paginated success message and data.
     *
     * @param array $paginationData The paginated data and metadata.
     * @param string $message A message describing the success.
     * @return void
     */
    public function paginatedSuccess(
        array $paginationData,
        string $message = "Success"
    ): void {
        /**
         * @var ModelSerializerHelperInterface $serializer
         */
        $serializer = $this->resolve(ModelSerializerHelperInterface::class);

        $data = $serializer::serialize($paginationData["data"]);
        $pagination = $paginationData["pagination"] ?? [];

        echo json_encode([
            "success" => true,
            "data" => $data,
            "message" => $message,
            "meta" => $pagination,
            "errors" => null,
        ]);

        exit();
    }

    /**
     * Respond with an error message.
     *
     * @param string $message A message describing the error.
     * @param int $code The HTTP status code for the response. Defaults to 400.
     * @param mixed $errors Additional error details or context.
     * @return void
     */
    public function error(
        string $message,
        int $code = 400,
        $errors = null
    ): void {
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

    /**
     * Respond with a validation error message.
     *
     * @param array $errors An array of validation errors.
     * @return void
     */
    public function validationError(array $errors): void
    {
        $this->error("Validation failed", 422, $errors);
    }

    /**
     * Handle validation logic and return the result or validation error response.
     *
     * @param callable $callback A callback function that performs validation.
     * @return mixed The result of the callback if validation succeeds.
     */
    public function handleValidation($callback)
    {
        try {
            return $callback();
        } catch (ValidationException $e) {
            $errors = $e->getErrors();
            $formattedErrors = $this->formatValidationErrors($errors);
            $this->validationError($formattedErrors);
        }
    }

    /**
     * Format validation errors for the response.
     *
     * @param array $errors An associative array of validation errors.
     * @return array A formatted array of validation errors.
     */
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
