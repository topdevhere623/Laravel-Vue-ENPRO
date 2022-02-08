<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTownDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('town_detail', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->nullable(true);
            $table->string('country')->nullable(true);
            $table->string('name')->nullable(true);
            $table->string('section')->nullable(true);
            $table->string('state_or_province')->nullable(true);


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
        Schema::dropIfExists('town_detail');

    }

}
