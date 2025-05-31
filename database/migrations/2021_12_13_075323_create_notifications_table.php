<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('sender_id');
                $table->unsignedBigInteger('receiver_id');
                $table->string('title');
                $table->string('url')->nullable();
                $table->text('body');
                $table->string('room_id')->nullable();
                $table->string('channel_id')->nullable();
                $table->boolean('is_read')->default(false);
                $table->boolean('is_deleted')->default(false);
                $table->foreign('sender_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('restrict');
                $table->foreign('receiver_id')->references('id')->on('users')
                    ->onUpdate('cascade')->onDelete('restrict');

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
        Schema::dropIfExists('notifications');
    }
}
