<?php
namespace Base\Interfaces;

interface ResponseInterface
{
    public static function json(array $data, int $status = 200): void;
    public static function text(string $message, int $status = 200): void;
    public static function html(
        string $view,
        mixed $data,
        ViewInterface $renderer,
        int $status = 200
    ): void;
    public static function xml(array $data, int $status = 200): void;
    public static function csv(array $data, int $status = 200): void;
}
