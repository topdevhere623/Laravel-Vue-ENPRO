<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAclineAddStatusIDTable extends Migration
{
    public function up()
    {
        Schema::table('acline', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->nullable()->default(1);

            // связи
            $table->foreign('status_id')
                ->references('id')->on('acline_status')
                ->onDelete('no action')
                ->onUpdate('no action');

            // удалить просто status
            $table->dropColumn('status');
        });

        // заполнение даннными
        if (DB::getDriverName() !== 'sqlite') DB::statement("UPDATE `acline` SET status_id = 1");
    }

    // на случай отката
    public function down()
    {
        //
    }
}
