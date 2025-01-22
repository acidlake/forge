<?php
namespace Base\Authentication\JWT;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\JWTInterface;
use Base\Interfaces\JWTManagerInterface;

class JWTManager implements JWTManagerInterface
{
    use ContainerAwareTrait;

    private string $secretKey;
    private string $algorithm;
    private JWTInterface $jwt;

    public function __construct(array $config)
    {
        $this->jwt = $this->resolve(JWTInterface);

        $this->secretKey = $config["jwt"]["secret"] ?? "";
        $this->algorithm = $config["jwt"]["algorithm"] ?? "HS256";

        if (empty($this->secretKey)) {
            throw new \RuntimeException("JWT secret key is not set.");
        }
    }

    public function generateToken(array $payload, int $expiresIn = 3600): string
    {
        $payload["exp"] = time() + $expiresIn;
        return $this->jwt->encode($payload, $this->secretKey, $this->algorithm);
    }

    public function validateToken(string $token): array
    {
        return $this->jwt->decode($token, $this->secretKey, $this->algorithm);
    }
}
