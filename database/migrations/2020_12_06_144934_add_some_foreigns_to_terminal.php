<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeForeignsToTerminal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terminal', function (Blueprint $table) {
            $table->unsignedBigInteger('connectivitycode_id')->nullable();

            $table->foreign('connectivitycode_id')
                ->references('id')->on('connectivitycode')
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
        Schema::table('terminal', function (Blueprint $table) {
            //
        });
    }
}
