<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageContentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('home_page_contents')) {
            Schema::create('home_page_contents', function (Blueprint $table) {
                $table->id();
                $table->string('banner_heading')->nullable();
                $table->string('banner_file')->nullable();
                $table->string('recruitment_content_heading')->nullable();
                $table->longText('recruitment_content_description')->nullable();
                $table->string('glorious_years')->nullable();
                $table->string('job_filled')->nullable();
                $table->string('job_vacancy')->nullable();
                $table->string('career_coach_text')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('home_page_contents');
    }
}
