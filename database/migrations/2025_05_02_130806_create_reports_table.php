<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('thesis_id')->nullable();
            $table->string('thesis_title');
            $table->json('forms')->nullable();
            $table->string('hardbound')->nullable();
            $table->string('final_script')->nullable();
            $table->string('ieee_journal')->nullable();
            $table->string('defense_ppt')->nullable();
            $table->string('user_manual')->nullable();
            $table->string('source_code')->nullable();
            $table->string('application')->nullable();
            $table->string('mobile_apk')->nullable();
            $table->string('tarpaulin_design')->nullable();
            $table->string('demo_video')->nullable();
            $table->string('promo_video')->nullable();
            $table->timestamps();

            // Add foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('thesis_id')->references('id')->on('theses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
