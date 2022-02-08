<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateACDCTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('a_c_d_c_terminals')) return;
        Schema::create('a_c_d_c_terminals', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('connected')->default(false);
            $table->integer('sequence_number')->default(0);
            $table->unsignedBigInteger('bus_name_marker')->nullable();
            $table->unsignedBigInteger('reporting_group')->nullable();
            $table->unsignedBigInteger('topological_node')->nullable();
            $table->unsignedBigInteger('identifiedobject_id')->nullable();
            $table->foreign('identifiedobject_id')->references('id')->on('identifiedobject');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('a_c_d_c_terminals');
    }
}
