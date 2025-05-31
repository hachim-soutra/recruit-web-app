<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->text('facebook_link')->nullable();
                $table->text('google_link')->nullable();
                $table->string('site_email')->nullable();
                $table->string('contact_email')->nullable();
                $table->string('currency')->default('eur');
                $table->enum('new_join_one_month_free', ['Employer', 'Both', 'Coach', 'None of These'])->default('Both');
                $table->text('twitter_link')->nullable();
                $table->text('instagram_link')->nullable();
                $table->text('pinterest_link')->nullable();
                $table->string('mobile_no')->nullable();
                $table->string('alt_mobaile_no')->nullable();
                $table->text('addres_one')->nullable();
                $table->text('address_two')->nullable();
                $table->longText('about_us')->nullable();
                $table->longText('help')->nullable();
                $table->longText('term_of_use')->nullable();
                $table->longText('privacy_policy')->nullable();
                $table->string('admin_pic')->nullable();
                $table->string('logo')->nullable();
                $table->string('banner_image')->nullable();
                $table->string('payment_gateway')->nullable();
                $table->string('secret_key')->nullable();
                $table->string('published_key')->nullable();
                $table->boolean('status')->default(true);
                $table->boolean('copyright_content')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
