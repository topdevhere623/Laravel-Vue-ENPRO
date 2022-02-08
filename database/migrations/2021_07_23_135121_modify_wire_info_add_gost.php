<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWireInfoAddGost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wire_info', function (Blueprint $table) {

            $table->unsignedBigInteger('enpro_force_id')->nullable(true);
            $table->foreign('enpro_force_id')->on('enpro_force')->references('id');

            $table->unsignedBigInteger('enpro_weight_per_length_id')->nullable(true);
            $table->foreign('enpro_weight_per_length_id')->on('enpro_weight_per_length')->references('id');

            $table->string('enpro_gost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
