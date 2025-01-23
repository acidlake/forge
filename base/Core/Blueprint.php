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

    public function build(): string
    {
        return implode(", ", $this->columns);
    }
}
