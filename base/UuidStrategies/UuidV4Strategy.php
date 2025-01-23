<?php

namespace Base\UuidStrategies;

use Base\Interfaces\UuidStrategyInterface;

class UuidV4Strategy implements UuidStrategyInterface
{
    public function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}
