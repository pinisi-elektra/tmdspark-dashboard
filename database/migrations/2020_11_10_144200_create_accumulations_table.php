<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccumulationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accumulations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id')->index();
            $table->unsignedMediumInteger('province_id')->index();
            $table->unsignedMediumInteger('city_id')->index();
            $table->unsignedMediumInteger('district_id')->index();
            $table->unsignedSmallInteger('merchant_id')->index();
            $table->unsignedSmallInteger('device_id')->index();
            $table->unsignedSmallInteger('region_tax_id')->index();
            $table->unsignedSmallInteger('parent_category_id')->index();
            $table->unsignedSmallInteger('child_category_id')->nullable()->index();
            $table->unsignedTinyInteger('solution_id')->index();
            $table->string('bill_no', 50)->index();
            $table->timestamp('trx_dt')->index();
            $table->float('total')->index();
            $table->float('tax_percentage');
            $table->float('tax');
            $table->string('province_name', 30);
            $table->string('city_name', 30);
            $table->string('district_name', 30);
            $table->string('merchant_name', 100);
            $table->string('device_name', 25);
            $table->string('parent_category_name', 25);
            $table->string('child_category_name', 25)->nullable();
            $table->string('solution_name', 10);
            $table->decimal('lat', 10, 8)->index();
            $table->decimal('lng', 11, 8)->index();
            $table->timestamps();

            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('parent_category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('child_category_id')->references('id')->on('categories')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('device_id')->references('id')->on('devices')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('region_tax_id')->references('id')->on('region_taxes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('solution_id')->references('id')->on('solutions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accumulations');
    }
}
