<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldSwitchInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_switch_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('loadBreak')->nullable(true);
            $table->integer('poleCount')->nullable(true);
            $table->boolean('remote')->nullable(true);
            $table->unsignedBigInteger('oil_volume_per_tank_id')->nullable(true);
            $table->unsignedBigInteger('withstand_current_id')->nullable(true);
            $table->unsignedBigInteger('making_capacity_id')->nullable(true);
            $table->unsignedBigInteger('enpro_earth_switch_current_duration_id')->nullable(true);
            $table->unsignedBigInteger('enpro_secondary_voltage_kind_id')->nullable(true);
            $table->unsignedBigInteger('enpro_secondary_voltage_id')->nullable(true);
            $table->unsignedBigInteger('switch_info_id')->nullable(true);

            $table->foreign('oil_volume_per_tank_id')->on('mass')->references('id');
            $table->foreign('withstand_current_id')->on('current_flows')->references('id');
            $table->foreign('making_capacity_id')->on('current_flows')->references('id');
            $table->foreign('enpro_earth_switch_current_duration_id')->on('seconds')->references('id');
            $table->foreign('enpro_secondary_voltage_kind_id')->on('secondary_circuits_voltage_kind')->references('id');
            $table->foreign('enpro_secondary_voltage_id')->on('voltages')->references('id');
            $table->foreign('switch_info_id')->on('switch_info')->references('id');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
        Schema::dropIfExists('old_switch_info');

        }
    }

}
