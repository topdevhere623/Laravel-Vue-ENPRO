<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCascadeDeleteAssemblyWireInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
            Schema::table('cable_info', function (Blueprint $table) {
                $table->dropForeign('cable_info_wire_info_id_foreign');
                $table->foreign('wire_info_id')->on('wire_info')->references('id')->onDelete('cascade');
            });
            Schema::table('overhead_wire_info', function (Blueprint $table) {
                $table->dropForeign('overhead_wire_info_wire_info_id_foreign');
                $table->foreign('wire_info_id')->on('wire_info')->references('id')->onDelete('cascade');
            });
            Schema::table('wire_info', function (Blueprint $table) {
                $table->dropForeign('wire_info_assetinfo_id_foreign');
                $table->renameColumn('assetinfo_id', 'asset_info_id');

                $table->dropForeign('wire_info_wire_phase_info_id_foreign');
                $table->foreign('wire_phase_info_id')->on('wire_phase_info')->references('id')->onDelete('cascade');
            });
            Schema::table('wire_info', function (Blueprint $table) {
                $table->foreign('asset_info_id')->on('asset_info')->references('id')->onDelete('cascade');
            });
            Schema::table('wire_assembly_info', function (Blueprint $table) {
                $table->dropForeign('wire_assembly_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id')->onDelete('cascade');
            });
            Schema::table('asset_info', function (Blueprint $table) {
                $table->dropForeign('asset_info_catalog_asset_type_id_foreign');
                $table->foreign('catalog_asset_type_id')->on('catalog_asset_type')->references('id')->onDelete('cascade');
            });
            Schema::table('catalog_asset_type', function (Blueprint $table) {
                $table->dropForeign('catalog_asset_type_identified_object_id_foreign');
                $table->foreign('identified_object_id')->on('identifiedobject')->references('id')->onDelete('cascade');
            });
            Schema::table('wire_phase_info', function (Blueprint $table) {
                $table->dropForeign('wire_phase_info_wire_assembly_info_id_foreign');
                $table->foreign('wire_assembly_info_id')->on('wire_assembly_info')->references('id')->onDelete('cascade');
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
            Schema::table('cable_info', function (Blueprint $table) {
                $table->dropForeign('cable_info_wire_info_id_foreign');
                $table->foreign('wire_info_id')->on('wire_info')->references('id');
            });
            Schema::table('overhead_wire_info', function (Blueprint $table) {
                $table->dropForeign('overhead_wire_info_wire_info_id_foreign');
                $table->foreign('wire_info_id')->on('wire_info')->references('id');
            });
            Schema::table('wire_info', function (Blueprint $table) {
                $table->dropForeign('wire_info_asset_info_id_foreign');
                $table->renameColumn('asset_info_id', 'assetinfo_id');

                $table->dropForeign('wire_info_wire_phase_info_id_foreign');
                $table->foreign('wire_phase_info_id')->on('wire_phase_info')->references('id');
            });
            Schema::table('wire_info', function (Blueprint $table) {
                $table->foreign('assetinfo_id')->on('asset_info')->references('id');
            });
            Schema::table('wire_assembly_info', function (Blueprint $table) {
                $table->dropForeign('wire_assembly_info_asset_info_id_foreign');
                $table->foreign('asset_info_id')->on('asset_info')->references('id');
            });
            Schema::table('asset_info', function (Blueprint $table) {
                $table->dropForeign('asset_info_catalog_asset_type_id_foreign');
                $table->foreign('catalog_asset_type_id')->on('catalog_asset_type')->references('id');
            });
            Schema::table('catalog_asset_type', function (Blueprint $table) {
                $table->dropForeign('catalog_asset_type_identified_object_id_foreign');
                $table->foreign('identified_object_id')->on('identifiedobject')->references('id');
            });
            Schema::table('wire_phase_info', function (Blueprint $table) {
                $table->dropForeign('wire_phase_info_wire_assembly_info_id_foreign');
                $table->foreign('wire_assembly_info_id')->on('wire_assembly_info')->references('id');
            });
        }
    }

}
