<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectorFilePivotTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'connector_file_pivots';

    /**
     * Run the migrations.
     * @table user
     *
     * @return void
     */
    public function up()
    {
        // ts новая таблица
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('connector_id')->unsigned();
            $table->bigInteger('file_id')->unsigned()->default(0);

            // поля Laravel
            $table->timestamps();

            // связи
            $table->foreign('connector_id')
                ->references('id')->on('connector')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('file_id')
                ->references('id')->on('file')
                ->onUpdate('no action');
        });

        // комментарий к таблице
        DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Файлы коннекторов (сводная)'");
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
