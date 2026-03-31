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
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category'); // Pre-Primary, Secondary, etc.
            $table->string('featured_image')->nullable();
            $table->string('year_of_establishment')->nullable();
            $table->string('growth_graph')->nullable(); // Image path
            $table->boolean('status')->default(true);

            // Contact Info
            $table->string('website')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->json('social_links')->nullable(); // fb, insta, linkedin, youtube

            // Major Sections
            $table->longText('institutional_journey')->nullable();
            $table->longText('academic_activities')->nullable();
            $table->longText('co_curricular_activities')->nullable();
            
            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
        });

        Schema::create('institution_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('student_photo')->nullable();
            $table->string('percentage')->nullable();
            $table->string('title')->nullable(); 
            $table->text('description')->nullable();
            $table->string('year')->nullable(); 
            $table->string('medium')->nullable(); 
            $table->string('overall_result')->nullable(); 
            $table->json('grades')->nullable(); 
            $table->timestamps();
        });

        Schema::create('institution_principals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->text('description')->nullable(); 
            $table->timestamps();
        });

        Schema::create('institution_pta_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('mobile')->nullable();
            $table->timestamps();
        });

        Schema::create('institution_awards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('institution_galleries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('title')->nullable();
            $table->timestamps();
        });

        Schema::create('institution_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->string('type'); 
            $table->string('title')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institution_sections');
        Schema::dropIfExists('institution_galleries');
        Schema::dropIfExists('institution_awards');
        Schema::dropIfExists('institution_pta_members');
        Schema::dropIfExists('institution_principals');
        Schema::dropIfExists('institution_results');
        Schema::dropIfExists('institutions');
    }
};
