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
        Schema::table('institution_awards', function (Blueprint $table) {
            $table->string('award_name')->nullable()->after('title');
            $table->string('recipient_name')->nullable()->after('award_name');
            $table->string('award_date')->nullable()->after('recipient_name');
        });

        Schema::table('institution_results', function (Blueprint $table) {
            $table->string('student_name')->nullable()->after('student_photo');
            $table->string('subject')->nullable()->after('student_name');
            $table->string('passing_year')->nullable()->after('subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institution_awards', function (Blueprint $table) {
            $table->dropColumn(['award_name', 'recipient_name', 'award_date']);
        });

        Schema::table('institution_results', function (Blueprint $table) {
            $table->dropColumn(['student_name', 'subject', 'passing_year']);
        });
    }
};
