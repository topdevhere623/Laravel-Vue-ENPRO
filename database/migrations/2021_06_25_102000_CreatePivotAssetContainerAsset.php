<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotAssetContainerAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('pivot_asset_container_asset', function (Blueprint $table) {
            
            $table->unsignedBigInteger('asset_container_id')->nullable(false);
            $table->unsignedBigInteger('asset_id')->nullable(false);

            $table->foreign('asset_container_id', 'pacaac_foreign')->on('asset_container')->references('id');
            $table->foreign('asset_id', 'pacaa_foreign')->on('asset')->references('id');

            $table->timestamps();
            $table->softDeletes();
        });
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asset_container');

    }

}
