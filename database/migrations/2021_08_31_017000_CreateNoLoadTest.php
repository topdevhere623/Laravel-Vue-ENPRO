<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoLoadTest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('no_load_test', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('energised_end_id')->nullable(true);
            $table->unsignedBigInteger('loss_id')->nullable(true);
            $table->unsignedBigInteger('exciting_current_id')->nullable(true);
            $table->unsignedBigInteger('transformer_test_id')->nullable(true);

            $table->foreign('energised_end_id')->on('transformer_end_info')->references('id');
            $table->foreign('loss_id')->on('kilo_active_power')->references('id');
            $table->foreign('exciting_current_id')->on('per_cent')->references('id');
            $table->foreign('transformer_test_id')->on('transformer_test')->references('id');

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
        Schema::dropIfExists('no_load_test');

        }
    }

}
