<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'task';

    /**
     * Run the migrations.
     * @table task
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('tasktype_id')->nullable(); // ts добавил nullable()
            $table->integer('link')->nullable()->comment('Привязка к ID объекта в таблице, из типа ');
            $table->string('title', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->dateTime('startdate')->nullable();
            $table->dateTime('enddate')->nullable();
            $table->string('sorting', 255)->nullable();
            $table->integer('status')->nullable(); // ts добавил nullable()

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('tasktype_id')
                ->references('id')->on('tasktype')
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
