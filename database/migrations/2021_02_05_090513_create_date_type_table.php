<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDateTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('date_type', function (Blueprint $table) {
            //
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('identifiedobject_id');
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
        Schema::table('date_type', function (Blueprint $table) {
            //
        });
    }
}
