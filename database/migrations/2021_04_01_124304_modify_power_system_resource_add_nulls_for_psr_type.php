<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPowerSystemResourceAddNullsForPsrType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        // увеличить длину поля для хранения массива характерных точек и поля длины пролета
        Schema::table('power_system_resources', function (Blueprint $table) {
            $table->unsignedBigInteger('psrtype_id')->nullable()->change();
        });
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
