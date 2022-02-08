<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyOldSwitchInfoEnproWithstandCurrentDuration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('old_switch_info', function (Blueprint $table) {
            $table->unsignedBigInteger('enpro_withstand_current_duration_id')->nullable(true);
            $table->foreign('enpro_withstand_current_duration_id')->on('seconds')->references('id');
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
            Schema::table('old_switch_info', function (Blueprint $table) {
                $table->dropForeign('old_switch_info_enpro_withstand_current_duration_id_foreign');
                $table->dropColumn('enpro_withstand_current_duration_id');
            });
        }
    }

}
