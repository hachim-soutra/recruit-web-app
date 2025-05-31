<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransuctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('transuctions')) {
            Schema::create('transuctions', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('job_id');
                $table->string('transaction_id')->unique();
                $table->string('cart_key')->nullable();
                $table->double('amount', 8, 2)->default(0);
                $table->string('currency')->nullable();
                $table->string('card_brand')->nullable();
                $table->string('card_last_four', 4)->nullable();
                $table->double('tax_percentage', 8, 2)->default(0);
                $table->double('total_amount', 8, 2)->default(0);
                $table->enum('status', ['pending', 'cancelled', 'complete'])->default('pending');
                $table->enum('pay_by', ['Coach', 'Employer'])->nullable();
                $table->date('expiry_date')->nullable();

                $table->timestamps();
                $table->foreign('user_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('cascade');
                $table->foreign('job_id')->references('id')->on('job_posts')
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
        Schema::dropIfExists('transuctions');
    }
}
