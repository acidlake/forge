<?php

namespace Base\Interfaces;

interface BlueprintInterface
{
    public function string(string $name, int $length = 255): self;
    public function uuid(string $name): self;
    public function timestamps(): self;
    public function primary(string $field): self;
    public function unique(string $field): self;
    public function json(string $name): self;
    public function enum(string $name, array $allowed): self;
    public function build(): string;
}
