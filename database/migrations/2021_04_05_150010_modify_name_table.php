<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyNameTable extends Migration
{

    // имя таблицы
    public $tableName = 'names';

    public function up()
    {
        // добавит признак (в частном случае - это для id линии)
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->BigInteger('object_id')->nullable();
        });
    }

    // на случай отката - удалить этот новый столбец
    public function down()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('object_id');
        });
    }
}
