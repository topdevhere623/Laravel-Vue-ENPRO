<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransformerEndInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transformer_end_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('endNumber')->nullable(true);
            $table->integer('phaseAngleClock')->nullable(true);
            $table->unsignedBigInteger('connection_kind_id')->nullable(true);
            $table->unsignedBigInteger('rated_s_id')->nullable(true);
            $table->unsignedBigInteger('rated_u_id')->nullable(true);
            $table->unsignedBigInteger('r_id')->nullable(true);

            $table->unsignedBigInteger('transformer_tank_info_id')->nullable(true);
            $table->unsignedBigInteger('asset_info_id')->nullable(true);

            $table->foreign('connection_kind_id')->on('winding_connections')->references('id');
            $table->foreign('rated_s_id')->on('apparent_power')->references('id');
            $table->foreign('rated_u_id')->on('voltages')->references('id');
            $table->foreign('r_id')->on('resistances')->references('id');
            $table->foreign('transformer_tank_info_id')->on('transformer_tank_info')->references('id');
            $table->foreign('asset_info_id')->on('asset_info')->references('id');

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
        Schema::dropIfExists('transformer_end_info');

        }
    }

}
