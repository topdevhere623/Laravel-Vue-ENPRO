<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySubstationXsdeDefaultValueTable extends Migration
{
    public function up()
    {
        Schema::table('substation', function (Blueprint $table) {
            $table->longText('xsde')->nullable()->change();
        });
    }

    // на случай отката
    public function down()
    {
        //
    }
}