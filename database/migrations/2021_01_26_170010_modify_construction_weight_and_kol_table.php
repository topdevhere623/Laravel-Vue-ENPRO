<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyConstructionWeightAndKolTable extends Migration
{
    public function up()
    {
        // увеличить разрядность у веса
        Schema::table('towerconstructionaccessory', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructionaggregate', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructionbase', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructionbasic', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructioninsulator', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructionmetal', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructionstandart', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });
        Schema::table('towerconstructionwood', function (Blueprint $table) {
            $table->float('weight', 20, 7)->change();
        });

        // увеличить разрядность у кол-ва
        Schema::table('towerconstructionaggregate_pivots', function (Blueprint $table) {
            $table->float('kol', 20, 4)->change();
        });
        Schema::table('towerconstructionreal_pivots', function (Blueprint $table) {
            $table->float('kol', 20, 4)->change();
        });
        Schema::table('towerconstruction_pivots', function (Blueprint $table) {
            $table->float('kol', 20, 4)->change();
        });
    }

    // на случай отката
    public function down()
    {
        //
    }
}