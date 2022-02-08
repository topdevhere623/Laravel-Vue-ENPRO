<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('line')) return;
        Schema::create('line', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->softDeletes();

            $table->unsignedBigInteger('equipment_containers_id')->nullable();
            // связи
            $table->foreign('equipment_containers_id')
                ->references('id')->on('equipment_containers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('sub_geographical_regions_id')->nullable();
            // связи
            $table->foreign('sub_geographical_regions_id')
                ->references('id')->on('sub_geographical_regions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('line');
    }
}
