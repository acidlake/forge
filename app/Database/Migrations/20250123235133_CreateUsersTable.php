<?php

namespace App\Database\Migrations;

use Base\Core\MigrationBuilder;
use Base\Core\Blueprint;

class CreateUsersTable
{
    public function up(): void
    {
        MigrationBuilder::create("users", function (Blueprint $table) {
            $table->autoIncrement("id");
            $table->string("name", 255);
            $table->string("lastName", 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        MigrationBuilder::dropIfExists("users");
    }
}
