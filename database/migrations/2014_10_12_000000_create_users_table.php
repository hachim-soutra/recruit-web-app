<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_id')->nullable(); 
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('name');
            $table->string('user_key')->unique();
            $table->string('email')->unique();
            $table->string('mobile', 20)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->enum('user_type', ['employer', 'candidate', 'coach', 'admin', 'team'])->default('candidate');
            $table->string('stripe_id')->nullable();
            $table->string('verify_token')->nullable();
            $table->boolean('email_verified')->default(0);
            $table->boolean('mobile_verified')->default(0);
            $table->boolean('verified')->default(0);
            $table->boolean('is_complete')->default(0);
            $table->boolean('status')->default(1);
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->longtext('provider_token')->nullable();
            $table->string('token')->nullable();
            $table->longText('fpm_token')->nullable();
            $table->longText('access_token')->nullable();
            $table->string('rejecte_reason')->nullable();
            $table->string('timezone')->nullable();
            $table->string('oauth_id')->nullable();
            $table->string('oauth_type')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
