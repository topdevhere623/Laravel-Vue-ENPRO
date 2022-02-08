<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('location', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('direction')->nullable(true);
            $table->unsignedBigInteger('identifiedobject_id')->nullable(true);
            $table->unsignedBigInteger('coordinate_system_id')->nullable(true);
            $table->unsignedBigInteger('main_address_id')->nullable(true);

            $table->foreign('identifiedobject_id')->on('identifiedobject')->references('id');
            $table->foreign('coordinate_system_id')->on('coordinate_system')->references('id');
            $table->foreign('main_address_id')->on('street_address')->references('id');

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
        Schema::dropIfExists('location');

    }

}
