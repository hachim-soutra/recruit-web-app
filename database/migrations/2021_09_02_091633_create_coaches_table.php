<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoachesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coaches', function (Blueprint $table) {
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
            $table->string('highest_qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('university_or_institute')->nullable();
            $table->string('year_of_graduation')->nullable();
            $table->string('education_type')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->enum('coach_type', ['Experience', 'Fresher'])->default('Experience');
            $table->enum('preferred_job_type', ['Part time', 'Full time', 'Part time & Full time', 'Work from home', 'Remote', 'Temporarily remote', 'Freelance'])->default('Full time');
            $table->enum('gender', ['Male', 'Female', 'Prefer to not say'])->default('Male');
            $table->enum('marital_status', ['Married', 'Single', 'Divorced', 'Widowed'])->default('Single');
            $table->string('resume')->nullable();
            $table->string('resume_title')->nullable();
            $table->text('linkedin_link')->nullable();
            $table->text('contact_link')->nullable();
            $table->longText('bio')->nullable();
            $table->longText('teaching_history')->nullable();
            $table->longText('active_teaching_history')->nullable();
            $table->longText('about_us')->nullable();
            $table->longText('faq')->nullable();
            $table->longText('how_we_help')->nullable();
            $table->longText('coach_skill')->nullable();
            $table->string('coach_banner')->nullable();
            $table->longText('skill_details')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')
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
        Schema::dropIfExists('coaches');
    }
}
