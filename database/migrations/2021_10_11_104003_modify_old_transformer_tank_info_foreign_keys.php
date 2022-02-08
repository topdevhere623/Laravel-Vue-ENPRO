<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOldTransformerTankInfoForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
            Schema::table('old_transformer_tank_info', function (Blueprint $table) {
                $table->dropForeign('old_transformer_tank_info_transformer_tank_info_id_foreign');
                $table->foreign('transformer_tank_info_id')->on('transformer_tank_info')->references('id')->onDelete('cascade');
                $table->dropForeign('old_transformer_tank_info_enpro_full_weight_id_foreign');
                $table->foreign('enpro_full_weight_id')->on('mass')->references('id')->onDelete('cascade');
                $table->dropForeign('old_transformer_tank_info_core_coils_weight_id_foreign');
                $table->foreign('core_coils_weight_id')->on('mass')->references('id')->onDelete('cascade');
                $table->dropForeign('old_transformer_tank_info_enpro_oil_weight_id_foreign');
                $table->foreign('enpro_oil_weight_id')->on('mass')->references('id')->onDelete('cascade');
                $table->dropForeign('old_transformer_tank_info_enpro_temperature_range_id_foreign');
                $table->foreign('enpro_temperature_range_id')->on('enpro_temperature_range')->references('id')->onDelete('cascade');
            });
            Schema::table('transformer_tank_info', function (Blueprint $table) {
                $table->dropForeign('transformer_tank_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id')->onDelete('cascade');
            });

            Schema::table('transformer_end_info', function (Blueprint $table) {
                $table->dropForeign('transformer_end_info_rated_s_id_foreign');
                $table->foreign('rated_s_id')->on('apparent_power')->references('id')->onDelete('cascade');
                $table->dropForeign('transformer_end_info_rated_u_id_foreign');
                $table->foreign('rated_u_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->dropForeign('transformer_end_info_r_id_foreign');
                $table->foreign('r_id')->on('resistances')->references('id')->onDelete('cascade');

                $table->dropForeign('transformer_end_info_transformer_tank_info_id_foreign');
                $table->foreign('transformer_tank_info_id')->on('transformer_tank_info')->references('id')->onDelete('cascade');

                $table->dropForeign('transformer_end_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id')->onDelete('cascade');
            });

            Schema::table('transformer_test', function (Blueprint $table) {
                $table->dropForeign('transformer_test_temperature_id_foreign');
                $table->foreign('temperature_id')->on('temperatures')->references('id')->onDelete('cascade');
                $table->dropForeign('transformer_test_identified_object_id_foreign');
                $table->foreign('identified_object_id')->on('identifiedobject')->references('id')->onDelete('cascade');
            });

            Schema::table('short_circuit_test', function (Blueprint $table) {
                $table->dropForeign('short_circuit_test_energised_end_id_foreign');
                $table->foreign('energised_end_id')->on('transformer_end_info')->references('id')->onDelete('cascade');
                $table->dropForeign('short_circuit_test_loss_id_foreign');
                $table->foreign('loss_id')->on('kilo_active_power')->references('id')->onDelete('cascade');
                $table->dropForeign('short_circuit_test_voltage_id_foreign');
                $table->foreign('voltage_id')->on('per_cent')->references('id')->onDelete('cascade');
                $table->dropForeign('short_circuit_test_transformer_test_id_foreign');
                $table->foreign('transformer_test_id')->on('transformer_test')->references('id')->onDelete('cascade');
            });
            Schema::table('no_load_test', function (Blueprint $table) {
                $table->dropForeign('no_load_test_energised_end_id_foreign');
                $table->foreign('energised_end_id')->on('transformer_end_info')->references('id')->onDelete('cascade');
                $table->dropForeign('no_load_test_loss_id_foreign');
                $table->foreign('loss_id')->on('kilo_active_power')->references('id')->onDelete('cascade');
                $table->dropForeign('no_load_test_exciting_current_id_foreign');
                $table->foreign('exciting_current_id')->on('per_cent')->references('id')->onDelete('cascade');
                $table->dropForeign('no_load_test_transformer_test_id_foreign');
                $table->foreign('transformer_test_id')->on('transformer_test')->references('id')->onDelete('cascade');
            });
            Schema::table('old_transformer_end_info', function (Blueprint $table) {
                $table->dropForeign('old_transformer_end_info_transformer_end_info_id_foreign');
                $table->foreign('transformer_end_info_id')->on('transformer_end_info')->references('id')->onDelete('cascade');
            });
            Schema::table('pivot_short_circuit_test_transformer_end_info', function (Blueprint $table) {
                $table->dropForeign('psctteisct_foreign');
                $table->foreign('short_circuit_test_id', 'psctteisct_foreign')->on('short_circuit_test')->references('id')->onDelete('cascade');
                $table->dropForeign('psctteitei_foreign');
                $table->foreign('transformer_end_info_id', 'psctteitei_foreign')->on('transformer_end_info')->references('id')->onDelete('cascade');
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
            Schema::table('old_transformer_tank_info', function (Blueprint $table) {
                $table->dropForeign('old_transformer_tank_info_transformer_tank_info_id_foreign');
                $table->foreign('transformer_tank_info_id')->on('transformer_tank_info')->references('id');
                $table->dropForeign('old_transformer_tank_info_enpro_full_weight_id_foreign');
                $table->foreign('enpro_full_weight_id')->on('mass')->references('id');
                $table->dropForeign('old_transformer_tank_info_core_coils_weight_id_foreign');
                $table->foreign('core_coils_weight_id')->on('mass')->references('id');
                $table->dropForeign('old_transformer_tank_info_enpro_oil_weight_id_foreign');
                $table->foreign('enpro_oil_weight_id')->on('mass')->references('id');
                $table->dropForeign('old_transformer_tank_info_enpro_temperature_range_id_foreign');
                $table->foreign('enpro_temperature_range_id')->on('enpro_temperature_range')->references('id');
            });
            Schema::table('transformer_tank_info', function (Blueprint $table) {
                $table->dropForeign('transformer_tank_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id');
            });

            Schema::table('transformer_end_info', function (Blueprint $table) {
                $table->dropForeign('transformer_end_info_rated_s_id_foreign');
                $table->foreign('rated_s_id')->on('apparent_power')->references('id');
                $table->dropForeign('transformer_end_info_rated_u_id_foreign');
                $table->foreign('rated_u_id')->on('voltages')->references('id');
                $table->dropForeign('transformer_end_info_r_id_foreign');
                $table->foreign('r_id')->on('resistances')->references('id');

                $table->dropForeign('transformer_end_info_transformer_tank_info_id_foreign');
                $table->foreign('transformer_tank_info_id')->on('transformer_tank_info')->references('id');

                $table->dropForeign('transformer_end_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id');
            });

            Schema::table('transformer_test', function (Blueprint $table) {
                $table->dropForeign('transformer_test_temperature_id_foreign');
                $table->foreign('temperature_id')->on('temperatures')->references('id');
                $table->dropForeign('transformer_test_identified_object_id_foreign');
                $table->foreign('identified_object_id')->on('identifiedobject')->references('id');
            });

            Schema::table('short_circuit_test', function (Blueprint $table) {
                $table->dropForeign('short_circuit_test_energised_end_id_foreign');
                $table->foreign('energised_end_id')->on('transformer_end_info')->references('id');
                $table->dropForeign('short_circuit_test_loss_id_foreign');
                $table->foreign('loss_id')->on('kilo_active_power')->references('id');
                $table->dropForeign('short_circuit_test_voltage_id_foreign');
                $table->foreign('voltage_id')->on('per_cent')->references('id');
                $table->dropForeign('short_circuit_test_transformer_test_id_foreign');
                $table->foreign('transformer_test_id')->on('transformer_test')->references('id');
            });
            Schema::table('no_load_test', function (Blueprint $table) {
                $table->dropForeign('no_load_test_energised_end_id_foreign');
                $table->foreign('energised_end_id')->on('transformer_end_info')->references('id');
                $table->dropForeign('no_load_test_loss_id_foreign');
                $table->foreign('loss_id')->on('kilo_active_power')->references('id');
                $table->dropForeign('no_load_test_exciting_current_id_foreign');
                $table->foreign('exciting_current_id')->on('per_cent')->references('id');
                $table->dropForeign('no_load_test_transformer_test_id_foreign');
                $table->foreign('transformer_test_id')->on('transformer_test')->references('id');
            });
            Schema::table('old_transformer_end_info', function (Blueprint $table) {
                $table->dropForeign('old_transformer_end_info_transformer_end_info_id_foreign');
                $table->foreign('transformer_end_info_id')->on('transformer_end_info')->references('id');
            });
            Schema::table('pivot_short_circuit_test_transformer_end_info', function (Blueprint $table) {
                $table->dropForeign('psctteisct_foreign');
                $table->foreign('short_circuit_test_id', 'psctteisct_foreign')->on('short_circuit_test')->references('id');
                $table->dropForeign('psctteitei_foreign');
                $table->foreign('transformer_end_info_id', 'psctteitei_foreign')->on('transformer_end_info')->references('id');
            });
        }
    }
}
