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
        Schema::table('theses', function (Blueprint $table) {
            $table->string('file_path')->after('coauthors');
            $table->string('original_filename')->nullable()->after('file_path'); // optional
        });
    }

    public function down()
    {
        Schema::table('theses', function (Blueprint $table) {
            $table->dropColumn(['file_path', 'original_filename']);
        });
    }

};
