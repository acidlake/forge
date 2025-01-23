<?php
namespace Base\Router\Http;

use Base\Exceptions\ValidationException;
use Base\Interfaces\RequestInterface;
use Base\Validations\Validator;

class Request implements RequestInterface
{
    public array $headers = [];
    public array $query = [];
    public array $body = [];
    public string $method;
    public string $uri;

    public function __construct()
    {
        // Parse headers
        $this->headers = getallheaders();

        // Parse query parameters
        $this->query = $_GET;

        // Parse body (JSON or form-data)
        $contentType = $_SERVER["CONTENT_TYPE"] ?? "";
        if ($contentType === "application/json") {
            $this->body =
                json_decode(file_get_contents("php://input"), true) ?? [];
        } else {
            // Default to $_POST for form data or other methods
            $this->body = $_POST;
        }

        // Capture method and URI
        $this->method = $_SERVER["REQUEST_METHOD"];
        $this->uri = $_SERVER["REQUEST_URI"];
    }

    /**
     * Get a specific header.
     */
    public function header(string $key, $default = null): ?string
    {
        return $this->headers[$key] ?? $default;
    }

    /**
     * Get a specific query parameter.
     */
    public function query(string $key, $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Get a specific query parameter for pagination.
     */
    public function page(string $key = "page", int $default = 1): int
    {
        return (int) ($this->query[$key] ?? $default);
    }

    /**
     * Get the entire body or a specific body parameter.
     */
    public function body(?string $key = null, $default = null): mixed
    {
        if ($key === null) {
            return $this->body;
        }
        return $this->body[$key] ?? $default;
    }

    /**
     * Get a specific input parameter (query + body).
     */
    public function input(string $key, $default = null): mixed
    {
        return $this->body[$key] ?? ($this->query[$key] ?? $default);
    }

    /**
     * Validate the request data against the given rules.
     */
    public function validate(array $rules): array
    {
        $validator = new Validator(array_merge($this->query, $this->body));
        $validated = $validator->validate($rules);

        if ($validated->fails()) {
            throw new ValidationException($validated->errors());
        }

        return $validated->getData();
    }
}
