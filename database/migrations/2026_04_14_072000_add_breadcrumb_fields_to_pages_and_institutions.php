<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('breadcrumb_image')->nullable()->after('pdf');
            $table->string('breadcrumb_note')->nullable()->after('breadcrumb_image');
        });

        Schema::table('institutions', function (Blueprint $table) {
            $table->string('breadcrumb_note')->nullable()->after('breadcrumb_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn(['breadcrumb_image', 'breadcrumb_note']);
        });

        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn(['breadcrumb_note']);
        });
    }
};
