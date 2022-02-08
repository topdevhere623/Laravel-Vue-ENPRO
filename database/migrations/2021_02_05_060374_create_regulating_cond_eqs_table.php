<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegulatingCondEqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regulating_cond_eqs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();


            $table->boolean('controlenabled')->default(false);
            $table->unsignedBigInteger('conducting_equipment_id');
            $table->unsignedBigInteger('regulatingcontrol')->nullable();

            $table->foreign('regulatingcontrol')
                ->references('id')->on('regulating_controls')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('conducting_equipment_id')
                ->references('id')->on('conducting_equipment')
                ->onDelete('cascade')
                ->onUpdate('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regulating_cond_eqs');
    }
}
