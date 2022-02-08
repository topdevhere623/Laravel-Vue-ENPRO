<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEndpointTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'endpoint';

    /**
     * Run the migrations.
     * @table substation_has_connector
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';

            // сделать таблицу endpoint с полем identifiedobject_id и name просто пока звание , status 0 или 1 и строка в таблице identifiedobject (IO)
            // ts это новая таблица
            $table->bigIncrements('id');
            $table->unsignedBigInteger('connector_id')->nullable(); // ts добавил id connector-а, иначе задача не билась (у одного endpoint-а один connector)
            $table->unsignedBigInteger('identifiedobject_id')->nullable(); // ts позже добавил nullable()
            $table->unsignedBigInteger('asset_id')->nullable(); // ts добавил, как у substation и connector
            $table->boolean('status')->default(1);

            // поля Laravel
            $table->softDeletes();
            $table->timestamps();

            // связи
            // ts cascade добавил
            $table->foreign('connector_id')
                ->references('id')->on('connector')
                ->onDelete('no action')
                ->onUpdate('no action');

            // ts cascade добавил
            $table->foreign('identifiedobject_id')
                ->references('id')->on('identifiedobject')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // ts cascade добавил
            $table->foreign('asset_id')
                ->references('id')->on('asset')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        // комментарий к таблице
        try {
DB::statement("ALTER TABLE `" . $this->tableName . "` comment 'Конечные точки'");
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
        // на случай отката - удалить таблицу
        Schema::dropIfExists($this->tableName);
    }
}
