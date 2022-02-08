<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwitchInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('switch_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('isSinglePhase')->nullable(true);
            $table->boolean('isUnganged')->nullable(true);
            $table->unsignedBigInteger('enpro_breaker_kind_id')->nullable(true);
            $table->unsignedBigInteger('enpro_interrupter_position_id')->nullable(true);
            $table->unsignedBigInteger('rated_voltage_id')->nullable(true);
            $table->unsignedBigInteger('enpro_max_voltage_id')->nullable(true);
            $table->unsignedBigInteger('rated_frequency_id')->nullable(true);
            $table->unsignedBigInteger('rated_current_id')->nullable(true);
            $table->unsignedBigInteger('breaking_capacity_id')->nullable(true);
            $table->unsignedBigInteger('rated_interrupting_time_id')->nullable(true);
            $table->unsignedBigInteger('rated_impulse_withstand_voltage_id')->nullable(true);
            $table->unsignedBigInteger('enpro_rated_pressure_id')->nullable(true);
            $table->unsignedBigInteger('low_pressure_alarm_id')->nullable(true);
            $table->unsignedBigInteger('low_pressure_lock_out_id')->nullable(true);
            $table->unsignedBigInteger('enpro_insulation_length_id')->nullable(true);
            $table->unsignedBigInteger('enpro_climatic_mod_placement_id')->nullable(true);
            $table->unsignedBigInteger('enpro_temperature_range_id')->nullable(true);
            $table->unsignedBigInteger('enpro_gost_id')->nullable(true);
            $table->unsignedBigInteger('gas_weight_per_tank_id')->nullable(true);
            $table->unsignedBigInteger('oil_volume_per_tank_id')->nullable(true);
            $table->unsignedBigInteger('asset_info_id')->nullable(true);

            $table->foreign('enpro_breaker_kind_id')->on('breaker_construction_kind')->references('id');
            $table->foreign('enpro_interrupter_position_id')->on('interrupter_position_kind')->references('id');
            $table->foreign('rated_voltage_id')->on('voltages')->references('id');
            $table->foreign('enpro_max_voltage_id')->on('voltages')->references('id');
            $table->foreign('rated_frequency_id')->on('frequency')->references('id');
            $table->foreign('rated_current_id')->on('current_flows')->references('id');
            $table->foreign('breaking_capacity_id')->on('current_flows')->references('id');
            $table->foreign('rated_interrupting_time_id')->on('seconds')->references('id');
            $table->foreign('rated_impulse_withstand_voltage_id')->on('voltages')->references('id');
            $table->foreign('enpro_rated_pressure_id')->on('pressure')->references('id');
            $table->foreign('low_pressure_alarm_id')->on('pressure')->references('id');
            $table->foreign('low_pressure_lock_out_id')->on('enpro_force')->references('id');
            $table->foreign('enpro_insulation_length_id')->on('lengths')->references('id');
            $table->foreign('enpro_climatic_mod_placement_id')->on('gost_climatic_mod_placement_kind')->references('id');
            $table->foreign('enpro_temperature_range_id')->on('enpro_temperature_range')->references('id');
            $table->foreign('enpro_gost_id')->on('gost')->references('id');
            $table->foreign('gas_weight_per_tank_id')->on('mass')->references('id');
            $table->foreign('oil_volume_per_tank_id')->on('mass')->references('id');
            $table->foreign('asset_info_id')->on('asset_info')->references('id');

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
        Schema::dropIfExists('switch_info');

        }
    }

}
