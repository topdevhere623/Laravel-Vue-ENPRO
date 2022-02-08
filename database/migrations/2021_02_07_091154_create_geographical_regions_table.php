<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeographicalRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            Schema::create('geographical_regions', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
                $table->softDeletes();
            });
        } catch (\Exception $e) {

        }
        Schema::table('geographical_regions', function (Blueprint $table) {
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
        Schema::dropIfExists('geographical_regions');
    }
}
