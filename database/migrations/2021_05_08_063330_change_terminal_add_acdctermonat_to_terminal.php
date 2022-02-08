<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTerminalAddAcdctermonatToTerminal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('terminal', function (Blueprint $table) {
            $table->unsignedBigInteger('a_c_d_c_terminals_id')->nullable();
            $table->foreign('a_c_d_c_terminals_id')->references('id')->on('a_c_d_c_terminals');
            //
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
