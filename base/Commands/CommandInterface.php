<?php

namespace Base\Commands;

interface CommandInterface
{
    public function getName(): string;

    public function handle(array $args): void;
}
