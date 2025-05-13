<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('thesis_requirements', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn([
                'forms',
                'hardbound',
                'final_script',
                'ieee_journal',
                'defense_ppt',
                'user_manual',
                'source_code',
                'application',
                'mobile_apk',
                'tarpaulin_design',
                'demo_video',
                'promo_video',
            ]);

            // Add new columns
            $table->string('title',500);
            $table->string('file_path',500);
            $table->string('original_filename',500);
        });
    }

    public function down()
    {
        Schema::table('thesis_requirements', function (Blueprint $table) {
            // Revert back: drop new columns
            $table->dropColumn(['title', 'file_path', 'original_filename']);

            // Re-add old columns
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
        });
    }
};
