<?php
namespace Base\Database;

use Base\Database\DatabaseAdapterInterface;

abstract class BaseMigration
{
    protected DatabaseAdapterInterface $adapter;
    protected BaseSchemaBuilder $schema;

    public function __construct(
        DatabaseAdapterInterface $adapter,
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
