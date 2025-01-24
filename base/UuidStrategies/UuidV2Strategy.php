<?php

namespace Base\UuidStrategies;

use Base\Interfaces\UuidStrategyInterface;

class UuidV2Strategy implements UuidStrategyInterface
{
    public function generate(): string
    {
        $time = microtime(true);
        $random = random_int(1000, 9999);
        return sprintf(
            "%08x-%04x-%04x-%04x-%08x",
            $time,
            $random,
            $random,
            $random,
            $time
        );
    }
}
