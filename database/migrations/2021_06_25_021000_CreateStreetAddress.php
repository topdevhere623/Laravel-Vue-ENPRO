<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreetAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('street_address', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('po_box')->nullable(true);
            $table->string('postal_code')->nullable(true);
            $table->unsignedBigInteger('street_detail_id')->nullable(true);
            $table->unsignedBigInteger('town_detail_id')->nullable(true);

            $table->foreign('street_detail_id')->on('street_detail')->references('id');
            $table->foreign('town_detail_id')->on('town_detail')->references('id');

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
        Schema::dropIfExists('street_address');

    }

}
