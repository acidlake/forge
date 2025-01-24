<?php

namespace Base\Interfaces;

interface UuidStrategyInterface
{
    /**
     * Generate a UUID based on the strategy.
     *
     * @return string The generated UUID.
     */
    public function generate(): string;
}
?>
