<?php
namespace App\Models;

use Base\ORM\BaseModel;

/**
 * User Model
 *
 * Represents the users table in the database.
 *
 * @property string $id
 * @property string $uuid
 * @property string $name
 * @property string $email
 */
class User extends BaseModel
{
    protected string $table = "users"; // Name of the database table
    protected string $key = "id"; // Primary key for the table (UUID in this case)

    protected array $fillable = ["id", "name"];
}
