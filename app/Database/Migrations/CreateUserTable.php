<?php

namespace App\Database\Migrations;

use Base\Core\MigrationBuilder;
use Base\Core\Blueprint;
use Base\Interfaces\MigrationInterface;

class CreateUsersTable implements MigrationInterface
{
    public function up(): void
    {
        MigrationBuilder::create("users", function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string("name", 255);
            $table->string("email", 255)->unique();
            $table->enum("role", ["admin", "user", "guest"]);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        MigrationBuilder::dropIfExists("users");
    }
}
