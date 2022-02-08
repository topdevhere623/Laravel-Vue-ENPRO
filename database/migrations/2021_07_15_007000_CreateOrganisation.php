<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganisation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('organisation', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('electronicAddress')->nullable(true);
            $table->string('phone1')->nullable(true);
            $table->string('phone2')->nullable(true);
            $table->unsignedBigInteger('postal_address_id')->nullable(true);
            $table->unsignedBigInteger('street_address_id')->nullable(true);

            $table->foreign('postal_address_id')->on('street_address')->references('id');
            $table->foreign('street_address_id')->on('street_address')->references('id');

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
        Schema::dropIfExists('organisation');

        }
    }

}
