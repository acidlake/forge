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

    abstract public function up(): void;
    abstract public function down(): void;

    protected function raw(string $sql): void
    {
        $this->adapter->query($sql);
    }
}
