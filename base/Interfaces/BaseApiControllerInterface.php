<?php
namespace Base\Interfaces;

interface BaseApiControllerInterface
{
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
    ): void;

    /**
     * Respond with an error message.
     *
     * @param string $message A message describing the error.
     * @param int $code The HTTP status code for the response.
     * @param mixed $errors Additional error details or context.
     * @return void
     */
    public function error(
        string $message,
        int $code = 400,
        $errors = null
    ): void;

    /**
     * Respond with a validation error message.
     *
     * @param array $errors An array of validation errors.
     * @return void
     */
    public function validationError(array $errors): void;
}
