<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTapChangerControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tap_changer_controls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->float('limitvoltage')->default(0.0);
            $table->boolean('linedropcompensation')->default(false);
            $table->float('linedropr')->default(0.0);
            $table->float('linedropx')->default(0.0);
            $table->float('reverselinedropr')->default(0.0);
            $table->float('reverselinedropx')->default(0.0);

            $table->unsignedBigInteger('regulatingcontrols')->nullable();
            $table->foreign('regulatingcontrols')
                ->references('id')->on('regulating_controls')
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
        Schema::dropIfExists('tap_changer_controls');
    }
}
