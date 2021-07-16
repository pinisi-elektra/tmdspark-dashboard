<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true);
            $table->unsignedSmallInteger('device_id')->index();
            $table->string('bill_no', 50)->index();
            $table->timestamp('trx_dt')->index();
            $table->float('total')->index();
            $table->timestamps();
            $table->unique(['device_id', 'bill_no', 'trx_dt', 'total']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
