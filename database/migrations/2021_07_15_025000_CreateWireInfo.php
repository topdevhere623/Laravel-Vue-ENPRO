<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWireInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('wire_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('coreStrandCount')->nullable(true);
            $table->boolean('insulated')->nullable(true);
            $table->string('sizeDescription')->nullable(true);
            $table->integer('strandCount')->nullable(true);
            $table->unsignedBigInteger('core_radius_id')->nullable(true);
            $table->unsignedBigInteger('gmr_id')->nullable(true);
            $table->unsignedBigInteger('insulation_material_id')->nullable(true);
            $table->unsignedBigInteger('insulation_thickness_id')->nullable(true);
            $table->unsignedBigInteger('material_id')->nullable(true);
            $table->unsignedBigInteger('r_a_c25_id')->nullable(true);
            $table->unsignedBigInteger('r_a_c50_id')->nullable(true);
            $table->unsignedBigInteger('r_a_c75_id')->nullable(true);
            $table->unsignedBigInteger('radius_id')->nullable(true);
            $table->unsignedBigInteger('rated_current_id')->nullable(true);
            $table->unsignedBigInteger('r_d_c20_id')->nullable(true);
            $table->unsignedBigInteger('assetinfo_id')->nullable(true);

            $table->foreign('core_radius_id')->on('lengths')->references('id');
            $table->foreign('gmr_id')->on('lengths')->references('id');
            $table->foreign('insulation_material_id')->on('wire_insulation_kind')->references('id');
            $table->foreign('insulation_thickness_id')->on('lengths')->references('id');
            $table->foreign('material_id')->on('wire_material_kind')->references('id');
            $table->foreign('r_a_c25_id')->on('resistance_per_length')->references('id');
            $table->foreign('r_a_c50_id')->on('resistance_per_length')->references('id');
            $table->foreign('r_a_c75_id')->on('resistance_per_length')->references('id');
            $table->foreign('radius_id')->on('lengths')->references('id');
            $table->foreign('rated_current_id')->on('current_flows')->references('id');
            $table->foreign('r_d_c20_id')->on('resistance_per_length')->references('id');
            $table->foreign('assetinfo_id')->on('assetinfo')->references('id');

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
        Schema::dropIfExists('wire_info');

        }
    }

}
