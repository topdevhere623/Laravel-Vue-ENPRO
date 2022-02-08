<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAllKindsEnproCodeTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cable_construction_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('cable_outer_jacket_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('cable_shield_material_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('wire_insulation_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('asset_model_usage_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('corporate_standard_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('wire_material_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
        Schema::table('asset_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_сode', 'enpro_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cable_construction_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('cable_outer_jacket_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('cable_shield_material_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('wire_insulation_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('asset_model_usage_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('corporate_standard_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('wire_material_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
        Schema::table('asset_kind', function (Blueprint $table) {
            $table->renameColumn('enpro_code', 'enpro_сode');
        });
    }
}
