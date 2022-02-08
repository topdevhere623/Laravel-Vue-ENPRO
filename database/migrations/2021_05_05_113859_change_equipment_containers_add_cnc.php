<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEquipmentContainersAddCnc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('equipment_containers', function (Blueprint $table) {
            $table->unsignedBigInteger('connectivity_node_container_id')->nullable();
            $table->foreign('connectivity_node_container_id')
                ->references('id')->on('connectivity_node_containers');

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
