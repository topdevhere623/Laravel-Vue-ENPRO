<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotShortCircuitTestTransformerEndInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_short_circuit_test_transformer_end_info', function (Blueprint $table) {
            
            $table->unsignedBigInteger('short_circuit_test_id')->nullable(false);
            $table->unsignedBigInteger('transformer_end_info_id')->nullable(false);

            $table->foreign('short_circuit_test_id', 'psctteisct_foreign')->on('short_circuit_test')->references('id');
            $table->foreign('transformer_end_info_id', 'psctteitei_foreign')->on('transformer_end_info')->references('id');

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
        Schema::dropIfExists('short_circuit_test');

        }
    }

}
