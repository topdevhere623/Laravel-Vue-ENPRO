<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWireInfoChangeGost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('wire_info', function (Blueprint $table) {
            $table->dropColumn('enpro_gost');
            $table->unsignedBigInteger('gost_id')->nullable(true);
            $table->foreign('gost_id')->on('gost')->references('id');
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
            Schema::table('wire_info', function (Blueprint $table) {
                $table->string('enpro_gost')->nullable();
                $table->dropForeign('wire_info_gost_id_foreign');
                $table->dropColumn('gost_id');
            });
        }
    }

}
