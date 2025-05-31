<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactSettingsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('contact_settings')) {
            Schema::create('contact_settings', function (Blueprint $table) {
                $table->id();
                $table->string('heading');
                $table->longText('detail');
                $table->string('contactlocation');
                $table->string('contactus_image');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('contact_settings');
    }
}
