<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateannexTable extends Migration
{
    public $tableName = 'annex';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('annexkind', 255)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('album', 255)->nullable();
            $table->string('manufactured', 255)->nullable();
            $table->double('weight', 8, 3)->nullable();
            $table->string('img', 255)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
        });

        // комментарий к таблице
        DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Приставки'");

        // заполнение даннными
        DB::statement("INSERT INTO `" . $this->tableName . "` (id, annexkind, name, weight, album, manufactured) VALUES " .
            "(1, 'ГОСТ 14295-75', 'ПТ45', 510, '3.407-57/72', null), " .
            "(2, null, 'ПТ43-2', 325, null, null)"
        );
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}