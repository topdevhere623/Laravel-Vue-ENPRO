<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeClampsAddRelation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clamps', function (Blueprint $table) {
            $table->unsignedBigInteger('conducting_equipment_id')->nullable();
            $table->foreign('conducting_equipment_id')->references('id')->on('conducting_equipment');
            $table->unsignedBigInteger('aclinesegment_id')->nullable();
            $table->foreign('aclinesegment_id')->references('id')->on('aclinesegment');
            $table->unsignedBigInteger('lengths_id')->nullable();
            $table->foreign('lengths_id')->references('id')->on('lengths');
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
