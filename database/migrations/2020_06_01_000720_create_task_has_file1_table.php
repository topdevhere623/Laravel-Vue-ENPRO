<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskHasFile1Table extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'task_has_file1';

    /**
     * Run the migrations.
     * @table task_has_file1
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('task_id');
            $table->unsignedBigInteger('file_id');

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            $table->foreign('task_id')
                ->references('id')->on('task')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('file_id')
                ->references('id')->on('file')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment ''");
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
