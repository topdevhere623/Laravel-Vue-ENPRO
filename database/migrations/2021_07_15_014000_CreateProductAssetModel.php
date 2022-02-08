<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductAssetModel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('product_asset_model', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('catalogueNumber')->nullable(true);
            $table->string('drawingNumber')->nullable(true);
            $table->string('instructionManual')->nullable(true);
            $table->string('modelNumber')->nullable(true);
            $table->string('modelVersion')->nullable(true);
            $table->string('styleNumber')->nullable(true);
            $table->unsignedBigInteger('usage_kind_id')->nullable(true);
            $table->unsignedBigInteger('manufacturer_id')->nullable(true);
            $table->unsignedBigInteger('corporate_standard_kind_id')->nullable(true);
            $table->unsignedBigInteger('overall_length_id')->nullable(true);
            $table->unsignedBigInteger('weight_total_id')->nullable(true);
            $table->unsignedBigInteger('identifiedobject_id')->nullable(true);

            $table->foreign('usage_kind_id')->on('asset_model_usage_kind')->references('id');
            $table->foreign('manufacturer_id')->on('manufacturer')->references('id');
            $table->foreign('corporate_standard_kind_id')->on('corporate_standard_kind')->references('id');
            $table->foreign('overall_length_id')->on('lengths')->references('id');
            $table->foreign('weight_total_id')->on('mass')->references('id');
            $table->foreign('identifiedobject_id')->on('identifiedobject')->references('id');

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
        if (env('DB_CONNECTION') != 'sqlite') {
        Schema::dropIfExists('product_asset_model');

        }
    }

}
