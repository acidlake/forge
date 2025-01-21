<?php
namespace Base\Database;

/**
 * SchemaBlueprint defines the structure of database tables and generates SQL for schema operations.
 *
 * @framework Forge
 * @license MIT
 * @author Jeremias
 * @github acidlake
 */
class SchemaBlueprint
{
    private string $table;
    private bool $modify = false;
    private array $columns = [];
    private array $constraints = [];
    private bool $nullable = false;

    public function __construct(string $table, bool $modify = false)
    {
        $this->table = $table;
        $this->modify = $modify;
    }

    public function id(string $name = "id"): self
    {
        $this->columns[] = "{$name} INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    public function uuid(string $name = "id"): self
    {
        $this->columns[] = "{$name} CHAR(36) NOT NULL UNIQUE";
        return $this;
    }

    /**
     * Add an auto-incrementing integer primary key.
     */
    public function autoIncrementPrimary(string $name = "id"): self
    {
        $this->columns[] = "{$name} INT AUTO_INCREMENT PRIMARY KEY";
        return $this;
    }

    /**
     * Add a JSON column.
     */
    public function json(string $name): self
    {
        $this->columns[] = "{$name} JSON NOT NULL";
        return $this;
    }

    /**
     * Add an ENUM column.
     */
    public function enum(string $name, array $values): self
    {
        $escapedValues = array_map(fn($value) => "'{$value}'", $values);
        $enumValues = implode(", ", $escapedValues);
        $this->columns[] = "{$name} ENUM({$enumValues}) NOT NULL";
        return $this;
    }

    /**
     * Add a decimal column.
     */
    public function decimal(
        string $name,
        int $precision = 10,
        int $scale = 2
    ): self {
        $this->columns[] = "{$name} DECIMAL({$precision}, {$scale}) NOT NULL";
        return $this;
    }

    /**
     * Add a float column.
     */
    public function float(string $name): self
    {
        $this->columns[] = "{$name} FLOAT NOT NULL";
        return $this;
    }

    /**
     * Add a string column.
     */
    public function string(string $name, int $length = 255): self
    {
        $this->addColumn("{$name} VARCHAR({$length})");
        return $this;
    }

    public function integer(string $name): self
    {
        $this->columns[] = "{$name} INT NOT NULL";
        return $this;
    }

    public function timestamps(): self
    {
        $this->columns[] = "created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
        $this->columns[] =
            "updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
        return $this;
    }

    public function toSql(): string
    {
        $columns = implode(", ", $this->columns);
        if ($this->modify) {
            return "ALTER TABLE {$this->table} {$columns}";
        }
        return "CREATE TABLE {$this->table} ({$columns})";
    }

    /**
     * Add a UUID primary key.
     */
    public function uuidPrimary(string $name = "id"): self
    {
        $this->columns[] = "{$name} CHAR(36) PRIMARY KEY NOT NULL UNIQUE";
        return $this;
    }

    /**
     * Add a nullable property to the last column.
     */
    public function nullable(): self
    {
        $this->nullable = true;
        $this->columns[count($this->columns) - 1] .= " NULL";
        return $this;
    }

    /**
     * Add a column with tracking nullable status.
     */
    private function addColumn(string $definition): void
    {
        if ($this->nullable) {
            $definition .= " NULL";
            $this->nullable = false; // Reset nullable flag
        } else {
            $definition .= " NOT NULL";
        }
        $this->columns[] = $definition;
    }
}
