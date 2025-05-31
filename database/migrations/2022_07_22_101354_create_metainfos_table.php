<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetainfosTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('metainfos')) {
            Schema::create('metainfos', function (Blueprint $table) {
                $table->id();
                $table->string('page_name');
                $table->string('meta_tags');
                $table->longText('meta_title');
                $table->longText('meta_description');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('metainfos');
    }
}
