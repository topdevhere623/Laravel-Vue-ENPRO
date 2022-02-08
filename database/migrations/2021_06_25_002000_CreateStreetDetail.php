<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStreetDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('street_detail', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('address_general')->nullable(true);
            $table->string('address_general2')->nullable(true);
            $table->string('address_general3')->nullable(true);
            $table->string('building_name')->nullable(true);
            $table->string('code')->nullable(true);
            $table->string('name')->nullable(true);
            $table->string('number')->nullable(true);
            $table->string('prefix')->nullable(true);
            $table->string('suffix')->nullable(true);
            $table->string('suite_number')->nullable(true);
            $table->string('type')->nullable(true);
            $table->boolean('within_town_limits')->nullable(true);


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
        Schema::dropIfExists('street_detail');

    }

}
