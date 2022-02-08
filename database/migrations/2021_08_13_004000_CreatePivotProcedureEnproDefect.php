<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotProcedureEnproDefect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pivot_procedure_enpro_defect', function (Blueprint $table) {
            
            $table->unsignedBigInteger('procedure_id')->nullable(false);
            $table->unsignedBigInteger('enpro_defect_id')->nullable(false);

            $table->foreign('procedure_id', 'ppedp_foreign')->on('procedure')->references('id');
            $table->foreign('enpro_defect_id', 'ppeded_foreign')->on('enpro_defect')->references('id');

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
        Schema::dropIfExists('procedure');

        }
    }

}
