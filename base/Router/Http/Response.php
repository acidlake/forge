<?php
namespace Base\Router\Http;

use Base\Interfaces\ResponseInterface;

class Response implements ResponseInterface
{
    /**
     * Send a JSON response.
     */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: application/json");
        echo json_encode($data);
        exit();
    }

    /**
     * Send a plain text response.
     */
    public static function text(string $message, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: text/plain");
        echo $message;
        exit();
    }

    /**
     * Send an HTML response.
     */
    public static function html(string $content, int $status = 200): void
    {
        http_response_code($status);
        header("Content-Type: text/html");
        echo $content;
        exit();
    }
}
