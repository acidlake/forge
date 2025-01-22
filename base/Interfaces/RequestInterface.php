<?php
namespace Base\Interfaces;

interface RequestInterface
{
    public function header(string $key, $default = null): ?string;
    public function query(string $key, $default = null): mixed;
    public function input(string $key, $default = null): mixed;
}
