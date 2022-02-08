<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrequency extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frequency', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->float('value')->nullable(true);
            $table->unsignedBigInteger('multiplier_id')->nullable(true);
            $table->unsignedBigInteger('unit_id')->nullable(true);

            $table->foreign('multiplier_id')->on('unit_multiplier')->references('id');
            $table->foreign('unit_id')->on('unit_symbols')->references('id');

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
        Schema::dropIfExists('frequency');

        }
    }

}
