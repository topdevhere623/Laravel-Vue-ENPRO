<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerconstructionstandartTable extends Migration
{
    // имя таблицы
    public $tableName = 'towerconstructionstandart';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('mark', 255)->nullable();
            $table->string('series', 255)->nullable();
            $table->string('album', 255)->nullable();
            $table->float('weight', 20, 7)->nullable();
            $table->string('img', 255)->nullable();
            $table->smallInteger('sort')->default(0);
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
        });

        // комментарий к таблице
        if (DB::getDriverName() !== 'sqlite')DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Компоненты - изделия стандартные'");
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
