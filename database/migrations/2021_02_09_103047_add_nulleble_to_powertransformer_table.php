<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullebleToPowertransformerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('powertransformer', function (Blueprint $table) {
            $table->unsignedBigInteger('identifiedobject_id')->nullable()->change();
            $table->unsignedBigInteger('tapchangecontrol_id')->nullable()->change();
            $table->unsignedBigInteger('tankdesign_id')->nullable()->change();
            $table->unsignedBigInteger('thetmometeroil_id')->nullable()->change();
            $table->unsignedBigInteger('alarmdevice_id')->nullable()->change();
            $table->unsignedBigInteger('installation_id')->nullable()->change();
            $table->unsignedBigInteger('coolingsystem_id')->nullable()->change();
            $table->unsignedBigInteger('coolantmedia_id')->nullable()->change();
            $table->unsignedBigInteger('gasprotection_id')->nullable()->change();
            $table->unsignedBigInteger('asset_id')->nullable()->change();
            $table->unsignedBigInteger('gasprotectionmark_id')->nullable()->change();
            $table->unsignedBigInteger('GASPROTECTIONOTHERMARK_ID')->nullable()->change();
            $table->unsignedBigInteger('conducting_equipment_id')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('powertransformer', function (Blueprint $table) {
            //
        });
    }
}
