<?php

namespace Base\Models;

class MigrationsModel extends BaseModel
{
    protected string $table = "migrations";
    protected bool $uuid = true;
    protected array $fillable = ["migration"];
}
