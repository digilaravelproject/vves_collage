<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('cache')) {
            return;
        }

        DB::statement('ALTER TABLE `cache` MODIFY `value` LONGTEXT NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('cache')) {
            return;
        }

        DB::statement('ALTER TABLE `cache` MODIFY `value` MEDIUMTEXT NOT NULL');
    }
};
