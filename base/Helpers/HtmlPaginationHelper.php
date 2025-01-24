<?php
namespace Base\Helpers;

class HtmlPaginationHelper
{
    private int $currentPage = 1;
    private int $totalPages = 1;
    private string $baseUrl;
    private bool $showLinks = true;
    private bool $showNextPrev = true;
    private int $maxLinks = 10;
    private string $class = "pagination";
    private string $activeClass = "active";
    private string $linkClass = "page-link";
    private string $ellipsis = "...";

    public function __construct()
    {
        $this->baseUrl = strtok($_SERVER["REQUEST_URI"] ?? "/", "?");
    }

    /**
     * Set the current page.
     *
     * @param int $page
     * @return $this
     */
    public function setCurrentPage(int $page): self
    {
        $this->currentPage = max(1, $page);
        return $this;
    }

    /**
     * Set the total number of pages.
     *
     * @param int $totalPages
     * @return $this
     */
    public function setTotalPages(int $totalPages): self
    {
        $this->totalPages = max(1, $totalPages);
        return $this;
    }

    /**
     * Set the base URL for pagination links.
     *
     * @param string $url
     * @return $this
     */
    public function setBaseUrl(string $url): self
    {
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * Configure the pagination display options.
     *
     * @param bool $showLinks Whether to show page links.
     * @param bool $showNextPrev Whether to show next/previous buttons.
     * @param int $maxLinks Max number of links to display.
     * @return $this
     */
    public function configure(
        bool $showLinks = true,
        bool $showNextPrev = true,
        int $maxLinks = 10
    ): self {
        $this->showLinks = $showLinks;
        $this->showNextPrev = $showNextPrev;
        $this->maxLinks = $maxLinks;
        return $this;
    }

    /**
     * Set the CSS classes for pagination elements.
     *
     * @param string $containerClass
     * @param string $activeClass
     * @param string $linkClass
     * @return $this
     */
    public function setClasses(
        string $containerClass,
        string $activeClass,
        string $linkClass
    ): self {
        $this->class = $containerClass;
        $this->activeClass = $activeClass;
        $this->linkClass = $linkClass;
        return $this;
    }

    /**
     * Set the ellipsis string.
     *
     * @param string $ellipsis
     * @return $this
     */
    public function setEllipsis(string $ellipsis): self
    {
        $this->ellipsis = $ellipsis;
        return $this;
    }

    /**
     * Generate the pagination HTML.
     *
     * @return string
     */
    public function render(): string
    {
        if ($this->totalPages <= 1) {
            return ""; // No pagination needed for a single page
        }

        $html = "<ul class='{$this->class}'>";

        // Previous Button
        if ($this->showNextPrev) {
            $prevDisabled = $this->currentPage === 1 ? "disabled" : "";
            $prevLink =
                $this->currentPage > 1
                    ? $this->buildLink($this->currentPage - 1)
                    : "#";
            $html .= "<li class='{$prevDisabled}'><a href='{$prevLink}' class='{$this->linkClass}'>Previous</a></li>";
        }

        // Page Links
        if ($this->showLinks) {
            $start = max(
                1,
                $this->currentPage - (int) floor($this->maxLinks / 2)
            );
            $end = min($this->totalPages, $start + $this->maxLinks - 1);

            if ($end - $start + 1 < $this->maxLinks && $start > 1) {
                $start = max(1, $end - $this->maxLinks + 1);
            }

            if ($start > 1) {
                $html .= $this->buildPageLink(1);
                if ($start > 2) {
                    $html .= "<li class='ellipsis'>{$this->ellipsis}</li>";
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                $activeClass =
                    $i === $this->currentPage ? $this->activeClass : "";
                $html .= $this->buildPageLink($i, $activeClass);
            }

            if ($end < $this->totalPages) {
                if ($end < $this->totalPages - 1) {
                    $html .= "<li class='ellipsis'>{$this->ellipsis}</li>";
                }
                $html .= $this->buildPageLink($this->totalPages);
            }
        }

        // Next Button
        if ($this->showNextPrev) {
            $nextDisabled =
                $this->currentPage === $this->totalPages ? "disabled" : "";
            $nextLink =
                $this->currentPage < $this->totalPages
                    ? $this->buildLink($this->currentPage + 1)
                    : "#";
            $html .= "<li class='{$nextDisabled}'><a href='{$nextLink}' class='{$this->linkClass}'>Next</a></li>";
        }

        $html .= "</ul>";
        return $html;
    }

    /**
     * Build a single page link.
     *
     * @param int $page
     * @param string $extraClass
     * @return string
     */
    private function buildPageLink(int $page, string $extraClass = ""): string
    {
        $link = $this->buildLink($page);
        $class = trim("{$this->linkClass} $extraClass");
        return "<li class='{$class}'><a href='{$link}' class='{$this->linkClass}'>{$page}</a></li>";
    }

    /**
     * Build a paginated link URL.
     *
     * @param int $page
     * @return string
     */
    private function buildLink(int $page): string
    {
        return "{$this->baseUrl}?page={$page}";
    }
}
