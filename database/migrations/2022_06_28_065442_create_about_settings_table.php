<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAboutSettingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('about_settings')) {

            Schema::create('about_settings', function (Blueprint $table) {
                $table->id();
                $table->string('heading');
                $table->longText('left_detail');
                $table->longText('right_detail');
                $table->longText('banner_description');
                $table->string('banner_file');
                $table->string('aboutus_video');
                $table->string('glorious_year')->nullable();
                $table->string('happy_client')->nullable();
                $table->string('talented_candidate')->nullable();
                $table->string('jobs_expo')->nullable();
                $table->string('counter_image');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('about_settings');
    }
}
