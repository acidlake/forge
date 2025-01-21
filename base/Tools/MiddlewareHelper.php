<?php

namespace Base\Tools;

/**
 * MiddlewareHelper provides a collection of reusable middleware for the Forge framework.
 *
 * This class includes various middleware to handle tasks like authentication, rate limiting,
 * security headers, request logging, and more.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */
class MiddlewareHelper
{
    /**
     * Middleware to set JSON response headers.
     *
     * Ensures all responses have a `Content-Type: application/json` header.
     *
     * @return callable The middleware handler.
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
     * Middleware to enforce authentication using Authorization headers.
     *
     * If the `Authorization` header is not present, the request is rejected with a 401 error.
     *
     * @return callable The middleware handler.
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
     * Middleware to handle Cross-Origin Resource Sharing (CORS).
     *
     * Adds headers to allow cross-origin requests from any origin.
     *
     * @return callable The middleware handler.
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
     * Middleware for implementing a circuit breaker.
     *
     * Limits service availability after a specified number of failures within a time window.
     *
     * @param int $maxFailures The maximum number of allowed failures.
     * @param int $resetTime   The time window for resetting failure count, in seconds.
     *
     * @return callable The middleware handler.
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
                    throw $e;
                }
            };
        };
    }

    /**
     * Middleware to enforce rate limiting on incoming requests.
     *
     * Limits the number of requests allowed from a single IP address within a specified time window.
     * If the limit is exceeded, a 429 "Too Many Requests" error is returned.
     *
     * @param int $maxRequests   The maximum number of allowed requests.
     * @param int $windowSeconds The time window for rate limiting, in seconds.
     *
     * @return callable The middleware handler.
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
     * Middleware to validate required fields in a request payload for specific routes.
     *
     * Ensures that required fields are present and meet specified constraints such as
     * minimum/maximum length or matching a regex pattern.
     * If a validation rule fails, a 400 "Bad Request" error is returned with an error message.
     *
     * @param array $rules An associative array of validation rules, where each key is a field name
     *                     and the value is an array of constraints (e.g., 'required', 'min', 'max', 'pattern').
     *
     * @return callable The middleware handler.
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
     * Middleware to log every incoming request to a specified log file.
     *
     * Logs details such as the request method, URI, IP address, and timestamp.
     *
     * @param string $logFile The path to the log file where request details will be saved.
     *
     * @return callable The middleware handler.
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
     * Middleware to enforce an IP whitelist for accessing routes.
     *
     * Only allows requests from specified IP addresses. If a request comes from an unauthorized IP,
     * a 403 "Forbidden" error is returned.
     *
     * @param array $allowedIps An array of allowed IP addresses.
     *
     * @return callable The middleware handler.
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
     * Middleware to validate API key authentication.
     *
     * Checks for the presence of a valid API key in the `X-API-KEY` header. If the key is missing or invalid,
     * a 401 "Unauthorized" error is returned.
     *
     * @param string $expectedKey The expected API key for authentication.
     *
     * @return callable The middleware handler.
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
     * Middleware to compress the response using Gzip.
     *
     * Compresses the response output to reduce the size of data transferred to the client.
     * Uses `ob_gzhandler` for compression.
     *
     * @return callable The middleware handler.
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
     * Middleware to apply common security headers to all responses.
     *
     * Sets headers such as `X-Content-Type-Options`, `X-Frame-Options`, and `X-XSS-Protection`.
     *
     * @return callable The middleware handler.
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
