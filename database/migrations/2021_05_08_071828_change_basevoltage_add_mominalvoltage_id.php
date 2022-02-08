<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBasevoltageAddMominalvoltageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('basevoltage', function (Blueprint $table) {
           $table->unsignedBigInteger('voltages_id')->nullable();
           $table->foreign('voltages_id')->references('id')->on('voltages');

            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->foreign('identifiedobject_id')->references('id')->on('identifiedobject');

        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
