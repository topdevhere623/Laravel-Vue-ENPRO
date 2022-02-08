<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UnitMultiplier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_multiplier', function (Blueprint $table) {
            //
            $table->id();
            $table->integer('value')->default(0);
            $table->char('literal', 5)->nullable();
            $table->string('description')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unit_multiplier', function (Blueprint $table) {
            //
        });
    }
}
