<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDisconnectorinfoOld extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disconnectorinfo', function (Blueprint $table) {
            $table->unsignedBigInteger('old_switch_info_id')->nullable(true);
            $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            $table->boolean('status')->default(1)->nullable(true)->change();
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
            Schema::table('disconnectorinfo', function (Blueprint $table) {
                $table->dropForeign('disconnectorinfo_old_switch_info_id_foreign');
                $table->dropColumn('old_switch_info_id');
                $table->boolean('status')->default(1)->nullable(false)->change();
            });

        }
    }

}
