<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('post_by')->references('id')->on('users')->change()->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreign('page_id')->references('id')->on('pages')->change()->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreign('category_id')->references('id')->on('post_categories')->change()->onUpdate('cascade')->onDelete('cascade')->nullable();
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign('post_by');
            $table->dropForeign('page_id');
            $table->dropForeign('category_id');
        });
    }
};
