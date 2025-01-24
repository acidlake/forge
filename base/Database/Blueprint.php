<?php
namespace Base\Database;

class Blueprint
{
    public function uuid(string $name): self
    {
        $this->columns[$name] = "UUID";
        return $this;
    }

    public function enum(string $name, array $values): self
    {
        $this->columns[$name] = "ENUM(" . implode(",", $values) . ")";
        return $this;
    }

    public function json(string $name): self
    {
        $this->columns[$name] = "JSON";
        return $this;
    }

    public function nullable(): self
    {
        $this->currentColumn["nullable"] = true;
        return $this;
    }

    public function primary(): self
    {
        $this->currentColumn["primary"] = true;
        return $this;
    }
}
