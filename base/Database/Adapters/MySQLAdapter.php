<?php
namespace Base\Database\Adapters;

use Base\Database\BaseDatabaseAdapter;

class MySQLAdaptaer extends BaseDatabaseAdapter
{
    protected function getDsn(array $config): string
    {
        return "mysql:host={$config["host"]};dbname={$config["database"]};port={$config["port"]}";
    }
}
