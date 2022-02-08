<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrossingtypeTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'crossingtype';

    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // ts это новая таблица
            $table->bigIncrements('id');
            $table->string('name',255)->nullable();
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Типы пересеченной местности'");
        } catch (Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // на случай отката - удалить таблицу
        Schema::dropIfExists($this->tableName);
    }
}
