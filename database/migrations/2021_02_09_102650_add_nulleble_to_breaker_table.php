<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullebleToBreakerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('breaker', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('switchdrivemarkinfo_id')->nullable()->change();
            $table->unsignedBigInteger('identifiedobject_id')->nullable()->change();
            $table->unsignedBigInteger('asset_id')->nullable()->change();
            $table->unsignedBigInteger('switchrelay_id')->nullable()->change();
            $table->unsignedBigInteger('drive_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('breaker', function (Blueprint $table) {
            //
        });
    }
}
