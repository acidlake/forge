<?php
namespace Base\Database\Migrations;

use Base\Core\Blueprint;
use Base\Database\BaseMigration;

class CreateMigrationsTable extends BaseMigration
{
    public function up(): void
    {
        $this->schema->create("migrations", function (Blueprint $table) {
            $table->autoIncrement("id");
            $table->string("migration");
            $table->integer("batch");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->schema->drop("users");
    }
}
