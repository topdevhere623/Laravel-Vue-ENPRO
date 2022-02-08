<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCableInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('cable_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->boolean('isStrandFill')->nullable(true);
            $table->boolean('sheathAsNeutral')->nullable(true);
            $table->unsignedBigInteger('construction_kind_id')->nullable(true);
            $table->unsignedBigInteger('diameter_over_core_id')->nullable(true);
            $table->unsignedBigInteger('diameter_over_insulation_id')->nullable(true);
            $table->unsignedBigInteger('diameter_over_jacket_id')->nullable(true);
            $table->unsignedBigInteger('diameter_over_screen_id')->nullable(true);
            $table->unsignedBigInteger('nominal_temperature_id')->nullable(true);
            $table->unsignedBigInteger('outer_jacket_kind_id')->nullable(true);
            $table->unsignedBigInteger('shield_material_id')->nullable(true);
            $table->unsignedBigInteger('wire_info_id')->nullable(true);

            $table->foreign('construction_kind_id')->on('cable_construction_kind')->references('id');
            $table->foreign('diameter_over_core_id')->on('lengths')->references('id');
            $table->foreign('diameter_over_insulation_id')->on('lengths')->references('id');
            $table->foreign('diameter_over_jacket_id')->on('lengths')->references('id');
            $table->foreign('diameter_over_screen_id')->on('lengths')->references('id');
            $table->foreign('nominal_temperature_id')->on('temperatures')->references('id');
            $table->foreign('outer_jacket_kind_id')->on('cable_outer_jacket_kind')->references('id');
            $table->foreign('shield_material_id')->on('cable_shield_material_kind')->references('id');
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
        Schema::dropIfExists('cable_info');

        }
    }

}
