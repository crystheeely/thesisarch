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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to users table
            $table->string('author_name'); // Store author's full name
            $table->string('title');
            $table->text('abstract');
            $table->text('keywords')->nullable();
            $table->string('academic_year');
            $table->string('semester');
            $table->string('month');
            $table->json('coauthors')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });

        Schema::create('thesis_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained('theses')->onDelete('cascade'); // Link to theses

            // Optional files as text (URL, JSON, or path)
            $table->text('forms')->nullable();
            $table->text('hardbound')->nullable();
            $table->text('final_script')->nullable();
            $table->text('ieee_journal')->nullable();
            $table->text('defense_ppt')->nullable();
            $table->text('user_manual')->nullable();
            $table->text('source_code')->nullable();
            $table->text('application')->nullable();
            $table->text('mobile_apk')->nullable();
            $table->text('tarpaulin_design')->nullable();
            $table->text('demo_video')->nullable();
            $table->text('promo_video')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thesis_requirements');
        Schema::dropIfExists('theses');
    }
};
