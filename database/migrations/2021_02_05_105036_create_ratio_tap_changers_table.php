<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatioTapChangersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratio_tap_changers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->float('stepvoltageincrement')->default(0.0);
            $table->string('tculcontrolmode')->default('reactive');
            $table->unsignedBigInteger('tap_changers_id')->nullable();
            $table->foreign('tap_changers_id')
                ->references('id')->on('tap_changers')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('ratiotapchangertable')->nullable();
            $table->foreign('ratiotapchangertable')
                ->references('id')->on('ratio_tap_changer_tables')
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
        Schema::dropIfExists('ratio_tap_changers');
    }
}
