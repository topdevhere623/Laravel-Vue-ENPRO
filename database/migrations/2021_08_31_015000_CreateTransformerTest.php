<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransformerTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transformer_test', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('temperature_id')->nullable(true);
            $table->unsignedBigInteger('identified_object_id')->nullable(true);

            $table->foreign('temperature_id')->on('temperatures')->references('id');
            $table->foreign('identified_object_id')->on('identifiedobject')->references('id');

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
        Schema::dropIfExists('transformer_test');

        }
    }

}
