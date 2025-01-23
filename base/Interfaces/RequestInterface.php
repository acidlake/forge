<?php

namespace Base\Interfaces;

/**
 * Interface for HTTP request handling.
 */
interface RequestInterface
{
    /**
     * Retrieve a value from the request headers.
     *
     * @param string $key The header key to retrieve.
     * @param mixed|null $default The default value to return if the header is not found. Defaults to null.
     * @return string|null The value of the header or the default value if not found.
     */
    public function header(string $key, $default = null): ?string;

    /**
     * Retrieve a value from the query string.
     *
     * @param string $key The query parameter key to retrieve.
     * @param mixed|null $default The default value to return if the query parameter is not found. Defaults to null.
     * @return mixed The value of the query parameter or the default value if not found.
     */
    public function query(string $key, $default = null): mixed;

    /**
     * Retrieve a value from the request input.
     *
     * This can include form data, JSON payloads, or other input sources.
     *
     * @param string $key The input key to retrieve.
     * @param mixed|null $default The default value to return if the input key is not found. Defaults to null.
     * @return mixed The value of the input key or the default value if not found.
     */
    public function input(string $key, $default = null): mixed;

    /**
     * Retrieve the raw body or a specific key from the request body.
     *
     * If a key is provided, it attempts to retrieve that key from the parsed body.
     *
     * @param string|null $key The specific key to retrieve from the body. If null, the entire body is returned.
     * @param mixed|null $default The default value to return if the key is not found. Defaults to null.
     * @return mixed The raw body, the value of the key, or the default value if not found.
     */
    public function body(?string $key = null, $default = null): mixed;

    /**
     * Validate the request input against a set of rules.
     *
     * @param array $rules An associative array of validation rules, where the key is the input key and the value is the validation rule(s).
     * @return array The validated data. Throws an exception if validation fails.
     */
    public function validate(array $rules): array;
}
