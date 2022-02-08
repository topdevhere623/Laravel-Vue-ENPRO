<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeasonDayTypeSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('season_day_type_schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('regular_interval_schedules_id')->nullable();
            $table->foreign('regular_interval_schedules_id')
                ->references('id')->on('regular_interval_schedules')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('datetype')->nullable();
            $table->foreign('datetype')
                ->references('id')->on('date_type')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('season')->nullable();
            $table->foreign('season')
                ->references('id')->on('seasons')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            //$table->

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('season_day_type_schedules');
    }
}
