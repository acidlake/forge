<?php

namespace Base\Interfaces;

interface CommandInterface
{
    public function getName(): string;

    public function execute(array $arguments = []): void;
}
