<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAclineStatusTable extends Migration
{
    // имя таблицы
    public $tableName = 'acline_status';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->string('name', 20, 4)->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Статусы линий'");
} catch (\Exception $e) {
}

        // заполнение даннными
        DB::statement("INSERT INTO `" . $this->tableName . "` (id, name, updated_at) VALUES " .
            "(1, 'черновик','2021-01-27 09:22:47'), " .
            "(2, 'на проверке','2021-01-27 09:22:47'), " .
            "(3, 'готово','2021-01-27 09:22:47')"
        );
    }

    // на случай отката - удалить таблицу
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
}
