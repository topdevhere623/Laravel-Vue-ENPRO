<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisationRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('organisation_role', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('organisation_id')->nullable(true);
            $table->unsignedBigInteger('identifiedobject_id')->nullable(true);

            $table->foreign('organisation_id')->on('organisation')->references('id');
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
        Schema::dropIfExists('organisation_role');

        }
    }

}
