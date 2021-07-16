<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->string('name', 100)->unique();
            $table->string('address', 180)->unique();
            $table->unsignedInteger('user_id')->nullable()->index();
            $table->unsignedMediumInteger('province_id')->nullable()->index();
            $table->unsignedMediumInteger('city_id')->nullable()->index();
            $table->unsignedMediumInteger('district_id')->nullable()->index();
            $table->unsignedTinyInteger('solution_id')->index();
            $table->decimal('lat', 10, 8)->unique();
            $table->decimal('lng', 11, 8)->unique();
            $table->string('npwpwd', 100)->unique();
            $table->string('status', 10);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('cms_users');
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->foreign('solution_id')->references('id')->on('solutions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('merchants');
    }
}
