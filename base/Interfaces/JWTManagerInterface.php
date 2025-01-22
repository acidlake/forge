<?php
namespace Base\Interfaces;

interface JWTManagerInterface
{
    public function generateToken(
        array $payload,
        int $expiresIn = 3600
    ): string;
    public function validateToken(string $token): array;
}
