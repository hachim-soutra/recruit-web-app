<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubscriptionFieldsToPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('best_value')
                ->default(false)
                ->after('slug');

            $table->string('badge_text')
                ->nullable()
                ->after('best_value');

            $table->json('features')
                ->nullable()
                ->after('badge_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumnIfExists('best_value');
            $table->dropColumnIfExists('badge_text');
            $table->dropColumnIfExists('features');
        });
    }
}
