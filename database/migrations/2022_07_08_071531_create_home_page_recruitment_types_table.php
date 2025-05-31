<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomePageRecruitmentTypesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('home_page_recruitment_types')) {
            Schema::create('home_page_recruitment_types', function (Blueprint $table) {
                $table->id();
                $table->string('recruitment_type');
                $table->string('recruitment_type_file');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('home_page_recruitment_types');
    }
}
