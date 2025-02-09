<?php

namespace Base\Core;

use Base\Interfaces\BlueprintInterface;

class Blueprint implements BlueprintInterface
{
    protected array $columns = [];
    protected ?string $lastColumn = null;

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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = null;
        return $this;
    }

    /**
     * Adds a primary key column.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function primary(string $name): self
    {
        $this->columns[] = "PRIMARY KEY (`{$name}`)";
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
        return $this;
    }

    /**
     * Adds an auto-incrementing primary key column.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function autoIncrement(string $name): self
    {
        $this->columns[] = "`{$name}` INT AUTO_INCREMENT PRIMARY KEY";
        $this->lastColumn = $name;
        return $this;
    }

    /**
     * Adds a unique constraint to a column.
     *
     * @param string $name The name of the column.
     * @return $this
     */
    public function unique(string $name): self
    {
        $this->columns[] = "UNIQUE KEY `unique_{$name}` (`{$name}`)";
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
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
        $this->lastColumn = $name;
        return $this;
    }

    /**
     * Makes the last defined column nullable.
     *
     * @return $this
     * @throws \RuntimeException If no column is defined to set as nullable.
     */
    public function nullable(): self
    {
        if (!$this->lastColumn) {
            throw new \RuntimeException(
                "No column defined to set as nullable."
            );
        }

        // Find the last column definition and append " NULL"
        $lastIndex = array_key_last($this->columns);
        $this->columns[$lastIndex] .= " NULL";

        return $this;
    }

    /**
     * Adds a raw SQL expression.
     *
     * @param string $expression The raw SQL expression.
     * @return string
     */
    public static function raw(string $expression): string
    {
        return $expression;
    }

    /**
     * Sets a default value for the last defined column.
     *
     * @param mixed $default The default value for the column.
     * @return $this
     * @throws \RuntimeException If no column is defined to set a default value.
     */
    public function default(mixed $default): self
    {
        if (!$this->lastColumn) {
            throw new \RuntimeException(
                "No column defined to set default value."
            );
        }

        // Find the last column definition and append " DEFAULT value"
        $lastIndex = array_key_last($this->columns);
        if (
            is_string($default) &&
            strpos($default, "CURRENT_TIMESTAMP") !== false
        ) {
            // Handle raw SQL expressions
            $this->columns[$lastIndex] .= " DEFAULT " . $default;
        } else {
            $this->columns[$lastIndex] .=
                " DEFAULT " . (is_string($default) ? "'{$default}'" : $default);
        }

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

    public function toSql(): string
    {
        $columns = implode(", ", $this->columns);
        if ($this->modify) {
            return "ALTER TABLE {$this->table} {$columns}";
        }
        return "CREATE TABLE {$this->table} ({$columns})";
    }

    public function dummyData(): array
    {
        $data = [];
        foreach ($this->columns as $column) {
            if (strpos($column, "VARCHAR") !== false) {
                $data[] = str_repeat("x", 10); // Example dummy string
            } elseif (strpos($column, "INT") !== false) {
                $data[] = rand(1, 100); // Example random number
            } elseif (strpos($column, "TIMESTAMP") !== false) {
                $data[] = date("Y-m-d H:i:s"); // Example timestamp
            }
        }
        return $data;
    }
}
