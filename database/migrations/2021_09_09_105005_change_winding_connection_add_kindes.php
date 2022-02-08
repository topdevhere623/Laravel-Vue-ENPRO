<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeWindingConnectionAddKindes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('winding_connections', function (Blueprint $table) {
            $table->string('ru_value')->nullable(true);
            $table->string('enpro_code')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('winding_connections', function (Blueprint $table) {
            $table->dropColumn('ru_value');
            $table->dropColumn('enpro_code');
        });
        //
    }
}
