<?php
namespace Base\Router\Http;

use Base\Interfaces\RequestInterface;

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
        $this->body =
            $_SERVER["CONTENT_TYPE"] === "application/json"
                ? json_decode(file_get_contents("php://input"), true) ?? []
                : $_POST;

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
     * Get a specific body parameter.
     */
    public function input(string $key, $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }
}
