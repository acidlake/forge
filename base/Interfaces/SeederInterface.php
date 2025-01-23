<?php

namespace Base\Interfaces;
use Base\Database\DatabaseAdapterInterface;

interface SeederInterface
{
    public function __construct(DatabaseAdapterInterface $db);

    public function run(): void;

    public function rollback(): void;
}
