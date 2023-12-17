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
        Schema::table('post_comments', function (Blueprint $table) {
            $table->foreign('post_id')->references('id')->on('posts')->change()->onUpdate('cascade')->onDelete('cascade')->nullable();            
            $table->foreign('user_id')->references('id')->on('users')->change()->onUpdate('cascade')->onDelete('cascade')->nullable();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_comments', function (Blueprint $table) {
            $table->dropForeign('post_id');
            $table->dropForeign('user_id');
        });
    }
};
