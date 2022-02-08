<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyInsulatormarkTable extends Migration
{
    public function up()
    {
        // переименование справочника марски фундаментов опор и добавление новых полей
        Schema::rename('insulatormark', 'towerconstructioninsulator');

        // комментарий к таблице
        if (DB::getDriverName() !== 'sqlite')DB::statement("ALTER TABLE `towerconstructioninsulator` comment 'Компоненты - изоляторы'");

        Schema::table('towerconstructioninsulator', function (Blueprint $table) {
            $table->string('mark', 255)->nullable();
            $table->string('series', 255)->nullable();
            $table->string('album', 255)->nullable();
            $table->float('weight', 20, 7)->nullable();
            $table->string('img', 255)->nullable();
            $table->smallInteger('sort')->default(0);
            $table->boolean('status')->default(1);
        });
    }

    // на случай отката
    public function down()
    {
        //
    }
}
