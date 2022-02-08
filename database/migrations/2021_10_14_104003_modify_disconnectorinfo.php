<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyDisconnectorInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (env('DB_CONNECTION') != 'sqlite') {
            Schema::table('disconnectorinfo', function (Blueprint $table) {
                $table->dropForeign('disconnectorinfo_old_switch_info_id_foreign');
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id')->onDelete('cascade');
            });
            Schema::dropIfExists('disconnector_info');
        }
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
                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');
            });
            Schema::create('disconnector_info', function (Blueprint $table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('old_switch_info_id')->nullable(true);

                $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');

                $table->timestamps();
                $table->softDeletes();
            });
        }
    }
}
