<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEquipmentAddSomedata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->unsignedBigInteger('equipment_containers_id')->nullable();
            $table->foreign('equipment_containers_id')
                ->references('id')->on('equipment_containers');

            $table->unsignedBigInteger('add_equipment_containers_id')->nullable();
            $table->foreign('add_equipment_containers_id')
                ->references('id')->on('equipment_containers');
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
