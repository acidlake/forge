<?php
namespace Base\Database;

use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\SeederInterface;

abstract class BaseSeeder implements SeederInterface
{
    use ContainerAwareTrait;
    /**
     * Seed the database with data.
     */
    abstract public function run(): void;

    /**
     * Access the database adapter directly for queries.
     */
    protected function db(): ORMDatabaseAdapterInterface
    {
        return $this->resolve(ORMDatabaseAdapterInterface::class);
    }
}
