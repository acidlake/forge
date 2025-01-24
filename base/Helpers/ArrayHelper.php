<?php

namespace Base\Helpers;

/**
 * Class ArrayHelper
 * Provides utility methods for working with arrays.
 */
class ArrayHelper
{
    /**
     * Get a value from a nested array using dot notation.
     *
     * This method allows retrieving values from a multi-dimensional array using dot notation
     * for specifying the key path.
     *
     * @param array $array The array to search.
     * @param string $key The dot-notated key path to the value.
     * @param mixed $default The default value to return if the key is not found (optional).
     *
     * @return mixed The value at the specified key or the default value if the key doesn't exist.
     */
    public static function get(
        array $array,
        string $key,
        $default = null
    ): mixed {
        $keys = explode(".", $key);

        foreach ($keys as $k) {
            if (!isset($array[$k])) {
                return $default;
            }
            $array = $array[$k];
        }

        return $array;
    }

    /**
     * Check if an array is associative.
     *
     * This method checks if the given array is associative (has string keys).
     * It returns true if the array is associative, otherwise false.
     *
     * @param array $array The array to check.
     *
     * @return bool True if the array is associative, otherwise false.
     */
    public static function isAssociative(array $array): bool
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    /**
     * Flatten a multidimensional array.
     *
     * This method recursively flattens a multidimensional array into a single-dimensional array.
     *
     * @param array $array The multidimensional array to flatten.
     *
     * @return array The flattened array.
     */
    public static function flatten(array $array): array
    {
        $result = [];
        array_walk_recursive($array, function ($value) use (&$result) {
            $result[] = $value;
        });

        return $result;
    }

    /**
     * Remove duplicate values from an array.
     *
     * This method removes duplicate values from an array, returning only unique values.
     *
     * @param array $array The array to process.
     *
     * @return array The array with duplicates removed.
     */
    public static function removeDuplicates(array $array): array
    {
        return array_unique($array);
    }

    /**
     * Merge two arrays and remove duplicates.
     *
     * This method merges two arrays and removes duplicate values, returning a unique array.
     *
     * @param array $array1 The first array.
     * @param array $array2 The second array.
     *
     * @return array The merged array with duplicates removed.
     */
    public static function mergeAndRemoveDuplicates(
        array $array1,
        array $array2
    ): array {
        return array_unique(array_merge($array1, $array2));
    }

    /**
     * Sort an array by its values in ascending order.
     *
     * This method sorts the array in ascending order, preserving the keys.
     *
     * @param array $array The array to sort.
     *
     * @return array The sorted array.
     */
    public static function sortArray(array $array): array
    {
        asort($array);
        return $array;
    }

    /**
     * Get the last element of an array.
     *
     * This method returns the last element of the array.
     *
     * @param array $array The array to retrieve the last element from.
     *
     * @return mixed The last element of the array.
     */
    public static function last(array $array): mixed
    {
        return end($array);
    }

    /**
     * Get the first element of an array.
     *
     * This method returns the first element of the array.
     *
     * @param array $array The array to retrieve the first element from.
     *
     * @return mixed The first element of the array.
     */
    public static function first(array $array): mixed
    {
        return reset($array);
    }

    /**
     * Group an array of arrays by a specific key.
     *
     * This method groups a collection of arrays by a specified key. It creates a new array with
     * the values grouped under their respective key values.
     *
     * @param array $array The array of arrays to group.
     * @param string $key The key to group by.
     *
     * @return array The grouped array.
     */
    public static function groupBy(array $array, string $key): array
    {
        $grouped = [];
        foreach ($array as $item) {
            if (isset($item[$key])) {
                $grouped[$item[$key]][] = $item;
            }
        }

        return $grouped;
    }

    /**
     * Filter an array by a given callback function.
     *
     * This method filters the elements of an array based on a provided callback function.
     *
     * @param array $array The array to filter.
     * @param callable $callback The callback function to filter the array.
     *
     * @return array The filtered array.
     */
    public static function filter(array $array, callable $callback): array
    {
        return array_filter($array, $callback);
    }
}
