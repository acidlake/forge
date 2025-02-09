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
    protected string $table = "users";
    protected bool $uuid = false;
    protected string $keyStrategy = "uuidv2";
    protected array $fillable = ["id", "name", "lastName"];

    public int $id;
    public string $name;
    public string $lastName;
}
