<?php
namespace Base\Database;

use Base\Interfaces\ORMDatabaseAdapterInterface;

abstract class BaseMigration
{
    protected ORMDatabaseAdapterInterface $adapter;
    protected BaseSchemaBuilder $schema;

    public function __construct(
        ORMDatabaseAdapterInterface $adapter,
        BaseSchemaBuilder $schema
    ) {
        $this->adapter = $adapter;
        $this->schema = $schema;
    }

    abstract public function up();
    abstract public function down();

    protected function raw(string $sql)
    {
        $this->adapter->query($sql);
    }
}
