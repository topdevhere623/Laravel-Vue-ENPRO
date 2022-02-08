<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodoStageFioPivot extends Migration
{
    public $tableName = 'todo_stage_fio_pivots';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ts новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('todo_id')->unsigned();
            $table->bigInteger('stage_id')->unsigned();
            $table->bigInteger('fio_id')->unsigned();

            // поля Laravel
            $table->timestamps();

            // связи
            $table->foreign('todo_id')
                ->references('id')->on('todo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('stage_id')
                ->references('id')->on('todo_stages')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('fio_id')
                ->references('id')->on('fio')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
            DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Этапы задач (сводная)'");
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
        Schema::dropIfExists($this->tableName);
    }
}
