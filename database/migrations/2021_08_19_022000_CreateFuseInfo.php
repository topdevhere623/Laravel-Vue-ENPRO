<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFuseInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuse_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('old_switch_info_id')->nullable(true);

            $table->foreign('old_switch_info_id')->on('old_switch_info')->references('id');

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
        Schema::dropIfExists('fuse_info');

        }
    }

}
