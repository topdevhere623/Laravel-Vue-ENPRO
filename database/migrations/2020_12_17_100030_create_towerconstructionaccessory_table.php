<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTowerconstructionaccessoryTable extends Migration
{
    // имя таблицы
    public $tableName = 'towerconstructionaccessory';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name', 255)->nullable();
            $table->string('series', 255)->nullable();
            $table->string('album', 255)->nullable();
            $table->integer('sheet')->nullable();
            $table->string('img', 255)->nullable();
            $table->smallInteger('sort')->default(0);
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Компоненты - арматура линейная'");
} catch (\Exception $e) {
}
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
