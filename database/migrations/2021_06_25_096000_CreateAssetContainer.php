<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetContainer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('asset_container', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('asset_id')->nullable(true);

            $table->foreign('asset_id')->on('asset')->references('id');

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
