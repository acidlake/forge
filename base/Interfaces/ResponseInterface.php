<?php
namespace Base\Interfaces;

use Base\Templates\View;

interface ResponseInterface
{
    public static function json(array $data, int $status = 200): void;
    public static function text(string $message, int $status = 200): void;
    public static function html(
        string $view,
        mixed $data,
        View $renderer,
        int $status = 200
    ): void;
    public static function xml(array $data, int $status = 200): void;
    public static function csv(array $data, int $status = 200): void;
}
