<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanPackageSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_package_slot', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_package_id');
            $table->unsignedBigInteger('slot_id');
            $table->foreign('plan_package_id')->references('id')->on('plan_packages')->onDelete('cascade');
            $table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_package_slot');
    }
}
