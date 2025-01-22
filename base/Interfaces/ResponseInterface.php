<?php
namespace Base\Interfaces;

interface ResponseInterface
{
    public static function json(array $data, int $status = 200): void;
    public static function text(string $message, int $status = 200): void;
    public static function html(string $content, int $status = 200): void;
}
