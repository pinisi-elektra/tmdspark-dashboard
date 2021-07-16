<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMerchantsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->date('implementation_dt')->after('user_id');
            $table->date('integration_dt')->nullable()->after('implementation_dt');
            $table->string('bap', 10)->default('Ada')->before('status');
            $table->string('vpn_ip', 25)->nullable()->after('status');
            $table->string('vpn_user', 50)->nullable()->after('vpn_ip');
            $table->string('vpn_password', 255)->nullable()->after('vpn_user');

            $table->string('device_modem', 15)->nullable()->after('vpn_password');
            $table->string('device_version', 10)->nullable()->after('device_modem');
            $table->string('device_serial', 25)->nullable()->after('device_version');
            $table->string('pic_name', 100)->nullable()->after('device_serial');
            $table->string('pic_phone', 15)->nullable()->after('pic_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
