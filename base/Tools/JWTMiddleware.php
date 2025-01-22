<?php
namespace Base\Tools;

use Base\Interfaces\JWTManagerInterface;
use Base\Interfaces\JWTMiddlewareInterface;

class JWTMiddleware implements JWTMiddlewareInterface
{
    private JWTManagerInterface $jwtManager;

    public function __construct(JWTManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    public function handle($request, $next)
    {
        $token = $request->getHeader("Authorization") ?? null;
        if (!$token) {
            throw new \RuntimeException("Unauthorized: No token provided.");
        }

        try {
            $payload = $this->jwtManager->validateToken($token);
            $request->setAttribute("user", $payload["user"]);
        } catch (\Exception $e) {
            throw new \RuntimeException("Unauthorized: Invalid token.");
        }

        return $next($request);
    }
}
