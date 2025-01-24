<?php

namespace Base\Helpers;

use DateTime;
use DateInterval;

/**
 * Class DateHelper
 * Provides utility methods for working with dates and times.
 */
class DateHelper
{
    /**
     * Formats a date string into a specified format.
     *
     * This method converts a date string into a formatted date string based on the provided format.
     * It uses PHP's `DateTime` class to parse and format the date.
     *
     * @param string $date The date string to format.
     * @param string $format The format to output (default is "Y-m-d H:i:s").
     *
     * @return string The formatted date string.
     */
    public static function format(
        string $date,
        string $format = "Y-m-d H:i:s"
    ): string {
        return (new DateTime($date))->format($format);
    }

    /**
     * Checks if a date is in the past.
     *
     * This method compares the given date with the current date to determine if it's in the past.
     *
     * @param string $date The date string to check.
     *
     * @return bool True if the date is in the past, otherwise false.
     */
    public static function isPast(string $date): bool
    {
        return new DateTime($date) < new DateTime();
    }

    /**
     * Checks if a date is in the future.
     *
     * This method compares the given date with the current date to determine if it's in the future.
     *
     * @param string $date The date string to check.
     *
     * @return bool True if the date is in the future, otherwise false.
     */
    public static function isFuture(string $date): bool
    {
        return new DateTime($date) > new DateTime();
    }

    /**
     * Returns a human-readable string representing the time ago from the current time.
     *
     * This method converts a date string into a human-readable "time ago" format, like "5 minutes ago",
     * "2 days ago", etc.
     *
     * @param string $date The date string to convert to "time ago" format.
     *
     * @return string A human-readable time difference.
     */
    public static function timeAgo(string $date): string
    {
        $time = strtotime($date);
        $diff = time() - $time;

        if ($diff < 60) {
            return "$diff seconds ago";
        } elseif ($diff < 3600) {
            return floor($diff / 60) . " minutes ago";
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . " hours ago";
        }

        return floor($diff / 86400) . " days ago";
    }

    /**
     * Adds a specified number of days to a given date.
     *
     * This method adds a given number of days to a specified date and returns the new date.
     *
     * @param string $date The original date to which days will be added.
     * @param int $days The number of days to add.
     *
     * @return string The new date after adding the specified days.
     */
    public static function addDays(string $date, int $days): string
    {
        $dateTime = new DateTime($date);
        $dateTime->add(new DateInterval("P{$days}D"));
        return $dateTime->format("Y-m-d");
    }

    /**
     * Subtracts a specified number of days from a given date.
     *
     * This method subtracts a given number of days from a specified date and returns the new date.
     *
     * @param string $date The original date from which days will be subtracted.
     * @param int $days The number of days to subtract.
     *
     * @return string The new date after subtracting the specified days.
     */
    public static function subtractDays(string $date, int $days): string
    {
        $dateTime = new DateTime($date);
        $dateTime->sub(new DateInterval("P{$days}D"));
        return $dateTime->format("Y-m-d");
    }

    /**
     * Checks if a date string is a valid date format.
     *
     * This method tries to create a `DateTime` object from the given string and returns true if the
     * string is a valid date format, otherwise false.
     *
     * @param string $date The date string to check.
     *
     * @return bool True if the date is valid, otherwise false.
     */
    public static function isValidDate(string $date): bool
    {
        return (bool) strtotime($date);
    }

    /**
     * Calculates the difference in days between two dates.
     *
     * This method calculates the number of days between two given dates.
     *
     * @param string $startDate The start date.
     * @param string $endDate The end date.
     *
     * @return int The number of days between the two dates.
     */
    public static function dateDifferenceInDays(
        string $startDate,
        string $endDate
    ): int {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);
        return $interval->days;
    }
}
