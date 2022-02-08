<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTapChangerTablePointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tap_changer_table_points', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->float('b')->default(0.0);
            $table->float('g')->default(0.0);
            $table->float('r')->default(0.0);
            $table->float('ratio')->default(0.0);
            $table->integer('step')->default(0);
            $table->float('x')->default(0.0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tap_changer_table_points');
    }
}
