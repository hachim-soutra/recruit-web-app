<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLookingStaffTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('looking_staff')) {

            Schema::create('looking_staff', function (Blueprint $table) {
                $table->id();
                $table->string('page_type');
                $table->longText('content');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('looking_staff');
    }
}
