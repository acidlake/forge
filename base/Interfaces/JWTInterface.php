<?php
namespace Base\Interfaces;

interface JWTInterface
{
    public static function encode(
        array $payload,
        string $secretKey,
        string $algorithm = "HS256"
    ): string;

    public static function decode(
        string $token,
        string $secretKey,
        string $algorithm = "HS256"
    ): array;
}
