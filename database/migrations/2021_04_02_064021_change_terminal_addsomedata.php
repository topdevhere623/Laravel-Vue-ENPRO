<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTerminalAddsomedata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terminal', function (Blueprint $table) {
            $table->unsignedBigInteger('conductingequipment_id')->nullable();

            $table->foreign('conductingequipment_id')
                ->references('id')->on('conducting_equipment')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('phasecode_id')->nullable();

            $table->foreign('phasecode_id')
                ->references('id')->on('phasecode')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('connectivitynode_id')->nullable();

            $table->foreign('connectivitynode_id')
                ->references('id')->on('connectivity_nodes')
                ->onUpdate('cascade');

            $table->unsignedSmallInteger('connected')->default(0);
            $table->unsignedBigInteger('sequencenumber')->default(0);

        });
        //
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
