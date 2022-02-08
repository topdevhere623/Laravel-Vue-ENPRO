<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAssetsAddEnproClassDefect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table) {

            $table->unsignedBigInteger('enpro_class_defect_id')->nullable(true);

            $table->foreign('enpro_class_defect_id')->on('enpro_class_defect')->references('id');
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
            Schema::table('asset', function (Blueprint $table) {
                $table->dropForeign('enpro_class_defect_id_foreign');

                $table->dropColumn('enpro_class_defect_id');

            });

        }
    }
}
