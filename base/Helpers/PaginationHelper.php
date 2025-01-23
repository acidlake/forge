<?php

namespace Base\Helpers;

/**
 * Class PaginationHelper
 * Provides utility methods for paginating results.
 */
class PaginationHelper
{
    /**
     * Calculates pagination details based on the total items, items per page, and the current page.
     *
     * This method calculates the total number of pages, ensures that the current page is valid,
     * and returns pagination information such as the total items, items per page, current page, and total pages.
     *
     * @param int $total The total number of items to paginate.
     * @param int $perPage The number of items to display per page.
     * @param int $currentPage The current page number.
     *
     * @return array An associative array containing pagination information:
     *               - "total": The total number of items.
     *               - "perPage": The number of items per page.
     *               - "currentPage": The current page number.
     *               - "totalPages": The total number of pages.
     */
    public static function paginate(
        int $total,
        int $perPage,
        int $currentPage
    ): array {
        $totalPages = ceil($total / $perPage);
        $currentPage = max(1, min($totalPages, $currentPage));

        return [
            "total" => $total,
            "perPage" => $perPage,
            "currentPage" => $currentPage,
            "totalPages" => $totalPages,
        ];
    }
}
