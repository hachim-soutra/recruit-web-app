<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('address')->nullable();
            $table->string('city', 50)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('country', 50)->nullable();
            $table->string('zip', 30)->nullable();
            $table->string('total_experience_year', 30)->nullable();
            $table->string('total_experience_month', 30)->nullable();
            $table->string('alternate_mobile_number', 20)->unique()->nullable();
            $table->unsignedBigInteger('highest_qualification')->nullable();
            $table->unsignedBigInteger('functional_id')->nullable();
            $table->string('specialization')->nullable();
            $table->string('university_or_institute')->nullable();
            $table->string('year_of_graduation')->nullable();
            $table->string('education_type')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->enum('candidate_type', ['Graduate', 'Entry level', 'Manager', 'Director', 'C-Level'])->default('Entry level');
            $table->enum('preferred_job_type', ['Permanent', 'Temporary', 'Fixed-Term', 'Internship', 'Commission-Only', 'Freelance', 'Part time', 'Full time', 'Work from home', 'Remote', 'Temporarily remote'])->default('Full time');
            $table->enum('gender', ['Male', 'Female', 'Prefer to not say'])->default('Male');
            $table->enum('marital_status', ['Married', 'Single', 'Divorced', 'Widowed'])->nullable();
            $table->string('resume')->nullable();
            $table->string('cover_letter')->nullable();
            $table->string('nationality')->nullable();
            $table->string('current_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->string('salary_currency')->nullable();
            $table->string('resume_title')->nullable();
            $table->string('notice_period')->nullable();
            $table->string('visa_status')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('portfolio_link')->nullable();
            $table->longText('bio')->nullable();
            $table->text('languages')->nullable();
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('highest_qualification')->references('id')->on('qualifications')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('functional_id')->references('id')->on('industries')
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
        Schema::dropIfExists('candidates');
    }
}
