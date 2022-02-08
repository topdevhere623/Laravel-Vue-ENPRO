<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerconstructionaggregatepivotTable extends Migration
{
    // имя таблицы
    public $tableName = 'towerconstructionaggregate_pivots';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('towerconstructionaggregate_id');
            $table->string("towerconstructionpivot_type");
            $table->integer('towerconstructionpivot_id');
            $table->float('kol', 20, 4)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('towerconstructionaggregate_id', 'towerconstructionaggregate_id_foreign')
                ->references('id')->on('towerconstructionaggregate')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // комментарий к таблице
        if (DB::getDriverName() !== 'sqlite')DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Сборные агрегаты (сводная)'");
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
