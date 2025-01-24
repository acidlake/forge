<?php

namespace App\Database\Migrations;

use Base\Core\MigrationBuilder;
use Base\Core\Blueprint;

class CreateCreateRolesTable
{
    public function up(): void
    {
        MigrationBuilder::create("roles_table", function (Blueprint $table) {
            $table->autoIncrement("id");
            $table->string("role", 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        MigrationBuilder::dropIfExists("roles_table");
    }
}
