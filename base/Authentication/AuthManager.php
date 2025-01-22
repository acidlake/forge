<?php
namespace Base\Authentication;

use Base\Interfaces\AuthManagerInterface;
use Base\Interfaces\JWTManagerInterface;
use Base\Interfaces\UserModelInterface;

class AuthManager implements AuthManagerInterface
{
    private JWTManagerInterface $jwtManager;
    private UserModelInterface $userModel;

    public function __construct(
        JWTManagerInterface $jwtManager,
        UserModelInterface $userModel
    ) {
        $this->jwtManager = $jwtManager;
        $this->userModel = $userModel;
    }

    public function login(string $username, string $password): array
    {
        $user = $this->userModel->findByUsername($username);

        if (!$user || !password_verify($password, $user->password)) {
            throw new \RuntimeException("Invalid credentials.");
        }

        $payload = ["user_id" => $user->id, "roles" => $user->roles];
        $accessToken = $this->jwtManager->generateToken($payload);
        $refreshToken = $this->jwtManager->generateRefreshToken($user->id);

        return [
            "access_token" => $accessToken,
            "refresh_token" => $refreshToken,
        ];
    }

    public function logout(string $refreshToken): void
    {
        $this->jwtManager->revokeRefreshToken($refreshToken);
    }

    public function refreshToken(string $refreshToken): array
    {
        $userId = $this->jwtManager->validateRefreshToken($refreshToken);
        $payload = ["user_id" => $userId];
        $accessToken = $this->jwtManager->generateToken($payload);

        return ["access_token" => $accessToken];
    }

    public function validateJWT(string $token): array
    {
        return $this->jwtManager->validateToken($token);
    }
}
