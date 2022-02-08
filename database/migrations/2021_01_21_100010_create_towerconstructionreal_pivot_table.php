<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerconstructionrealpivotTable extends Migration
{
    // имя таблицы
    public $tableName = 'towerconstructionreal_pivots';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('tower_id');
            $table->string("towerconstructionpivot_type");
            $table->integer('towerconstructionpivot_id');
            $table->float('kol', 20, 4)->nullable();
            $table->boolean('mark')->default(0);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('tower_id')
                ->references('id')->on('tower')
                ->onDelete('cascade')
                ->onUpdate('cascade');

        });

        // комментарий к таблице
        if (DB::getDriverName() !== 'sqlite') DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Компоненты в реальной опоре (сводная)'");
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
