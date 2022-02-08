<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConductionequipmentToPowertransformerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('powertransformer', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('conducting_equipment_id')->nullable();

            $table->foreign('conducting_equipment_id')
                ->references('id')->on('conducting_equipment')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('powertransformer', function (Blueprint $table) {
            //
        });
    }
}
