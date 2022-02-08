<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulatingControlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulating_controls', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->boolean('discrete')->default(false);
            $table->boolean('enabled')->default(false);
            $table->string('mode')->default('');
            $table->unsignedBigInteger('phasecode_id')->nullable();
            $table->float('targetdeadband')->default(0.0);
            $table->float('targetvalue')->default(0.0);
            $table->unsignedBigInteger('targetvalueunitmultiplier')->nullable();

            $table->float('maxallowedtargetvalue')->default(0.0);
            $table->float('minallowedtargetvalue')->default(0.0);


            $table->unsignedBigInteger('terminal_id')->nullable();



            $table->unsignedBigInteger('power_system_resources_id')->nullable();

            $table->foreign('power_system_resources_id')
                ->references('id')->on('power_system_resources')
                ->onDelete('cascade')
                ->onUpdate('cascade');


            $table->foreign('phasecode_id')
                ->references('id')->on('phasecode')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('targetvalueunitmultiplier')
                ->references('id')->on('unit_multiplier')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('terminal_id')
                ->references('id')->on('terminal')
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
        Schema::dropIfExists('regulating_controls');
    }
}
