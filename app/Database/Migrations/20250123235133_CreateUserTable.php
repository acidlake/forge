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
        });
    }

    public function down(): void
    {
        MigrationBuilder::dropIfExists("users");
    }
}
