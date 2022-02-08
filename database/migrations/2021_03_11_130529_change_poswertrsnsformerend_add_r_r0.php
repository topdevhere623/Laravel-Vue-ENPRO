<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePoswertrsnsformerendAddRR0 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('powertransformerend', function (Blueprint $table) {
            //
            $table->float('r')->default(0.0);
            $table->float('r0')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('powertransformerend', function (Blueprint $table) {
            //
        });
    }
}
