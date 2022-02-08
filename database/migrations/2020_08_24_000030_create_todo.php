<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodo extends Migration
{
    public $tableName = 'todo';

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
            $table->unsignedBigInteger('status_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->text('description')->nullable();
            $table->date('date_begin')->nullable();
            $table->date('date_end')->nullable();

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('status_id')
                ->references('id')->on('todo_status')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Задачи'");
} catch (\Exception $e) {
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
