<?php
namespace Base\Database\Adapters;

use Base\Database\BaseDatabaseAdapter;

class PostgreSQLAdapter extends BaseDatabaseAdapter
{
    protected function getDsn(array $config): string
    {
        return "pgsql:host={$config["host"]};dbname={$config["database"]};port={$config["port"]}";
    }
}
