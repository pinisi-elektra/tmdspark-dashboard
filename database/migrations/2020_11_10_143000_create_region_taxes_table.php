<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionTaxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('region_taxes', function (Blueprint $table) {
            $table->unsignedSmallInteger('id', true);
            $table->unsignedMediumInteger('province_id')->index();
            $table->unsignedMediumInteger('city_id')->index();
            $table->unsignedSmallInteger('category_id')->index();
            $table->float('tax_value')->default(0);
            $table->float('target')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('province_id')->references('id')->on('provinces');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('region_taxes');
    }
}
