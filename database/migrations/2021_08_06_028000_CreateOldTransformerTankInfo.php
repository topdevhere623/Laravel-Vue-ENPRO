<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldTransformerTankInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transformer_construction_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->string('ru_value')->nullable(true);
            $table->string('enpro_code')->nullable(true);
            $table->string('description')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transformer_core_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->string('ru_value')->nullable(true);
            $table->string('enpro_code')->nullable(true);
            $table->string('description')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transformer_function_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->string('ru_value')->nullable(true);
            $table->string('enpro_code')->nullable(true);
            $table->string('description')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transformer_cooling_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(true);
            $table->string('ru_value')->nullable(true);
            $table->string('enpro_code')->nullable(true);
            $table->string('description')->nullable(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('enpro_temperature_range', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('min_temperature_id')->nullable(true);
            $table->foreign('min_temperature_id')->on('temperatures')->references('id');
            $table->unsignedBigInteger('max_temperature_id')->nullable(true);
            $table->foreign('max_temperature_id')->on('temperatures')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('transformer_tank_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_info_id')->nullable(true);
            $table->foreign('asset_info_id')->on('asset_info')->references('id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('old_transformer_tank_info', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('transformer_tank_info_id')->nullable(true);
            $table->foreign('transformer_tank_info_id')->on('transformer_tank_info')->references('id');

            $table->unsignedBigInteger('construction_kind_id')->nullable(true);
            $table->foreign('construction_kind_id')->on('transformer_construction_kind')->references('id');

            $table->unsignedBigInteger('core_coils_weight_id')->nullable(true);
            $table->foreign('core_coils_weight_id')->on('mass')->references('id');

            $table->unsignedBigInteger('core_kind_id')->nullable(true);
            $table->foreign('core_kind_id')->on('transformer_construction_kind')->references('id');

            $table->unsignedBigInteger('function_id')->nullable(true);
            $table->foreign('function_id')->on('transformer_function_kind')->references('id');

            $table->unsignedBigInteger('cooling_kind_id')->nullable(true);
            $table->foreign('cooling_kind_id')->on('transformer_cooling_kind')->references('id');

            $table->unsignedBigInteger('enpro_full_weight_id')->nullable(true);
            $table->foreign('enpro_full_weight_id')->on('mass')->references('id');

            $table->unsignedBigInteger('enpro_oil_weight_id')->nullable(true);
            $table->foreign('enpro_oil_weight_id')->on('mass')->references('id');

            $table->unsignedBigInteger('enpro_temperature_range_id')->nullable(true);
            $table->foreign('enpro_temperature_range_id')->on('enpro_temperature_range')->references('id');

            $table->unsignedBigInteger('gost_id')->nullable(true);
            $table->foreign('gost_id')->on('gost')->references('id');

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
            Schema::dropIfExists('old_transformer_tank_info');
            Schema::dropIfExists('transformer_tank_info');
            Schema::dropIfExists('temperature_range');
            Schema::dropIfExists('transformer_construction_kind');
            Schema::dropIfExists('transformer_core_kind');
            Schema::dropIfExists('transformer_cooling_kind');



        }
    }

}
