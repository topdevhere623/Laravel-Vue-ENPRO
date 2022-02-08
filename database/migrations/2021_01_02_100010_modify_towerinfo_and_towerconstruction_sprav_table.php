<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTowerinfoAndTowerconstructionSpravTable extends Migration
{
    public function up()
    {
        // добавить поля в справочник марок опор
        Schema::table('towerinfo', function (Blueprint $table) {
            $table->string('mark', 255)->nullable();
            $table->string('series', 255)->nullable();
            $table->string('album', 255)->nullable();
            $table->float('weight', 20, 7)->nullable();
            $table->string('img_symbol', 255)->nullable();
            $table->string('img_draft', 255)->nullable();
        });

        // добавить вес в другие справочники
        Schema::table('towerconstructionbasic', function (Blueprint $table) {
            $table->float('weight', 20, 7)->nullable();
        });
        Schema::table('towerconstructionmetal', function (Blueprint $table) {
            $table->float('weight', 20, 7)->nullable();
        });
        Schema::table('towerconstructionaccessory', function (Blueprint $table) {
            $table->float('weight', 20, 7)->nullable();
        });

        // комментарии к таблицам подправить
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE `towerconstructionbasic` comment 'Компоненты - железобетонные элементы'");
            DB::statement("ALTER TABLE `towerconstructionmetal` comment 'Компоненты - стальные конструкции'");
            DB::statement("ALTER TABLE `towerconstructionaccessory` comment 'Компоненты - арматура линейная'");
        }
    }

    // на случай отката
    public function down()
    {
        //
    }
}
