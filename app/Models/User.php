<?php
namespace App\Models;

use Base\ORM\BaseModel;

/**
 * User Model
 *
 * Represents the users table in the database.
 *
 * @property string $id
 * @property string $name
 */
class User extends BaseModel
{
    protected string $table = "users"; // Name of the database table
    protected string $key = "id"; // Primary key for the table (UUID in this case)

    protected string $storage = "document";
    protected string $keyStrategy = "id";

    protected array $fillable = ["name"];
    protected array $keyFields = ["id"];
}
