<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTapSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tap_schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->unsignedBigInteger('season')->nullable();
            $table->unsignedBigInteger('tapchanger')->nullable();
            $table->unsignedBigInteger('daytype')->nullable();

            $table->foreign('tapchanger')
                ->references('id')->on('tap_changers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('season')
                ->references('id')->on('seasons')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('daytype')
                ->references('id')->on('season_day_type_schedules')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tap_schedules');
    }
}
