<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySwitchInfoEnproBreakForce extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('switch_info', function (Blueprint $table) {
            $table->unsignedBigInteger('enpro_break_force_id')->nullable(true);
            $table->foreign('enpro_break_force_id')->on('enpro_force')->references('id');
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
            Schema::table('switch_info', function (Blueprint $table) {
                $table->dropForeign('switch_info_enpro_break_force_id_foreign');
                $table->dropColumn('enpro_break_force_id');
            });
        }
    }

}
