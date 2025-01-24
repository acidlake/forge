<?php
namespace Base\Interfaces;

interface AuthManagerInterface
{
    public function login(string $username, string $password): array;
    public function logout(string $refreshToken): void;
    public function refreshToken(string $refreshToken): array;
    public function validateJWT(string $token): array;
}
