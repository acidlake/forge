<?php

namespace Base\Interfaces;

/**
 * Interface MiddlewareInterface
 *
 * Defines the contract for middleware classes, which process an HTTP request
 * and can perform actions either before or after passing the request to the
 * next middleware or handler in the stack.
 */
interface MiddlewareInterface
{
    /**
     * Handle an incoming HTTP request.
     *
     * Middleware classes implementing this interface should perform operations
     * on the provided request (e.g., adding headers, validating inputs,
     * injecting metadata) and then pass the request to the next middleware
     * in the chain by invoking `$next($request)`.
     *
     * If desired, middleware may terminate the request lifecycle by directly
     * returning a response without calling `$next($request)`.
     *
     * @param mixed $request The HTTP request object being processed.
     * @param callable $next The next middleware or handler to process the request.
     *
     * @return mixed The response or modified request after processing.
     */
    public function handle($request, $next): mixed;
}
