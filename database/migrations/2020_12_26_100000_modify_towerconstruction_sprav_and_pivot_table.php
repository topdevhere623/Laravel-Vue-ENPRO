<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTowerconstructionSpravAndPivotTable extends Migration
{
    public function up()
    {
        // изменить тип листа в 3-х справочниках и добавить поле Марка
        Schema::table('towerconstructionbasic', function (Blueprint $table) {
            // изменить тип
            $table->dropColumn('sheet'); // $table->string('sheet', 255)->change();
            // вставить новый столбец
            $table->string('mark', 255)->nullable();
        });
        Schema::table('towerconstructionmetal', function (Blueprint $table) {
            // изменить тип
            $table->dropColumn('sheet'); // $table->string('sheet', 255)->change();
            // вставить новый столбец
            $table->string('mark', 255)->nullable();
        });
        Schema::table('towerconstructionaccessory', function (Blueprint $table) {
            // изменить тип
            $table->dropColumn('sheet'); // $table->string('sheet', 255)->change();
            // вставить новый столбец
            $table->string('mark', 255)->nullable();
        });

        // переименовать столбцы в pivot
        Schema::table('towerconstruction_pivots', function (Blueprint $table) {

            $table->renameColumn('towerconstructiontable_id', 'towerconstructionpivot_id');
        });
        Schema::table('towerconstruction_pivots', function (Blueprint $table) {

            $table->renameColumn('towerconstructiontable_type', 'towerconstructionpivot_type');
        });
    }

    // на случай отката
    public function down()
    {
        //
    }
}
