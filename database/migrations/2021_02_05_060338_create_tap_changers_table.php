<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTapChangersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tap_changers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->boolean('controlenabled')->default(true);
            $table->integer('highstep')->default(0);
            $table->double('initialdelay')->default(0.0);
            $table->integer('lowstep')->default(0);
            $table->integer('ltcflag')->default(0);
            $table->integer('neutralstep')->default(0);
            $table->double(' neutralu')->default(0.0);
            $table->integer('normalstep')->default(0);
            $table->double('subsequentdelay')->default(0.0);
            $table->double('step')->default(0.0);

            $table->unsignedBigInteger('power_system_resources_id')->nullable();
            $table->unsignedBigInteger('tapchangercontrol')->nullable();
            $table->unsignedBigInteger('svtapsteps')->nullable();
            $table->foreign('power_system_resources_id')
                ->references('id')->on('power_system_resources')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('tapchangercontrol')
                ->references('id')->on('tap_changer_controls')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('svtapsteps')
                ->references('id')->on('sv_tap_steps')
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
        Schema::dropIfExists('tap_changers');
    }
}
