<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Add user_id column if it doesn't exist already
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }

            // Add thesis_id column if it doesn't exist already
            if (!Schema::hasColumn('notifications', 'thesis_id')) {
                $table->unsignedBigInteger('thesis_id')->nullable();
                $table->foreign('thesis_id')->references('id')->on('theses')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
{
    Schema::table('notifications', function (Blueprint $table) {
        // Drop only existing foreign key constraint
        $table->dropForeign(['thesis_id']); // Only thesis_id has FK
        $table->dropColumn(['user_id', 'thesis_id']);
    });
}

};
