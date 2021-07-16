<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCmsUsersTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_users', function (Blueprint $table) {
            $table->unsignedInteger('id', true)->change();
            $table->string('phone', 15)->nullable()->after('password');
            $table->unsignedInteger('id_cms_users')->nullable()->after('phone');
            $table->unsignedMediumInteger('province_id')->nullable()->after('id_cms_users')->index();
            $table->unsignedMediumInteger('city_id')->nullable()->after('province_id')->index();
            $table->unsignedMediumInteger('district_id')->nullable()->after('province_id')->index();
            $table->foreign('province_id')->references('id')->on('provinces');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('district_id')->references('id')->on('districts');
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
