<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsCategoriesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('news_categories')) {
            Schema::create('news_categories', function (Blueprint $table) {
                $table->id();
                $table->string('category_name');
                $table->string('category_slug');
                $table->enum('navbar_show', ['0', '1'])->default('0');
                $table->enum('status', ['A', 'I', 'D'])->default('A');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('news_categories');
    }
}
