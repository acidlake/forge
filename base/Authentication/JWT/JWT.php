<?php

namespace Base\Authentication\JWT;

use Base\Interfaces\JWTInterface;

class JWT implements JWTInterface
{
    /**
     * Encode a payload into a JWT token.
     *
     * @param array $payload The payload data.
     * @param string $secretKey The secret key used to sign the token.
     * @param string $algorithm The algorithm used for signing (e.g., HS256).
     *
     * @return string The encoded JWT token.
     */
    public static function encode(
        array $payload,
        string $secretKey,
        string $algorithm = "HS256"
    ): string {
        // Create header
        $header = [
            "typ" => "JWT",
            "alg" => $algorithm,
        ];
        $headerEncoded = self::base64UrlEncode(json_encode($header));

        // Create payload
        $payloadEncoded = self::base64UrlEncode(json_encode($payload));

        // Create signature
        $signature = self::sign(
            "{$headerEncoded}.{$payloadEncoded}",
            $secretKey,
            $algorithm
        );
        $signatureEncoded = self::base64UrlEncode($signature);

        // Combine and return the token
        return "{$headerEncoded}.{$payloadEncoded}.{$signatureEncoded}";
    }

    /**
     * Decode a JWT token.
     *
     * @param string $token The JWT token.
     * @param string $secretKey The secret key to verify the token.
     * @param string $algorithm The algorithm used for signing (e.g., HS256).
     *
     * @return array The decoded payload.
     * @throws \InvalidArgumentException If the token is invalid.
     */
    public static function decode(
        string $token,
        string $secretKey,
        string $algorithm = "HS256"
    ): array {
        $parts = explode(".", $token);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException("Invalid JWT token structure.");
        }

        [$headerEncoded, $payloadEncoded, $signatureEncoded] = $parts;

        // Decode header and payload
        $header = json_decode(self::base64UrlDecode($headerEncoded), true);
        $payload = json_decode(self::base64UrlDecode($payloadEncoded), true);
        $signature = self::base64UrlDecode($signatureEncoded);

        // Verify signature
        $validSignature = self::sign(
            "{$headerEncoded}.{$payloadEncoded}",
            $secretKey,
            $algorithm
        );
        if (!hash_equals($signature, $validSignature)) {
            throw new \InvalidArgumentException("Invalid token signature.");
        }

        // Verify expiration time
        if (isset($payload["exp"]) && time() > $payload["exp"]) {
            throw new \InvalidArgumentException("Token has expired.");
        }

        return $payload;
    }

    /**
     * Sign a message using the given algorithm and secret key.
     *
     * @param string $message The message to sign.
     * @param string $secretKey The secret key.
     * @param string $algorithm The signing algorithm (e.g., HS256).
     *
     * @return string The signature.
     */
    private static function sign(
        string $message,
        string $secretKey,
        string $algorithm
    ): string {
        switch ($algorithm) {
            case "HS256":
                return hash_hmac("sha256", $message, $secretKey, true);
            default:
                throw new \InvalidArgumentException(
                    "Unsupported algorithm: {$algorithm}"
                );
        }
    }

    /**
     * Encode data into Base64 URL format.
     *
     * @param string $data The data to encode.
     *
     * @return string The Base64 URL encoded string.
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), "+/", "-_"), "=");
    }

    /**
     * Decode Base64 URL encoded data.
     *
     * @param string $data The encoded data.
     *
     * @return string The decoded string.
     */
    private static function base64UrlDecode(string $data): string
    {
        return base64_decode(strtr($data, "-_", "+/"));
    }
}
