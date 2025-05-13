<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            // Laravel Defaults
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable'); // ← This already creates the composite index
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            // Thesis-Specific Additions
            $table->foreignId('thesis_id')->nullable()->constrained()->cascadeOnDelete();
            $table->text('message')->nullable();
            
            // Keep only custom indexes
            $table->index(['thesis_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};