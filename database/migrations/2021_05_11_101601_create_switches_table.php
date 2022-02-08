<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSwitchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('switches')) {
            Schema::create('switches', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();

                $table->unsignedBigInteger('conducting_equipment_id')->nullable();
                $table->foreign('conducting_equipment_id')->references('id')->on('conducting_equipment');

                $table->boolean('normal_open')->default(false);

                $table->unsignedBigInteger('current_flows_id')->nullable();
                $table->foreign('current_flows_id')->references('id')->on('current_flows');

                $table->boolean('retained')->default(false);

                $table->boolean('open')->default(false);

                $table->boolean('locked')->default(false);

                $table->unsignedBigInteger('composite_switches_id')->nullable();
                $table->foreign('composite_switches_id')->references('id')->on('composite_switches');


            });
        }
        if (!Schema::hasColumn('switch_phases', 'switches_id')) {
            Schema::table('switch_phases', function (Blueprint $table) {
                $table->unsignedBigInteger('switches_id')->nullable();
                $table->foreign('switches_id')->references('id')->on('switches');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('switches');
    }
}
