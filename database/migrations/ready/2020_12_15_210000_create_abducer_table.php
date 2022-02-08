<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbducerTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'abducer';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('abducerkind', 255)->nullable();
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
        DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Оттяжки'");

        // заполнение даннными
        DB::statement("INSERT INTO `" . $this->tableName . "` (id, name, album, manufactured, weight) VALUES " .
            "(1, 'ОТ3', '3.407.1-143.8.45', 'СЕЛЬЭНЕРГОПРОЕКТ', 9.6), " .
            "(2, 'ОТ4', '3.407.1-143.8.46', 'СЕЛЬЭНЕРГОПРОЕКТ', 64)"
        );
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}