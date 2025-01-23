<?php

namespace Base\Core;

use Base\Interfaces\BlueprintInterface;

class Blueprint implements BlueprintInterface
{
    protected array $columns = [];

    /**
     * Adds a string (VARCHAR) column to the schema.
     *
     * @param string $name The name of the column.
     * @param int $length The length of the string. Default is 255.
     * @return $this
     */
    public function string(string $name, int $length = 255): self
    {
        $this->columns[] = "`{$name}` VARCHAR({$length})";
        return $this;
    }

    /**
     * Adds a UUID column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function uuid(string $name): self
    {
        $this->columns[] = "`{$name}` CHAR(36)";
        return $this;
    }

    /**
     * Adds soft delete support by adding a `deleted_at` column.
     *
     * @return $this
     */
    public function softDeletes(): self
    {
        $this->columns[] = "`deleted_at` TIMESTAMP NULL DEFAULT NULL";
        return $this;
    }

    /**
     * Adds `created_at` and `updated_at` timestamp columns to the schema.
     *
     * @return $this
     */
    public function timestamps(): self
    {
        $this->columns[] = "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] =
            "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    /**
     * Adds a primary key column.
     *
     * @param string $field The name of the column.
     * @return $this
     */
    public function primary(string $field): self
    {
        $this->columns[] = "PRIMARY KEY (`{$field}`)";
        return $this;
    }

    /**
     * Adds a UUID primary key column.
     *
     * @param string $name The name of the column. Default is "id".
     * @return $this
     */
    public function uuidPrimary(string $name = "id"): self
    {
        $this->columns[] = "`{$name}` CHAR(36) PRIMARY KEY";
        return $this;
    }

    /**
     * Adds an auto-incrementing primary key column.
     *
     * @param string $field The name of the column.
     * @return $this
     */
    public function autoIncrement(string $field): self
    {
        $this->columns[] = "`{$field}` INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    /**
     * Adds a unique constraint to a column.
     *
     * @param string $field The name of the column.
     * @return $this
     */
    public function unique(string $field): self
    {
        $this->columns[] = "UNIQUE KEY `unique_{$field}` (`{$field}`)";
        return $this;
    }

    /**
     * Adds a JSON column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function json(string $name): self
    {
        $this->columns[] = "`{$name}` JSON";
        return $this;
    }

    /**
     * Adds an ENUM column to the schema.
     *
     * @param string $name The name of the column.
     * @param array $allowed The allowed values for the column.
     * @return $this
     */
    public function enum(string $name, array $allowed): self
    {
        $allowedList = "'" . implode("','", $allowed) . "'";
        $this->columns[] = "`{$name}` ENUM({$allowedList})";
        return $this;
    }

    /**
     * Adds an integer column to the schema.
     *
     * @param string $name The name of the column.
     * @param bool $unsigned Whether the column should be unsigned. Default is false.
     * @return $this
     */
    public function integer(string $name, bool $unsigned = false): self
    {
        $unsignedSql = $unsigned ? " UNSIGNED" : "";
        $this->columns[] = "`{$name}` INT{$unsignedSql}";
        return $this;
    }

    /**
     * Adds a big integer column to the schema.
     *
     * @param string $name The name of the column.
     * @param bool $unsigned Whether the column should be unsigned. Default is false.
     * @return $this
     */
    public function bigInteger(string $name, bool $unsigned = false): self
    {
        $unsignedSql = $unsigned ? " UNSIGNED" : "";
        $this->columns[] = "`{$name}` BIGINT{$unsignedSql}";
        return $this;
    }

    /**
     * Adds a boolean column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function boolean(string $name): self
    {
        $this->columns[] = "`{$name}` TINYINT(1)";
        return $this;
    }

    /**
     * Adds a decimal column to the schema.
     *
     * @param string $name The name of the column.
     * @param int $precision Total number of digits in the number.
     * @param int $scale Number of digits to the right of the decimal point.
     * @return $this
     */
    public function decimal(string $name, int $precision, int $scale): self
    {
        $this->columns[] = "`{$name}` DECIMAL({$precision}, {$scale})";
        return $this;
    }

    /**
     * Adds a float column to the schema.
     *
     * @param string $name The name of the column.
     * @param int|null $precision Total number of digits in the number.
     * @param int|null $scale Number of digits to the right of the decimal point.
     * @return $this
     */
    public function float(
        string $name,
        int $precision = null,
        int $scale = null
    ): self {
        $precisionSql = $precision && $scale ? "({$precision}, {$scale})" : "";
        $this->columns[] = "`{$name}` FLOAT{$precisionSql}";
        return $this;
    }

    /**
     * Adds a date column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function date(string $name): self
    {
        $this->columns[] = "`{$name}` DATE";
        return $this;
    }

    /**
     * Adds a datetime column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function dateTime(string $name): self
    {
        $this->columns[] = "`{$name}` DATETIME";
        return $this;
    }

    /**
     * Adds a text column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function text(string $name): self
    {
        $this->columns[] = "`{$name}` TEXT";
        return $this;
    }

    /**
     * Adds a long text column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function longText(string $name): self
    {
        $this->columns[] = "`{$name}` LONGTEXT";
        return $this;
    }

    /**
     * Adds a time column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function time(string $name): self
    {
        $this->columns[] = "`{$name}` TIME";
        return $this;
    }

    /**
     * Adds a timestamp column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function timestamp(string $name): self
    {
        $this->columns[] = "`{$name}` TIMESTAMP";
        return $this;
    }

    /**
     * Adds a binary column to the schema.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function binary(string $name): self
    {
        $this->columns[] = "`{$name}` BLOB";
        return $this;
    }

    /**
     * Makes a column nullable.
     *
     * @param string $name The name of the column.
     * @param string $type The type of the column.
     * @return $this
     */
    public function nullable(string $name, string $type): self
    {
        $this->columns[] = "`{$name}` {$type} NULL";
        return $this;
    }

    /**
     * Sets a default value for a column.
     *
     * @param string $name The name of the column.
     * @param string $type The type of the column.
     * @param mixed $default The default value for the column.
     * @return $this
     */
    public function default(string $name, string $type, mixed $default): self
    {
        $defaultValue = is_string($default) ? "'{$default}'" : $default;
        $this->columns[] = "`{$name}` {$type} DEFAULT {$defaultValue}";
        return $this;
    }

    /**
     * Compiles the columns into a valid SQL string.
     *
     * @return string
     */
    public function build(): string
    {
        return implode(", ", $this->columns);
    }
}
