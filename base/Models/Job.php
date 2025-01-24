<?php
namespace Base\Models;

use Base\ORM\BaseModel;

class Job extends BaseModel
{
    protected string $table = "jobs";
    protected array $fillable = [
        "queue",
        "payload",
        "attempts",
        "max_attempts",
        "reserved_at",
        "available_at",
    ];
}
