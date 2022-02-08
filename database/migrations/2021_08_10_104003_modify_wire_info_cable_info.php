<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyWireInfoCableInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enpro_fire_safety_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->string('ru_value')->nullable(true);
            $table->string('enpro_code')->nullable(true);
            $table->string('description')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('duration', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('wire_info', function (Blueprint $table) {
            $table->unsignedBigInteger('nominal_voltage_id')->nullable(true);
            $table->foreign('nominal_voltage_id')->on('voltages')->references('id');
            $table->unsignedBigInteger('standard_service_life_id')->nullable(true);
            $table->foreign('standard_service_life_id')->on('duration')->references('id');
        });

        Schema::table('cable_info', function (Blueprint $table) {
            $table->unsignedBigInteger('fire_safety_id')->nullable(true);
            $table->foreign('fire_safety_id')->on('enpro_fire_safety_kind')->references('id');
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
                $table->dropForeign('wire_info_nominal_voltage_id_foreign');
                $table->dropColumn('nominal_voltage_id');
                $table->dropForeign('wire_info_standard_service_life_id_foreign');
                $table->dropColumn('standard_service_life_id');
            });
            Schema::table('cable_info', function (Blueprint $table) {
                $table->dropForeign('cable_info_fire_safety_id_foreign');
                $table->dropColumn('fire_safety_id');
            });
            Schema::dropIfExists('enpro_fire_safety_kind');
            Schema::dropIfExists('duration');
        }
    }
}
