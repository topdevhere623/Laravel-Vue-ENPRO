<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegularTimePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regular_time_points', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('sequencenumber')->default(0);
            $table->float('value1')->default(0.0);
            $table->float('value2')->default(0.0);
            $table->unsignedBigInteger('interval_schedule');
            $table->foreign('interval_schedule')
                ->references('id')->on('regular_interval_schedules')
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
        Schema::dropIfExists('regular_time_points');
    }
}
