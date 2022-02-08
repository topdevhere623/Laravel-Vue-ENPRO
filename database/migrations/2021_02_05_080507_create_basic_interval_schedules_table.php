<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasicIntervalSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('basic_interval_schedules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->dateTime('starttime')->nullable();
            $table->char('value1multiplier', 6)->default('M');
            $table->char('value2multiplier', 6)->default('M');
            $table->char('value1unit', 6)->default('N');
            $table->char('value2unit', 6)->default('N');

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
        Schema::dropIfExists('basic_interval_schedules');
    }
}
