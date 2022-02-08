<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductingEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conducting_equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('equipment_id');
            $table->unsignedBigInteger('basevoltage_id');

            $table->foreign('equipment_id')
                ->references('id')->on('equipment')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreign('basevoltage_id')
                ->references('id')->on('basevoltage')
                ->onDelete('no action')
                ->onUpdate('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conducting_equipment');
    }
}
