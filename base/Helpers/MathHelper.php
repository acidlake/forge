<?php

namespace Base\Helpers;

/**
 * Class MathHelper
 * Provides utility methods for common mathematical operations.
 */
class MathHelper
{
    /**
     * Rounds a number to a specified number of decimal places.
     *
     * This method uses PHP's built-in `round()` function to round a given number
     * to the specified number of decimal places.
     *
     * @param float $number The number to round.
     * @param int $decimals The number of decimal places to round to.
     *
     * @return float The rounded number.
     */
    public static function roundTo(float $number, int $decimals): float
    {
        return round($number, $decimals);
    }

    /**
     * Calculates the percentage of a partial value relative to a total value.
     *
     * This method calculates the percentage by dividing the partial value by the
     * total value and multiplying the result by 100.
     *
     * @param float $partial The partial value.
     * @param float $total The total value.
     *
     * @return float The percentage of the partial value relative to the total.
     */
    public static function percentage(float $partial, float $total): float
    {
        return ($partial / $total) * 100;
    }

    /**
     * Generates a random integer within a specified range.
     *
     * This method generates a random integer between the provided minimum and
     * maximum values (inclusive) using PHP's `mt_rand()` function.
     *
     * @param int $min The minimum value (inclusive).
     * @param int $max The maximum value (inclusive).
     *
     * @return int A random integer within the specified range.
     */
    public static function randomInRange(int $min, int $max): int
    {
        return mt_rand($min, $max);
    }
}
