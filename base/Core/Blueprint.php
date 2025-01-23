<?php

namespace Base\Core;

use Base\Interfaces\BlueprintInterface;

class Blueprint implements BlueprintInterface
{
    protected array $columns = [];

    public function string(string $name, int $length = 255): self
    {
        $this->columns[] = "`{$name}` VARCHAR({$length})";
        return $this;
    }

    public function uuid(string $name): self
    {
        $this->columns[] = "`{$name}` CHAR(36)";
        return $this;
    }

    public function softDeletes(): self
    {
        $this->columns[] = "`deleted_at` TIMESTAMP NULL DEFAULT NULL";
        return $this;
    }

    public function timestamps(): self
    {
        $this->columns[] = "`created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] =
            "`updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function primary(string $field): self
    {
        $this->columns[] = "PRIMARY KEY (`{$field}`)";
        return $this;
    }

    public function uuidPrimary(string $name = "id"): self
    {
        $this->columns[] = "`{$name}` CHAR(36) PRIMARY KEY";
        return $this;
    }

    public function autoIncrement(string $field): self
    {
        $this->columns[] = "`{$field}` INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function unique(string $field): self
    {
        $this->columns[] = "UNIQUE KEY `unique_{$field}` (`{$field}`)";
        return $this;
    }

    public function json(string $name): self
    {
        $this->columns[] = "`{$name}` JSON";
        return $this;
    }

    public function enum(string $name, array $allowed): self
    {
        $allowedList = "'" . implode("','", $allowed) . "'";
        $this->columns[] = "`{$name}` ENUM({$allowedList})";
        return $this;
    }

    public function integer(string $name, bool $unsigned = false): self
    {
        $unsignedSql = $unsigned ? " UNSIGNED" : "";
        $this->columns[] = "`{$name}` INT{$unsignedSql}";
        return $this;
    }

    public function bigInteger(string $name, bool $unsigned = false): self
    {
        $unsignedSql = $unsigned ? " UNSIGNED" : "";
        $this->columns[] = "`{$name}` BIGINT{$unsignedSql}";
        return $this;
    }

    public function boolean(string $name): self
    {
        $this->columns[] = "`{$name}` TINYINT(1)";
        return $this;
    }

    public function decimal(string $name, int $precision, int $scale): self
    {
        $this->columns[] = "`{$name}` DECIMAL({$precision}, {$scale})";
        return $this;
    }

    public function float(
        string $name,
        int $precision = null,
        int $scale = null
    ): self {
        $precisionSql = $precision && $scale ? "({$precision}, {$scale})" : "";
        $this->columns[] = "`{$name}` FLOAT{$precisionSql}";
        return $this;
    }

    public function date(string $name): self
    {
        $this->columns[] = "`{$name}` DATE";
        return $this;
    }

    public function dateTime(string $name): self
    {
        $this->columns[] = "`{$name}` DATETIME";
        return $this;
    }

    public function text(string $name): self
    {
        $this->columns[] = "`{$name}` TEXT";
        return $this;
    }

    public function longText(string $name): self
    {
        $this->columns[] = "`{$name}` LONGTEXT";
        return $this;
    }

    public function time(string $name): self
    {
        $this->columns[] = "`{$name}` TIME";
        return $this;
    }

    public function timestamp(string $name): self
    {
        $this->columns[] = "`{$name}` TIMESTAMP";
        return $this;
    }

    public function binary(string $name): self
    {
        $this->columns[] = "`{$name}` BLOB";
        return $this;
    }

    public function nullable(string $name, string $type): self
    {
        $this->columns[] = "`{$name}` {$type} NULL";
        return $this;
    }

    public function default(string $name, string $type, mixed $default): self
    {
        $defaultValue = is_string($default) ? "'{$default}'" : $default;
        $this->columns[] = "`{$name}` {$type} DEFAULT {$defaultValue}";
        return $this;
    }

    public function build(): string
    {
        return implode(", ", $this->columns);
    }
}
