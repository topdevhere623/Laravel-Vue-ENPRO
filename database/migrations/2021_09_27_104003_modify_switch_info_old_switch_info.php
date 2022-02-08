<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySwitchInfoOldSwitchInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
            Schema::table('switch_info', function (Blueprint $table) {
                $table->dropForeign('switch_info_rated_voltage_id_foreign');
                $table->foreign('rated_voltage_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_enpro_max_voltage_id_foreign');
                $table->foreign('enpro_max_voltage_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_rated_frequency_id_foreign');
                $table->foreign('rated_frequency_id')->on('frequency')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_rated_current_id_foreign');
                $table->foreign('rated_current_id')->on('current_flows')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_breaking_capacity_id_foreign');
                $table->foreign('breaking_capacity_id')->on('current_flows')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_rated_interrupting_time_id_foreign');
                $table->foreign('rated_interrupting_time_id')->on('seconds')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_rated_impulse_withstand_voltage_id_foreign');
                $table->foreign('rated_impulse_withstand_voltage_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_enpro_rated_pressure_id_foreign');
                $table->foreign('enpro_rated_pressure_id')->on('pressure')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_low_pressure_alarm_id_foreign');
                $table->foreign('low_pressure_alarm_id')->on('pressure')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_low_pressure_lock_out_id_foreign');
                $table->foreign('low_pressure_lock_out_id')->on('enpro_force')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_enpro_insulation_length_id_foreign');
                $table->foreign('enpro_insulation_length_id')->on('lengths')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_enpro_temperature_range_id_foreign');
                $table->foreign('enpro_temperature_range_id')->on('enpro_temperature_range')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_gas_weight_per_tank_id_foreign');
                $table->foreign('gas_weight_per_tank_id')->on('mass')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_oil_volume_per_tank_id_foreign');
                $table->foreign('oil_volume_per_tank_id')->on('mass')->references('id')->onDelete('cascade');
                $table->dropForeign('switch_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id')->onDelete('cascade');
            });
            Schema::table('old_switch_info', function (Blueprint $table) {
                $table->dropForeign('old_switch_info_oil_volume_per_tank_id_foreign');
                $table->foreign('oil_volume_per_tank_id')->on('mass')->references('id')->onDelete('cascade');
                $table->dropForeign('old_switch_info_withstand_current_id_foreign');
                $table->foreign('withstand_current_id')->on('current_flows')->references('id')->onDelete('cascade');
                $table->dropForeign('old_switch_info_making_capacity_id_foreign');
                $table->foreign('making_capacity_id')->on('current_flows')->references('id')->onDelete('cascade');
                $table->dropForeign('old_switch_info_enpro_earth_switch_current_duration_id_foreign');
                $table->foreign('enpro_earth_switch_current_duration_id')->on('seconds')->references('id')->onDelete('cascade');
                $table->dropForeign('old_switch_info_enpro_secondary_voltage_id_foreign');
                $table->foreign('enpro_secondary_voltage_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->dropForeign('old_switch_info_switch_info_id_foreign');
                $table->foreign('switch_info_id')->on('switch_info')->references('id')->onDelete('cascade');
            });
            Schema::table('enpro_temperature_range', function (Blueprint $table) {
                $table->dropForeign('enpro_temperature_range_min_temperature_id_foreign');
                $table->foreign('min_temperature_id')->on('temperatures')->references('id')->onDelete('cascade');
                $table->dropForeign('enpro_temperature_range_max_temperature_id_foreign');
                $table->foreign('max_temperature_id')->on('temperatures')->references('id')->onDelete('cascade');
            });
            Schema::table('breaker_info', function (Blueprint $table) {
                $table->dropForeign('breaker_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id')->onDelete('cascade');
            });
            Schema::table('recloser_info', function (Blueprint $table) {
                $table->dropForeign('recloser_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id')->onDelete('cascade');
            });
            Schema::table('fuse_info', function (Blueprint $table) {
                $table->dropForeign('fuse_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id')->onDelete('cascade');
            });
            Schema::table('disconnector_info', function (Blueprint $table) {
                $table->dropForeign('disconnector_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id')->onDelete('cascade');
            });
            Schema::table('load_break_switch_info', function (Blueprint $table) {
                $table->dropForeign('load_break_switch_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
            Schema::table('switch_info', function (Blueprint $table) {
                $table->dropForeign('switch_info_rated_voltage_id_foreign');
                $table->foreign('rated_voltage_id')->on('voltages')->references('id');
                $table->dropForeign('switch_info_enpro_max_voltage_id_foreign');
                $table->foreign('enpro_max_voltage_id')->on('voltages')->references('id');
                $table->dropForeign('switch_info_rated_frequency_id_foreign');
                $table->foreign('rated_frequency_id')->on('frequency')->references('id');
                $table->dropForeign('switch_info_rated_current_id_foreign');
                $table->foreign('rated_current_id')->on('current_flows')->references('id');
                $table->dropForeign('switch_info_breaking_capacity_id_foreign');
                $table->foreign('breaking_capacity_id')->on('current_flows')->references('id');
                $table->dropForeign('switch_info_rated_interrupting_time_id_foreign');
                $table->foreign('rated_interrupting_time_id')->on('seconds')->references('id');
                $table->dropForeign('switch_info_rated_impulse_withstand_voltage_id_foreign');
                $table->foreign('rated_impulse_withstand_voltage_id')->on('voltages')->references('id');
                $table->dropForeign('switch_info_enpro_rated_pressure_id_foreign');
                $table->foreign('enpro_rated_pressure_id')->on('pressure')->references('id');
                $table->dropForeign('switch_info_low_pressure_alarm_id_foreign');
                $table->foreign('low_pressure_alarm_id')->on('pressure')->references('id');
                $table->dropForeign('switch_info_low_pressure_lock_out_id_foreign');
                $table->foreign('low_pressure_lock_out_id')->on('enpro_force')->references('id');
                $table->dropForeign('switch_info_enpro_insulation_length_id_foreign');
                $table->foreign('enpro_insulation_length_id')->on('lengths')->references('id');
                $table->dropForeign('switch_info_enpro_temperature_range_id_foreign');
                $table->foreign('enpro_temperature_range_id')->on('enpro_temperature_range')->references('id');
                $table->dropForeign('switch_info_gas_weight_per_tank_id_foreign');
                $table->foreign('gas_weight_per_tank_id')->on('mass')->references('id');
                $table->dropForeign('switch_info_oil_volume_per_tank_id_foreign');
                $table->foreign('oil_volume_per_tank_id')->on('mass')->references('id');
                $table->dropForeign('switch_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id');
            });
            Schema::table('old_switch_info', function (Blueprint $table) {
                $table->dropForeign('old_switch_info_oil_volume_per_tank_id_foreign');
                $table->foreign('oil_volume_per_tank_id')->on('mass')->references('id');
                $table->dropForeign('old_switch_info_withstand_current_id_foreign');
                $table->foreign('withstand_current_id')->on('current_flows')->references('id');
                $table->dropForeign('old_switch_info_making_capacity_id_foreign');
                $table->foreign('making_capacity_id')->on('current_flows')->references('id');
                $table->dropForeign('old_switch_info_enpro_earth_switch_current_duration_id_foreign');
                $table->foreign('enpro_earth_switch_current_duration_id')->on('seconds')->references('id');
                $table->dropForeign('old_switch_info_enpro_secondary_voltage_id_foreign');
                $table->foreign('enpro_secondary_voltage_id')->on('voltages')->references('id');
                $table->dropForeign('old_switch_info_switch_info_id_foreign');
                $table->foreign('switch_info_id')->on('switch_info')->references('id');
            });
            Schema::table('enpro_temperature_range', function (Blueprint $table) {
                $table->dropForeign('enpro_temperature_range_min_temperature_id_foreign');
                $table->foreign('min_temperature_id')->on('temperatures')->references('id');
                $table->dropForeign('enpro_temperature_range_max_temperature_id_foreign');
                $table->foreign('max_temperature_id')->on('temperatures')->references('id');
            });
            Schema::table('breaker_info', function (Blueprint $table) {
                $table->dropForeign('breaker_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            });
            Schema::table('recloser_info', function (Blueprint $table) {
                $table->dropForeign('recloser_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            });
            Schema::table('fuse_info', function (Blueprint $table) {
                $table->dropForeign('fuse_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            });
            Schema::table('disconnector_info', function (Blueprint $table) {
                $table->dropForeign('disconnector_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            });
            Schema::table('load_break_switch_info', function (Blueprint $table) {
                $table->dropForeign('load_break_switch_info_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            });
        }
    }
}
