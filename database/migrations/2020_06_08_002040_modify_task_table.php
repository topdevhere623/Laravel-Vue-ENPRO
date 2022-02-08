<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTaskTable extends Migration
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
        Schema::table($this->tableName, function (Blueprint $table) {

            // в таблице task нужно добавить поле substation и связать ее с таблицей substation
            $table->unsignedBigInteger('substation_id')->nullable(); // ts добавил nullable()
            $table->unsignedBigInteger('connector_id')->nullable(); // ts добавил nullable()
            $table->string('json_file', 255)->nullable(); // ts добавил чтобы json хранить

            // добавилось поле uuid - идентификатор задачи в приложении
            $table->string('uuid', 36)->nullable();

            // связи
            $table->foreign('substation_id')
                ->references('id')->on('substation')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('connector_id')
                ->references('id')->on('connector')
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
            if (DB::getDriverName() !== 'sqlite')$table->dropColumn('substation_id');
            if (DB::getDriverName() !== 'sqlite')$table->dropForeign('substation_id');
            if (DB::getDriverName() !== 'sqlite')$table->dropForeign('connector_id');
        });
        Schema::table($this->tableName, function (Blueprint $table) {

            $table->dropColumn('json_file');
        });
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->dropColumn('connector_id');
        });
    }
}
