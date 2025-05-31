<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('industry_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('zip', 30)->nullable();
            $table->string('company_ceo')->nullable();
            $table->string('number_of_employees')->nullable();
            $table->string('established_in')->nullable();
            $table->string('fax')->nullable();
            $table->string('facebook_address')->nullable();
            $table->string('twitter')->nullable();
            $table->enum('ownership_type', ['Sole Proprietorship', 'Public', 'Private', 'Government', 'NGO'])->default('Sole Proprietorship');
            $table->string('phone_number', 20)->unique()->nullable();
            $table->longText('company_details')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('website_link')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_name')->nullable();
            $table->string('tag_line')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('industry_id')->references('id')->on('industries')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('employers');
    }
}
