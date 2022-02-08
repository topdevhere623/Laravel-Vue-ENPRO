<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoltagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('voltages', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->unsignedBigInteger('multiplier_id')->nullable();
                $table->unsignedBigInteger('unit_id')->nullable();
                $table->float('value')->nullable();
                $table->foreign('multiplier_id')
                    ->references('id')->on('unit_multiplier')
                    ->onDelete('no action')
                    ->onUpdate('no action');
                $table->foreign('unit_id')
                    ->references('id')->on('unit_symbols')
                    ->onDelete('no action')
                    ->onUpdate('no action');
            });
        } catch (Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voltages');
    }
}
