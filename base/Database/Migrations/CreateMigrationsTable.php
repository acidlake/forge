<?php
namespace Base\Database\Migrations;

use Base\Database\BaseMigration;
use Base\Database\BaseSchemaBuilder;
use Base\Database\SchemaBlueprint as Blueprint;

class CreateMigrationsTable extends BaseMigration
{
    public function up()
    {
        $schema = new BaseSchemaBuilder();

        $schema->create("migrations", function (Blueprint $table) {
            $table->autoIncrementPrimary("id");
            $table->string("migration");
            $table->integer("batch");
            $table->timestamps();
        });
    }

    public function down()
    {
        $schema = new BaseSchemaBuilder();
        $schema->drop("users");
    }
}
