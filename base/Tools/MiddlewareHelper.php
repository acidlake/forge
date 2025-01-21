<?php

namespace Base\Tools;

class MiddlewareHelper
{
    /**
     * Middleware to set JSON response headers.
     *
     * @return callable
     */
    public static function jsonResponse(): callable
    {
        return function (callable $handler) {
            return function (...$args) use ($handler) {
                header("Content-Type: application/json");
                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to set AUTHORIZATION headers.
     *
     * @return callable
     */
    public static function auth(): callable
    {
        return function (callable $handler) {
            return function (...$args) use ($handler) {
                if (!isset($_SERVER["HTTP_AUTHORIZATION"])) {
                    http_response_code(401);
                    echo json_encode(["error" => "Unauthorized"]);
                    exit();
                }
                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to set CORS  headers.
     *
     * @return callable
     */
    public static function cors(): callable
    {
        return function (callable $handler) {
            return function (...$args) use ($handler) {
                header("Access-Control-Allow-Origin: *");
                header("Access-Control-Allow-Methods: GET, POST, PUT");
                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to set Circuit Breaker.
     *
     * @return callable
     */
    public static function circuitBreaker(
        int $maxFailures,
        int $resetTime
    ): callable {
        $failureCount = 0;
        $lastFailureTime = null;

        return function (callable $handler) use (
            &$failureCount,
            &$lastFailureTime,
            $maxFailures,
            $resetTime
        ) {
            return function (...$args) use (
                $handler,
                &$failureCount,
                &$lastFailureTime,
                $maxFailures,
                $resetTime
            ) {
                if (
                    $failureCount >= $maxFailures &&
                    time() - $lastFailureTime < $resetTime
                ) {
                    http_response_code(503);
                    echo json_encode([
                        "error" => "Service temporarily unavailable",
                    ]);
                    return;
                }

                try {
                    $response = $handler(...$args);
                    $failureCount = 0; // Reset on success
                    return $response;
                } catch (\Exception $e) {
                    $failureCount++;
                    $lastFailureTime = time();
                    throw $e; // Re-throw the exception after recording the failure
                }
            };
        };
    }

    /**
     * Middleware to set Rate Limit.
     *
     * @param int $maxRequests Max allowed requests
     * @param int $$windowSeconds Amount of seconds
     *
     * @return callable
     */
    public static function rateLimit(
        int $maxRequests,
        int $windowSeconds
    ): callable {
        $requestCounts = [];

        return function (callable $handler) use (
            &$requestCounts,
            $maxRequests,
            $windowSeconds
        ) {
            return function (...$args) use (
                $handler,
                &$requestCounts,
                $maxRequests,
                $windowSeconds
            ) {
                $clientIp = $_SERVER["REMOTE_ADDR"] ?? "unknown";
                $currentTime = time();

                // Clean up old requests
                if (isset($requestCounts[$clientIp])) {
                    $requestCounts[$clientIp] = array_filter(
                        $requestCounts[$clientIp],
                        function ($timestamp) use (
                            $currentTime,
                            $windowSeconds
                        ) {
                            return $currentTime - $timestamp < $windowSeconds;
                        }
                    );
                }

                // Initialize or update request count
                $requestCounts[$clientIp] = $requestCounts[$clientIp] ?? [];
                $requestCounts[$clientIp][] = $currentTime;

                if (count($requestCounts[$clientIp]) > $maxRequests) {
                    http_response_code(429);
                    echo json_encode(["error" => "Too many requests"]);
                    return;
                }

                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to set Validate required fields in a request
     * payload for spesific routes.
     *
     * @return callable
     */
    public static function validate(array $rules): callable
    {
        return function (callable $handler) use ($rules) {
            return function (...$args) use ($handler, $rules) {
                // Parse request payload
                $input =
                    json_decode(file_get_contents("php://input"), true) ??
                    $_POST;

                foreach ($rules as $field => $rule) {
                    // Check if the field is required and missing
                    if (
                        ($rule["required"] ?? false) &&
                        !isset($input[$field])
                    ) {
                        http_response_code(400);
                        echo json_encode([
                            "error" => "Missing required field: $field",
                        ]);
                        return;
                    }

                    if (isset($input[$field])) {
                        $value = $input[$field];

                        // Check for minimum length
                        if (
                            isset($rule["min"]) &&
                            strlen($value) < $rule["min"]
                        ) {
                            http_response_code(400);
                            echo json_encode([
                                "error" => "Field '$field' must be at least {$rule["min"]} characters",
                            ]);
                            return;
                        }

                        // Check for maximum length
                        if (
                            isset($rule["max"]) &&
                            strlen($value) > $rule["max"]
                        ) {
                            http_response_code(400);
                            echo json_encode([
                                "error" => "Field '$field' must not exceed {$rule["max"]} characters",
                            ]);
                            return;
                        }

                        // Check for regex pattern
                        if (
                            isset($rule["pattern"]) &&
                            !preg_match($rule["pattern"], $value)
                        ) {
                            http_response_code(400);
                            echo json_encode([
                                "error" => "Invalid value for field: $field",
                            ]);
                            return;
                        }
                    }
                }

                // If all validations pass, proceed to the handler
                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to set Log every request to a file.
     *
     * @return callable
     */
    public static function logRequests(string $logFile): callable
    {
        return function (callable $handler) use ($logFile) {
            return function (...$args) use ($handler, $logFile) {
                $requestData = [
                    "method" => $_SERVER["REQUEST_METHOD"],
                    "uri" => $_SERVER["REQUEST_URI"],
                    "ip" => $_SERVER["REMOTE_ADDR"] ?? "unknown",
                    "timestamp" => date("Y-m-d H:i:s"),
                ];

                file_put_contents(
                    $logFile,
                    json_encode($requestData) . PHP_EOL,
                    FILE_APPEND
                );
                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to set Whitelist Ip.
     *
     * @return callable
     */
    public static function ipWhitelist(array $allowedIps): callable
    {
        return function (callable $handler) use ($allowedIps) {
            return function (...$args) use ($handler, $allowedIps) {
                $clientIp = $_SERVER["REMOTE_ADDR"] ?? "unknown";
                if (!in_array($clientIp, $allowedIps)) {
                    http_response_code(403);
                    echo json_encode(["error" => "Access denied"]);
                    return;
                }
                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to allow API Key Validation.
     *
     * @return callable
     */
    public static function apiKey(string $expectedKey): callable
    {
        return function (callable $handler) use ($expectedKey) {
            return function (...$args) use ($handler, $expectedKey) {
                $providedKey = $_SERVER["HTTP_X_API_KEY"] ?? null;

                if ($providedKey !== $expectedKey) {
                    http_response_code(401);
                    echo json_encode(["error" => "Invalid API key"]);
                    return;
                }

                return $handler(...$args);
            };
        };
    }

    /**
     * Middleware to Compress the response using Gzip.
     *
     * @return callable
     */
    public static function compress(): callable
    {
        return function (callable $handler) {
            return function (...$args) use ($handler) {
                ob_start("ob_gzhandler");
                $response = $handler(...$args);
                ob_end_flush();
                return $response;
            };
        };
    }

    /**
     * Middleware to set Common security headers to al response.
     *
     * @return callable
     */
    public static function securityHeaders(): callable
    {
        return function (callable $handler) {
            return function (...$args) use ($handler) {
                header("X-Content-Type-Options: nosniff");
                header("X-Frame-Options: DENY");
                header("X-XSS-Protection: 1; mode=block");
                return $handler(...$args);
            };
        };
    }
}
