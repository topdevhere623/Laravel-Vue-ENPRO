<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOldTransformerEndInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('old_transformer_end_info', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('transformer_end_info_id')->nullable(true);
            $table->unsignedBigInteger('winding_insulation_kind_id')->nullable(true);

            $table->foreign('transformer_end_info_id')->on('transformer_end_info')->references('id');
            $table->foreign('winding_insulation_kind_id')->on('winding_insulation_kind')->references('id');
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
        Schema::dropIfExists('old_transformer_end_info');

        }
    }

}
