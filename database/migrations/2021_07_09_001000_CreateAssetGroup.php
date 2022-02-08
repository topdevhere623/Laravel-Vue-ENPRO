<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('identifiedobject_id');

            $table->foreign('identifiedobject_id')->on('identifiedobject')->references('id');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('asset_group_kind', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('value')->nullable(false);
            $table->string('description')->nullable(false);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('asset_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asset_group_kind_id')->nullable(false);
            $table->unsignedBigInteger('document_id')->nullable(false);

            $table->foreign('asset_group_kind_id')->on('asset_group_kind')->references('id');
            $table->foreign('document_id')->on('document')->references('id');

            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('pivot_asset_asset_group', function (Blueprint $table) {
            $table->unsignedBigInteger('asset_id')->nullable(false);
            $table->unsignedBigInteger('asset_group_id')->nullable(false);

            $table->foreign('asset_id')->on('asset')->references('id');
            $table->foreign('asset_group_id')->on('asset_group')->references('id');

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
        Schema::dropIfExists('pivot_asset_asset_group');
        Schema::dropIfExists('asset_group');
        Schema::dropIfExists('asset_group_kind');
        Schema::dropIfExists('document');
    }

}
