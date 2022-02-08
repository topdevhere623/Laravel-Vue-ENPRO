<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySpanPointsTable extends Migration
{
    public function up()
    {
        // увеличить длину поля для хранения массива характерных точек и поля длины пролета
        Schema::table('span', function (Blueprint $table) {
            $table->longText('points')->change();
            $table->float('spanlength', 11, 2)->change();
        });
    }

    // на случай отката
    public function down()
    {
        //
    }
}