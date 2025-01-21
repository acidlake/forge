<?php
namespace Base\Database\Migrations;

use Base\Database\BaseMigration;
use Base\Database\SchemaBlueprint as Blueprint;

class CreateMigrationsTable extends BaseMigration
{
    public function up()
    {
        $this->schema->create("migrations", function (Blueprint $table) {
            $table->autoIncrementPrimary("id");
            $table->string("migration");
            $table->integer("batch");
            $table->timestamps();
        });
    }

    public function down()
    {
        $this->schema->dropIfExists("migrations");
    }
}
