<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobNotInterestedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('job_not_interesteds')) {
            Schema::create('job_not_interesteds', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_id');
                $table->unsignedBigInteger('candidate_id');
                $table->text('not_interest_for')->nullable();
                $table->foreign('job_id')->references('id')->on('job_posts')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('candidate_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');

                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_not_interesteds');
    }
}
