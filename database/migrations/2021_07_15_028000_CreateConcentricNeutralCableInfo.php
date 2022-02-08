<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcentricNeutralCableInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('concentric_neutral_cable_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('neutralStrandCount')->nullable(true);
            $table->unsignedBigInteger('diameter_over_neutral_id')->nullable(true);
            $table->unsignedBigInteger('neutral_strand_gmr_id')->nullable(true);
            $table->unsignedBigInteger('neutral_strand_radius_id')->nullable(true);
            $table->unsignedBigInteger('neutral_strand_r_d_c20_id')->nullable(true);
            $table->unsignedBigInteger('cable_info_id')->nullable(true);

            $table->foreign('diameter_over_neutral_id')->on('lengths')->references('id');
            $table->foreign('neutral_strand_gmr_id')->on('lengths')->references('id');
            $table->foreign('neutral_strand_radius_id')->on('lengths')->references('id');
            $table->foreign('neutral_strand_r_d_c20_id')->on('resistance_per_length')->references('id');
            $table->foreign('cable_info_id')->on('cable_info')->references('id');

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
        Schema::dropIfExists('concentric_neutral_cable_info');

        }
    }

}
