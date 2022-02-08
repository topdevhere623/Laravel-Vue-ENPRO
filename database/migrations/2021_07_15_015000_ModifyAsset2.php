<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyAsset2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('asset', function (Blueprint $table) {

            $table->unsignedBigInteger('assetinfo_id')->nullable(true);

            $table->foreign('assetinfo_id')->on('assetinfo')->references('id');
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
            $table->dropForeign('assetinfo_assetinfo_id_foreign');

            $table->dropColumn('assetinfo_id');

        });

        }
    }

}
