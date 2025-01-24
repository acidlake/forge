<?php
namespace Base\Database\Adapters;

use Base\Database\BaseDatabaseAdapter;

class SQLiteAdapter extends BaseDatabaseAdapter
{
    protected function getDsn(array $config): string
    {
        return "sqlite:{$config["database"]}";
    }
}
