<?php
namespace Base\Database\Migrations;

use Base\Core\MigrationBuilder;
use Base\Core\Blueprint;

class CreateJobsTable
{
    public static function up(): void
    {
        MigrationBuilder::create("jobs", function (Blueprint $table) {
            $table->bigInteger("id", true)->primary();
            $table->string("queue");
            $table->json("payload");
            $table->integer("attempts")->default(0);
            $table->integer("max_attempts")->default(3);
            $table->timestamp("reserved_at")->nullable();
            $table
                ->timestamp("available_at")
                ->default(DB::raw("CURRENT_TIMESTAMP"));
            $table->timestamps();
        });
    }

    public static function down(): void
    {
        MigrationBuilder::dropIfExists("jobs");
    }
}
