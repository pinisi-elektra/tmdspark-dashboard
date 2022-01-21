<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->string('chat_id', 255);
            $table->string('message_id', 255)->nullable();
            $table->string('text', 255)->nullable();
            $table->string('photo', 255)->nullable();
            $table->string('document', 255)->nullable();
            $table->string('caption', 255)->nullable();
            $table->string('audio', 255)->nullable();
            $table->string('video', 255)->nullable();
            $table->decimal('latitude', 10, 8)->unique();
            $table->decimal('longitude', 11, 8)->unique();
            $table->unsignedInteger('user_id')->index();
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
        Schema::dropIfExists('messages');
    }
}
