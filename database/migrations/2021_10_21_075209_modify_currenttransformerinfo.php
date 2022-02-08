<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCurrenttransformerinfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
            Schema::table('currenttransformerinfo', function (Blueprint $table) {
                //Добавляем поле
                $table->unsignedBigInteger('asset_info_id')->nullable();
                $table->unsignedBigInteger('rated_voltage_id')->nullable();
                $table->unsignedBigInteger('enpro_max_voltage_id')->nullable();
                $table->string('accuracyclass', 255)->nullable(); //
                $table->unsignedBigInteger('rated_frequency_id')->nullable();
                $table->integer('corecount')->nullable();
                $table->unsignedBigInteger('nominal_ratio_id')->nullable();
                $table->unsignedBigInteger('rated_current_id')->nullable();
                $table->unsignedBigInteger('enpro_climatic_mod_placement_id')->nullable();

                //Добавляем ключи
                $table->foreign('asset_info_id')->on('asset_info')->references('id')->onDelete('cascade');
                $table->foreign('rated_voltage_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->foreign('enpro_max_voltage_id')->on('voltages')->references('id')->onDelete('cascade');
                $table->foreign('rated_frequency_id')->on('frequency')->references('id')->onDelete('cascade');
                $table->foreign('nominal_ratio_id')->on('ratio')->references('id')->onDelete('cascade');
                $table->foreign('rated_current_id')->on('current_flows')->references('id')->onDelete('cascade');
                $table->foreign('enpro_climatic_mod_placement_id')->on('gost_climatic_mod_placement_kind')->references('id')->onDelete('cascade');

                //Переименовываем поля в нижний регистр букв
                $table->renameColumn('ASSETINFOKEY', 'assetinfokey');
                $table->renameColumn('UMAX', 'umax');
                $table->renameColumn('INOM1', 'inom1');
                $table->renameColumn('INOM2', 'inom2');
                $table->renameColumn('IELST', 'ielst');
                $table->renameColumn('F', 'f');
                $table->renameColumn('SNOM_Z', 'snom_z');
                $table->renameColumn('IPRKRO_Z', 'iprkro_z');
                $table->renameColumn('IKRT_1', 'ikrt_1');
                $table->renameColumn('IKRT_3', 'ikrt_3');
                $table->renameColumn('IKRELST', 'ikrelst');
                $table->renameColumn('UISP_1', 'uisp_1');
                $table->renameColumn('UISP_GI', 'uisp_gi');
                $table->renameColumn('IPRKRO_15', 'iprkro_15');
                $table->renameColumn('UNOM_DO', 'unom_do');
                $table->renameColumn('ITERM_1', 'iterm_1');
                $table->renameColumn('ITERM_3', 'iterm_3');
                $table->renameColumn('MASSA', 'massa');
                $table->renameColumn('N_VO', 'n_vo');
                $table->renameColumn('KLASST_Z', 'klasst_z');
                $table->renameColumn('KLASST_IZ', 'klasst_iz');
                $table->renameColumn('SNOM_IZ', 'snom_iz');
                $table->renameColumn('UNOM', 'unom');
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
        Schema::table('currenttransformerinfo', function($table) {
            $table->dropForeign('currenttransformerinfo_asset_info_id');
            $table->dropForeign('currenttransformerinfo_rated_voltage_id');
            $table->dropForeign('currenttransformerinfo_enpro_max_voltage_id');
            $table->dropForeign('currenttransformerinfo_rated_frequency_id');
            $table->dropForeign('currenttransformerinfo_nominal_ratio_id');
            $table->dropForeign('currenttransformerinfo_rated_current_id');
            $table->dropForeign('currenttransformerinfo_enpro_climatic_mod_placement_id');

            $table->dropColumn('asset_info_id');
            $table->dropColumn('rated_voltage_id');
            $table->dropColumn('enpro_max_voltage_id');
            $table->dropColumn('accuracyclass'); //
            $table->dropColumn('rated_frequency_id');
            $table->dropColumn('corecount');
            $table->dropColumn('nominal_ratio_id');
            $table->dropColumn('rated_current_id');
            $table->dropColumn('enpro_climatic_mod_placement_id');
        });
    }
}
