<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->softDeletes();

            $table->boolean('aggregate')->default(false);
            $table->boolean('inService')->default(false);
            $table->boolean('networkAnalysisEnabled')->default(false);
            $table->boolean('normallyInService')->default(false);

            $table->unsignedBigInteger('power_system_resources_id');

            $table->foreign('power_system_resources_id')
                ->references('id')->on('power_system_resources')
                ->onDelete('cascade')
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
        Schema::dropIfExists('equipment');
    }
}
