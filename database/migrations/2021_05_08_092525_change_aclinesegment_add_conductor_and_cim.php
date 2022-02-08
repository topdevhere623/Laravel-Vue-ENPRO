<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAclinesegmentAddConductorAndCim extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aclinesegment', function (Blueprint $table) {
            $table->unsignedBigInteger('conductors_id')->nullable();
            $table->foreign('conductors_id')->references('id')->on('conductors');

            $table->unsignedBigInteger('b0ch_id')->nullable();
            $table->foreign('b0ch_id')->references('id')->on('susceptances');
            $table->unsignedBigInteger('bch_id')->nullable();
            $table->foreign('bch_id')->references('id')->on('susceptances');

            $table->unsignedBigInteger('g0ch_id')->nullable();
            $table->foreign('g0ch_id')->references('id')->on('conductances');
            $table->unsignedBigInteger('gch_id')->nullable();
            $table->foreign('gch_id')->references('id')->on('conductances');

            $table->unsignedBigInteger('r0_id')->nullable();
            $table->foreign('r0_id')->references('id')->on('resistances');
            $table->unsignedBigInteger('r_id')->nullable();
            $table->foreign('r_id')->references('id')->on('resistances');

            $table->unsignedBigInteger('x0_id')->nullable();
            $table->foreign('x0_id')->references('id')->on('reactances');
            $table->unsignedBigInteger('x_id')->nullable();
            $table->foreign('x_id')->references('id')->on('reactances');

            $table->unsignedBigInteger('temperatures_id')->nullable();
            $table->foreign('temperatures_id')->references('id')->on('temperatures');

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
