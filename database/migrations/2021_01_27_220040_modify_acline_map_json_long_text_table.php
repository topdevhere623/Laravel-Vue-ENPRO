<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAclineMapJsonLongTextTable extends Migration
{
    public function up()
    {
        Schema::table('acline', function (Blueprint $table) {
            $table->longText('map_json')->nullable()->change();
        });
    }

    // на случай отката
    public function down()
    {
        //
    }
}