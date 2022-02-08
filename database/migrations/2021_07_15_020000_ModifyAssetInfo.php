<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAssetInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('assetinfo', function (Blueprint $table) {

            $table->unsignedBigInteger('product_asset_model_id')->nullable(true);
            $table->unsignedBigInteger('identifiedobject_id')->nullable(true);

            $table->foreign('product_asset_model_id')->on('product_asset_model')->references('id');
            $table->foreign('identifiedobject_id')->on('identifiedobject')->references('id');
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
        Schema::table('assetinfo', function (Blueprint $table) {
            $table->dropForeign('identifiedobject_identifiedobject_id_foreign');

            $table->dropColumn('identifiedobject_id');

            $table->dropForeign('product_asset_model_product_asset_model_id_foreign');

            $table->dropColumn('product_asset_model_id');

        });

        }
    }

}
