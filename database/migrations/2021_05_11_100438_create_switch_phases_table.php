<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwitchPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('switch_phases')) return;
        Schema::create('switch_phases', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('phase_side_1')->nullable();
            $table->foreign('phase_side_1')->references('id')->on('single_phase_kinds');
            $table->unsignedBigInteger('phase_side_2')->nullable();
            $table->foreign('phase_side_2')->references('id')->on('single_phase_kinds');
            $table->boolean('normal_open')->default(false);
            $table->boolean('closed')->default(false);
            $table->unsignedBigInteger('current_flows_id')->nullable();
            $table->foreign('current_flows_id')->references('id')->on('current_flows');
            $table->unsignedBigInteger('power_system_resources_id')->nullable();
            $table->foreign('power_system_resources_id')->references('id')->on('power_system_resources');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('switch_phases');
    }
}
