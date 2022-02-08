<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWireAssemblyInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::rename('assetinfo', 'asset_info');

        Schema::create('wire_assembly_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_info_id')->nullable(true);
            $table->foreign('asset_info_id')->on('asset_info')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('wire_phase_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('phase_info_id')->nullable(true);
            $table->foreign('phase_info_id')->on('single_phase_kinds')->references('id');
            $table->unsignedBigInteger('wire_assembly_info_id')->nullable(true);
            $table->foreign('wire_assembly_info_id')->on('wire_assembly_info')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('wire_info', function (Blueprint $table) {
            $table->unsignedBigInteger('wire_phase_info_id')->nullable(true);
            $table->foreign('wire_phase_info_id')->on('wire_phase_info')->references('id');
        });

        Schema::create('catalog_asset_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('identified_object_id')->nullable(true);
            $table->foreign('identified_object_id')->on('identifiedobject')->references('id');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('asset_info', function (Blueprint $table) {
            $table->unsignedBigInteger('catalog_asset_type_id')->nullable(true);
            $table->foreign('catalog_asset_type_id')->on('catalog_asset_type')->references('id');
        });

        Schema::create('overhead_wire_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wire_info_id')->nullable(true);
            $table->foreign('wire_info_id')->on('wire_info')->references('id');
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
            Schema::dropIfExists('overhead_wire_info');
            Schema::table('asset_info', function (Blueprint $table) {
                $table->dropForeign('asset_info_catalog_asset_type_id_foreign');
                $table->dropColumn('catalog_asset_type_id');
            });
            Schema::dropIfExists('catalog_asset_type');
            Schema::table('wire_info', function (Blueprint $table) {
                $table->dropForeign('wire_info_wire_phase_info_id_foreign');
                $table->dropColumn('wire_phase_info_id');
            });
            Schema::dropIfExists('wire_phase_info');
            Schema::dropIfExists('wire_assembly_info');
            Schema::rename('asset_info', 'assetinfo');
        }
    }

}
