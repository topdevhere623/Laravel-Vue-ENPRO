<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTowerconstructionPivotTable extends Migration
{
    // имя таблицы
    public $tableName = 'towerconstruction_pivots';

    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            // марки опор
            $table->unsignedBigInteger('towerinfo_id')->nullable();

            // связи
            $table->foreign('towerinfo_id')
                ->references('id')->on('towerinfo')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    // на случай отката - удалить этот новый столбец
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('towerinfo_id');
        });
    }
}
