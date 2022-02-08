<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTapeShieldCableInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('tape_shield_cable_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('cable_info_id')->nullable(true);

            $table->foreign('cable_info_id')->on('cable_info')->references('id');

            $table->timestamps();
            $table->softDeletes();
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
        Schema::dropIfExists('tape_shield_cable_info');

        }
    }

}
