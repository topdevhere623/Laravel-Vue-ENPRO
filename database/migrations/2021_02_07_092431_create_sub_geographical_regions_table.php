<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubGeographicalRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('sub_geographical_regions', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();


                $table->unsignedBigInteger('region')->nullable();

                $table->foreign('region')
                    ->references('id')->on('geographical_regions')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');

            });
        } catch (\Exception $e) {

        }
        Schema::table('sub_geographical_regions', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('identifiedobject_id')->nullable();

            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
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
        Schema::dropIfExists('sub_geographical_regions');
    }
}
