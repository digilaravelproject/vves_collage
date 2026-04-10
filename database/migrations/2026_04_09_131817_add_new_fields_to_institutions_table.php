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
        Schema::table('institutions', function (Blueprint $table) {
            $table->string('iso_certification')->nullable()->after('city');
            $table->string('breadcrumb_image')->nullable()->after('featured_image');
            $table->string('tagline')->nullable()->after('name');
            $table->string('academic_diary_pdf')->nullable()->after('website');
            $table->json('about_sections')->nullable()->after('institutional_journey');
            $table->json('activities_facilities_blocks')->nullable()->after('academic_activities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->dropColumn([
                'iso_certification',
                'breadcrumb_image',
                'tagline',
                'academic_diary_pdf',
                'about_sections',
                'activities_facilities_blocks'
            ]);
        });
    }
};
