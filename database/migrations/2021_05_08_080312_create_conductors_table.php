<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConductorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('conductors')) return;
        Schema::create('conductors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('length_id')->nullable();
            $table->foreign('length_id')->references('id')->on('lengths');
            $table->unsignedBigInteger('conducting_equipment_id');
            $table->foreign('conducting_equipment_id')->
            references('id')->on('conducting_equipment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conductors');
    }
}
