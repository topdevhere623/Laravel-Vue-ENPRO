<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotAssetPowerSystemResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('pivot_asset_power_system_resources', function (Blueprint $table) {

            $table->unsignedBigInteger('asset_id')->nullable(false);
            $table->unsignedBigInteger('power_system_resources_id')->nullable(false);

            $table->foreign('asset_id', 'papsra_foreign')->on('asset')->references('id');
            $table->foreign('power_system_resources_id', 'papsrpsr_foreign')->on('power_system_resources')->references('id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset');

    }

}
