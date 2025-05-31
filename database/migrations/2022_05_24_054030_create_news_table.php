<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('news')) {
            Schema::create('news', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('news_category_id')->nullable();
                $table->foreign('news_category_id')->references('id')->on('news_categories')->onUpdate('cascade')->onDelete('cascade');
                $table->string('title');
                $table->string('slug');
                $table->string('image');
                $table->longText('newsdetail');
                $table->enum('status', ['A', 'I', 'D'])->default('A');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->foreign('created_by')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->foreign('updated_by')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->timestamp('expire_date');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('news');
    }
}
