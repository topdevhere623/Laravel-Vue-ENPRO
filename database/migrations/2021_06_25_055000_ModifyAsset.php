<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyAsset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->string('utc_number')->nullable(true);
            $table->string('lot_number')->nullable(true);
            $table->string('electronic_address')->nullable(true);
            $table->unsignedBigInteger('identifiedobject_id')->nullable(true);
            $table->unsignedBigInteger('location_id')->nullable(true);
            $table->unsignedBigInteger('status_id')->nullable(true);
        });
        Schema::table('asset', function (Blueprint $table) {
            $table->foreign('identifiedobject_id')->on('identifiedobject')->references('id');
            $table->foreign('location_id')->on('location')->references('id');
            $table->foreign('status_id')->on('status')->references('id');
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
                $table->dropForeign('status_status_id_foreign');
                $table->dropColumn('status_id');
                $table->dropForeign('location_location_id_foreign');
                $table->dropColumn('location_id');
                $table->dropForeign('identifiedobject_identifiedobject_id_foreign');
                $table->dropColumn('identifiedobject_id');
                $table->dropColumn('electronic_address');
                $table->dropColumn('lot_number');
                $table->dropColumn('utc_number');
            });
        }
    }

}
