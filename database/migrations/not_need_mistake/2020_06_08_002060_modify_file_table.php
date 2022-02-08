<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyFileTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'file';

    /**
     * Run the migrations.
     * @table task
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {

            $table->unsignedBigInteger('task_id')->nullable(); // ts у задачи task несколько файлов

            // связи
            // ts у задачи task несколько файлов
            $table->foreign('task_id')
                ->references('id')->on('task')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // на случай отката - удалить этот новый столбец
        Schema::table($this->tableName, function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite')$table->dropColumn('task_id');

            if (DB::getDriverName() !== 'sqlite')$table->dropForeign('task_id');
        });
    }
}
