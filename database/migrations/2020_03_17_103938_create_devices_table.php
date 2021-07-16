<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->string('name', 25)->unique();
            $table->string('npwpd', 25)->unique();
            $table->unsignedSmallInteger('merchant_id')->index();
            $table->unsignedSmallInteger('category_id')->index();
            $table->string('status', 10);
            $table->timestamp('last_trx');
            $table->timestamps();
            
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devices');
    }
}
