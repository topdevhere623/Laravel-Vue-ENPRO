<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerconstructionpivotTable extends Migration
{
    // имя таблицы
    public $tableName = 'towerconstruction_pivots';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string("towerconstructiontable_type");
            $table->integer('towerconstructiontable_id');
            $table->float('kol', 20, 4)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
        });

        // комментарий к таблице
        if (DB::getDriverName() !== 'sqlite') DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Компоненты (сводная)'");
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
