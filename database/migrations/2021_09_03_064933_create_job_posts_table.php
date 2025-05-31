<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employer_id');
            $table->date('job_expiry_date')->nullable();
            $table->string('salary_from')->nullable();
            $table->string('salary_to')->nullable();
            $table->string('salary_currency')->nullable();
            $table->enum('salary_period', ['Weekly', 'Monthly', 'Yearly', 'Hourly'])->default('Yearly');
            $table->enum('hide_salary', ['yes', 'no'])->default('no');
            $table->string('job_title')->nullable();
            $table->string('job_location')->nullable();
            $table->text('qualifications')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('zip', 30)->nullable();
            $table->text('job_skills')->nullable();
            $table->string('functional_area')->nullable();
            $table->enum('preferred_job_type', ['Permanent', 'Temporary', 'Fixed-Term', 'Internship', 'Commission-Only', 'Freelance', 'Part time', 'Full time', 'Work from home', 'Remote', 'Temporarily remote'])->default('Permanent');
            $table->enum('additinal_pay', ['Bonus', 'Overtime', 'Commission'])->default('Commission');
            $table->string('experience')->nullable();
            $table->string('total_hire')->nullable();
            $table->longText('job_details')->nullable();
            $table->boolean('status')->default(1);
            $table->enum('payment_status', ['Paid', 'Unpaid', 'Failed'])->default('Unpaid');
            $table->enum('job_status', ['Save as Draft', 'Published'])->default('Save as Draft');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->foreign('employer_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_posts');
    }
}
