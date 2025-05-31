<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('coupons')) {
            Schema::create('coupons', function (Blueprint $table) {
                $table->id();
                $table->string('coupon_title')->nullable();
                $table->string('code')->unique();
                $table->enum('coupon_type', ['Price discount', 'Hike job limit'])->default('Price discount');
                $table->enum('coupon_for', ['Employer', 'Coach'])->default('Employer'); // Employer job posting and Coach subscription
                $table->integer('coupon_amount')->nullable();
                $table->integer('posted_job_ammount')->nullable();
                $table->integer('total_usage')->nullable();
                $table->integer('coupon_limit')->nullable();
                $table->date('coupon_start_date')->nullable();
                $table->date('coupon_expiry_date')->nullable();
                $table->text('description')->nullable();
                $table->boolean('status')->default(1);
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
                $table->foreign('created_by')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('coupons');
    }
}
